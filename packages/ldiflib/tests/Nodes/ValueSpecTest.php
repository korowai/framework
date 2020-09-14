<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif;

use Korowai\Lib\Ldif\Nodes\ValueSpec;
use Korowai\Lib\Ldif\Nodes\ValueSpecInterface;
use League\Uri\Contracts\UriInterface;
use League\Uri\Exceptions\SyntaxError;
use League\Uri\Uri;
use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Nodes\ValueSpec
 */
final class ValueSpecTest extends TestCase
{
    public function test__implmements__ValueSpecInterface() : void
    {
        $this->assertImplementsInterface(ValueSpecInterface::class, ValueSpec::class);
    }

    public function test__construct__isPrivate() : void
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('private');
        new ValueSpec(0, 'v', 'c');
    }

    public function test__safeString() : void
    {
        $value = ValueSpec::createSafeString('safe string');
        $this->assertInstanceOf(ValueSpec::class, $value);
        $this->assertSame(ValueSpec::TYPE_SAFE, $value->getType());
        $this->assertSame('safe string', $value->getSpec());
        $this->assertSame('safe string', $value->getContent());
    }

    public function test__bas64String() : void
    {
        $value = ValueSpec::createBase64String('YmFzZTY0IHN0cmluZw==');
        $this->assertInstanceOf(ValueSpec::class, $value);
        $this->assertSame(ValueSpec::TYPE_BASE64, $value->getType());
        $this->assertSame('YmFzZTY0IHN0cmluZw==', $value->getSpec());
        $this->assertSame('base64 string', $value->getContent());

        //

        $value = ValueSpec::createBase64String('YmFzZTY0IHN0cmluZw==', 'already decoded');
        $this->assertInstanceOf(ValueSpec::class, $value);
        $this->assertSame(ValueSpec::TYPE_BASE64, $value->getType());
        $this->assertSame('YmFzZTY0IHN0cmluZw==', $value->getSpec());
        $this->assertSame('already decoded', $value->getContent());
    }

    public function test__base64String__invalid() : void
    {
        $value = ValueSpec::createBase64String('YmFzZTY0IHN0cmluZ');
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('failed to decode base64 string');
        $value->getContent();
    }

    public function test__uri__fromUri() : void
    {
        $uri = Uri::createFromString(__file__);
        $value = ValueSpec::createUri($uri);
        $this->assertInstanceOf(ValueSpec::class, $value);
        $this->assertSame(ValueSpec::TYPE_URL, $value->getType());
        $this->assertSame($uri, $value->getSpec());
        $this->assertSame(file_get_contents(__file__), $value->getContent());
    }

    public function test__uri__fromComponents() : void
    {
        $components = ['path' => __file__];
        $value = ValueSpec::createUriFromComponents($components);
        $this->assertInstanceOf(ValueSpec::class, $value);
        $this->assertSame(ValueSpec::TYPE_URL, $value->getType());
        $this->assertInstanceOf(UriInterface::class, $value->getSpec());
        $this->assertSame(file_get_contents(__file__), $value->getContent());
    }

    public function test__uri__fromRfc3986Matches() : void
    {
        $value = ValueSpec::createUriFromRfc3986Matches(['path_absolute' => [ __file__, 123]]);
        $this->assertInstanceOf(ValueSpec::class, $value);
        $this->assertSame(ValueSpec::TYPE_URL, $value->getType());
        $uri = $value->getSpec();
        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertSame(__file__, $uri->getPath());
        $this->assertSame(file_get_contents(__file__), $value->getContent());
    }

    public static function uriFromRfc3986Matches__cases()
    {
        return [
            // #0
            [
                'matches' => [
                    'userinfo' => ['user', 0],
                    'path_abempty' => ['/abempty', 0],
                ],
                'expect' => [
                    'userinfo' => 'user',
                    'authority' => 'user@',
                    'path' => '/abempty',
                    'string' => '//user@/abempty',
                ]
            ],
            // #1
            [
                'matches' => [
                    'scheme' => ['http', 0],
                    'userinfo' => ['user:pass', 0],
                    'host' => ['example.org', 0],
                    'port' => ['123', 0],
                    'path_absolute' => ['/absolute', 0],
                    'query' => ['q=1', 0],
                    'fragment' => ['f=2' ,0],
                ],
                'expect' => [
                    'scheme' => 'http',
                    'authority' => 'user:pass@example.org:123',
                    'userinfo' => 'user:pass',
                    'host' => 'example.org',
                    'port' => 123,
                    'path' => '/absolute',
                    'query' => 'q=1',
                    'fragment' => 'f=2',
                    'string' => 'http://user:pass@example.org:123/absolute?q=1#f=2',
                ]
            ],
            // #2
            [
                'matches' => [
                    'path_rootless' => ['rootless', 0],
                ],
                'expect' => [
                    'path' => 'rootless',
                    'string' => 'rootless'
                ]
            ],
            // #3
            [
                'matches' => [
                    'path_rootless' => ['noscheme', 0],
                ],
                'expect' => [
                    'path' => 'noscheme',
                    'string' => 'noscheme',
                ]
            ],
            // #3
            [
                'matches' => [
                    'path_empty' => ['', 0],
                ],
                'expect' => [
                    'path' => '',
                    'string' => '',
                ]
            ],
        ];
    }

    /**
     * @dataProvider uriFromRfc3986Matches__cases
     */
    public function test__uri__fromRfc3986Matches__components(array $matches, array $expect) : void
    {
        $value = ValueSpec::createUriFromRfc3986Matches($matches);
        $this->assertInstanceOf(ValueSpec::class, $value);

        $uri = $value->getSpec();
        $this->assertInstanceOf(UriInterface::class, $uri);

        $this->assertSame($expect['scheme'] ?? null, $uri->getScheme());
        $this->assertSame($expect['authority'] ?? null, $uri->getAuthority());
        $this->assertSame($expect['userinfo'] ?? null, $uri->getUserInfo());
        $this->assertSame($expect['host'] ?? null, $uri->getHost());
        $this->assertSame($expect['port'] ?? null, $uri->getPort());
        $this->assertSame($expect['path'] ?? null, $uri->getPath());
        $this->assertSame($expect['query'] ?? null, $uri->getQuery());
        $this->assertSame($expect['fragment'] ?? null, $uri->getFragment());

        if (null !== ($string = $expect['string'] ?? null)) {
            $this->assertSame($string, (string)$uri);
        }
    }

    public function test__uri__withSyntaxErrorInScheme() : void
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage('The scheme `##` is invalid');
        ValueSpec::createUriFromComponents(['scheme' => '##']);
    }

    public function test__uri__withNonIntPortNumber() : void
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage('The port `asdf` is invalid (not an integer)');
        ValueSpec::createUriFromRfc3986Matches(['port' => ['asdf', 0]]);
    }

    public function test__uri__Unavailable() : void
    {
        $value = ValueSpec::createUriFromComponents(['path' => __dir__.'/inexistent.txt']);
        $this->assertInstanceOf(ValueSpec::class, $value);
        $this->assertSame(ValueSpec::TYPE_URL, $value->getType());
        $this->assertInstanceOf(UriInterface::class, $value->getSpec());

        $this->expectException(\ErrorException::class);
        $this->expectExceptionMessage("failed to open stream");
        $value->getContent();
    }

    public function test__throwInvalidTypeException() : void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('internal error: invalid type (5) set to '.ValueSpec::class.' object');

        ValueSpec::throwInvalidTypeException(5);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
