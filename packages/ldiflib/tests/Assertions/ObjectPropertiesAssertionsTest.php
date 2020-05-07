<?php
/**
 * @file tests/Assertions/ObjectPropertiesAssertionsTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Assertions;

use Korowai\Testing\TestCase;
use Korowai\Testing\Lib\Ldif\Assertions\ObjectPropertiesAssertions;
use Korowai\Lib\Ldif\SourceLocationInterface;
use Korowai\Lib\Ldif\LocationInterface;
use Korowai\Lib\Ldif\CursorInterface;
use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Lib\Ldif\ValueInterface;
use Korowai\Lib\Ldif\AttrValInterface;
use Korowai\Lib\Ldif\ControlInterface;
use Korowai\Lib\Ldif\ParserStateInterface;
use Korowai\Lib\Ldif\ParserErrorInterface;
use Korowai\Lib\Ldif\ParserError;
use League\Uri\Contracts\UriInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ObjectPropertiesAssertionsTest extends TestCase
{
    use ObjectPropertiesAssertions;

    // Required by ObjectPropertiesAssertions
    public static function objectPropertyGettersMap() : array
    {
        return self::$ldiflibObjectPropertyGettersMap;
    }

    //
    // assertSourceLocationHas
    //

    public function test__assertSourceLocationHas()
    {
        $getters = [
            'getSourceFileName'             => 'foo.ldif',
            'getSourceString'               => "# comment\nversion: 1\n",
	        'getSourceOffset'               => 10,
	        'getSourceCharOffset'           => 10,
	        'getSourceLineIndex'            => 1,
	        'getSourceLine'                 => "version: 1",
	        'getSourceLineAndOffset'        => [1, 0],
	        'getSourceLineAndCharOffset'    => [1, 0],
        ];

        $location = $this->getMockBuilder(SourceLocationInterface::class)->getMockForAbstractClass();
        foreach ($getters as $method => $value) {
            $location->expects($this->exactly(2))
                     ->method($method)
                     ->with()
                     ->willReturn($value);
        }

        $this->assertSourceLocationHas([
            'fileName'                      => 'foo.ldif',
            'sourceString'                  => "# comment\nversion: 1\n",
	        'sourceOffset'                  => 10,
	        'sourceCharOffset'              => 10,
	        'sourceLineIndex'               => 1,
	        'sourceLine'                    => "version: 1",
	        'sourceLineAndOffset'           => [1, 0],
	        'sourceLineAndCharOffset'       => [1, 0],
        ], $location);

        $this->assertSourceLocationHas([
            'getSourceFileName()'           => 'foo.ldif',
            'getSourceString()'             => "# comment\nversion: 1\n",
	        'getSourceOffset()'             => 10,
	        'getsourceCharOffset()'         => 10,
	        'getSourceLineIndex()'          => 1,
	        'getSourceLine()'               => "version: 1",
	        'getSourceLineAndOffset()'      => [1, 0],
	        'getSourceLineAndCharOffset()'  => [1, 0],
        ], $location);
    }

    //
    // assertLocationHas
    //

    public function test__assertLocationHas()
    {
        $getters = [
            'getSourceFileName'             => 'foo.ldif',
            'getSourceString'               => "# comment\nversion: 1\n",
	        'getSourceOffset'               => 10,
	        'getSourceCharOffset'           => 10,
	        'getSourceLineIndex'            => 1,
	        'getSourceLine'                 => "version: 1",
	        'getSourceLineAndOffset'        => [1, 0],
	        'getSourceLineAndCharOffset'    => [1, 0],
            'getString'                     => "version: 1\n",
            'getOffset'                     => 0,
            'getCharOffset'                 => 0,
        ];

        $location = $this->getMockBuilder(LocationInterface::class)->getMockForAbstractClass();
        foreach ($getters as $method => $value) {
            $location->expects($this->exactly(2))
                     ->method($method)
                     ->with()
                     ->willReturn($value);
        }

        $this->assertLocationHas([
            'fileName'                      => 'foo.ldif',
            'sourceString'                  => "# comment\nversion: 1\n",
	        'sourceOffset'                  => 10,
	        'sourceCharOffset'              => 10,
	        'sourceLineIndex'               => 1,
	        'sourceLine'                    => "version: 1",
	        'sourceLineAndOffset'           => [1, 0],
	        'sourceLineAndCharOffset'       => [1, 0],
            'string'                        => "version: 1\n",
            'offset'                        => 0,
            'charOffset'                    => 0,
        ], $location);

        $this->assertLocationHas([
            'getSourceFileName()'           => 'foo.ldif',
            'getSourceString()'             => "# comment\nversion: 1\n",
	        'getSourceOffset()'             => 10,
	        'getSourceCharOffset()'         => 10,
	        'getSourceLineIndex()'          => 1,
	        'getSourceLine()'               => "version: 1",
	        'getSourceLineAndOffset()'      => [1, 0],
	        'getSourceLineAndCharOffset()'  => [1, 0],
            'getString()'                   => "version: 1\n",
            'getOffset()'                   => 0,
            'getCharOffset()'               => 0,
        ], $location);
    }

    //
    // assertCursorHas
    //

    public function test__assertCursorHas()
    {
        $getters = [
            'getSourceFileName'             => 'foo.ldif',
            'getSourceString'               => "# comment\nversion: 1\n",
	        'getSourceOffset'               => 10,
	        'getSourceCharOffset'           => 10,
	        'getSourceLineIndex'            => 1,
	        'getSourceLine'                 => "version: 1",
	        'getSourceLineAndOffset'        => [1, 0],
	        'getSourceLineAndCharOffset'    => [1, 0],
            'getString'                     => "version: 1\n",
            'getOffset'                     => 0,
            'getCharOffset'                 => 0,
        ];

        $cursor = $this->getMockBuilder(CursorInterface::class)->getMockForAbstractClass();
        foreach ($getters as $method => $value) {
            $cursor->expects($this->exactly(2))
                   ->method($method)
                   ->with()
                   ->willReturn($value);
        }

        $this->assertCursorHas([
            'fileName'                      => 'foo.ldif',
            'sourceString'                  => "# comment\nversion: 1\n",
	        'sourceOffset'                  => 10,
	        'sourceCharOffset'              => 10,
	        'sourceLineIndex'               => 1,
	        'sourceLine'                    => "version: 1",
	        'sourceLineAndOffset'           => [1, 0],
	        'sourceLineAndCharOffset'       => [1, 0],
            'string'                        => "version: 1\n",
            'offset'                        => 0,
            'charOffset'                    => 0,
        ], $cursor);

        $this->assertCursorHas([
            'getSourceFileName()'           => 'foo.ldif',
            'getSourceString()'             => "# comment\nversion: 1\n",
	        'getSourceOffset()'             => 10,
	        'getSourceCharOffset()'         => 10,
	        'getSourceLineIndex()'          => 1,
	        'getSourceLine()'               => "version: 1",
	        'getSourceLineAndOffset()'      => [1, 0],
	        'getSourceLineAndCharOffset()'  => [1, 0],
            'getString()'                   => "version: 1\n",
            'getOffset()'                   => 0,
            'getCharOffset()'               => 0,
        ], $cursor);
    }

    //
    // assertSnippetHas
    //

    public function test__assertSnippetHas()
    {
        $getters = [
            'getSourceFileName'             => 'foo.ldif',
            'getSourceString'               => "# comment\nversion: 1\n",
	        'getSourceOffset'               => 10,
	        'getSourceCharOffset'           => 10,
	        'getSourceLineIndex'            => 1,
	        'getSourceLine'                 => "version: 1",
	        'getSourceLineAndOffset'        => [1, 0],
	        'getSourceLineAndCharOffset'    => [1, 0],
            'getString'                     => "version: 1\n",
            'getOffset'                     => 0,
            'getCharOffset'                 => 0,
            'getLength'                     => 7,
	        'getEndOffset'                  => 7,
	        'getSourceLength'               => 7,
	        'getSourceEndOffset'            => 17,
	        'getSourceCharLength'           => 7,
	        'getSourceCharEndOffset'        => 17,
        ];

        $snippet = $this->getMockBuilder(SnippetInterface::class)->getMockForAbstractClass();
        foreach ($getters as $method => $value) {
            $snippet->expects($this->exactly(2))
                    ->method($method)
                    ->with()
                    ->willReturn($value);
        }

        $this->assertSnippetHas([
            'fileName'                      => 'foo.ldif',
            'sourceString'                  => "# comment\nversion: 1\n",
	        'sourceOffset'                  => 10,
	        'sourceCharOffset'              => 10,
	        'sourceLineIndex'               => 1,
	        'sourceLine'                    => "version: 1",
	        'sourceLineAndOffset'           => [1, 0],
	        'sourceLineAndCharOffset'       => [1, 0],
            'string'                        => "version: 1\n",
            'offset'                        => 0,
            'charOffset'                    => 0,
            'length'                        => 7,
	        'endOffset'                     => 7,
	        'sourceLength'                  => 7,
	        'sourceEndOffset'               => 17,
	        'sourceCharLength'              => 7,
	        'sourceCharEndOffset'           => 17,
        ], $snippet);

        $this->assertSnippetHas([
            'getSourceFileName()'           => 'foo.ldif',
            'getSourceString()'             => "# comment\nversion: 1\n",
	        'getSourceOffset()'             => 10,
	        'getSourceCharOffset()'         => 10,
	        'getSourceLineIndex()'          => 1,
	        'getSourceLine()'               => "version: 1",
	        'getSourceLineAndOffset()'      => [1, 0],
	        'getSourceLineAndCharOffset()'  => [1, 0],
            'getString()'                   => "version: 1\n",
            'getOffset()'                   => 0,
            'getCharOffset()'               => 0,
            'getLength()'                   => 7,
	        'getEndOffset()'                => 7,
	        'getSourceLength()'             => 7,
	        'getSourceEndOffset()'          => 17,
	        'getSourceCharLength()'         => 7,
	        'getSourceCharEndOffset()'      => 17,
        ], $snippet);
    }

    //
    // assertParserErrorHas
    //

    public function test__assertParserErrorHas()
    {
        $previous = new \Exception('previous');

        $getters = [
            'getSourceFileName'             => 'foo.ldif',
            'getSourceString'               => "# comment\nversion: 1\n",
	        'getSourceOffset'               => 10,
	        'getSourceCharOffset'           => 10,
	        'getSourceLineIndex'            => 1,
	        'getSourceLine'                 => "version: 1",
	        'getSourceLineAndOffset'        => [1, 0],
	        'getSourceLineAndCharOffset'    => [1, 0],
        ];

        $location = $this->getMockBuilder(SourceLocationInterface::class)->getMockForAbstractClass();
        foreach ($getters as $method => $value) {
            $location->expects($this->atLeastOnce())
                     ->method($method)
                     ->with()
                     ->willReturn($value);
        }

        $line = 1 + __line__;
        $error = new ParserError($location, 'syntax error: foo', 0, $previous);

        $this->assertParserErrorHas([
            'fileName'                      => 'foo.ldif',
            'sourceString'                  => "# comment\nversion: 1\n",
	        'sourceOffset'                  => 10,
	        'sourceCharOffset'              => 10,
	        'sourceLineIndex'               => 1,
	        'sourceLine'                    => "version: 1",
	        'sourceLineAndOffset'           => [1, 0],
	        'sourceLineAndCharOffset'       => [1, 0],
            'message'                       => "syntax error: foo",
            'code'                          => 0,
            'file'                          => __file__,
            'line'                          => $line,
//            'trace'                         => ['T'],
//            'traceAsString'                 => 'T',
            'previous'                      => $previous,
            'multilineMessage'              => "foo.ldif:2:1:syntax error: foo\nfoo.ldif:2:1:version: 1\nfoo.ldif:2:1:^",
        ], $error);

        $this->assertParserErrorHas([
            'getSourceFileName()'           => 'foo.ldif',
            'getSourceString()'             => "# comment\nversion: 1\n",
	        'getSourceOffset()'             => 10,
	        'getSourceCharOffset()'         => 10,
	        'getSourceLineIndex()'          => 1,
	        'getSourceLine()'               => "version: 1",
	        'getSourceLineAndOffset()'      => [1, 0],
	        'getSourceLineAndCharOffset()'  => [1, 0],
            'getMessage()'                  => "syntax error: foo",
            'getCode()'                     => 0,
            'getFile()'                     => __file__,
            'getLine()'                     => $line,
//            'getTrace()'                    => ['T'],
//            'getTraceAsString()'            => 'T',
            'getPrevious()'                 => $previous,
            'getMultilineMessage()'         => "foo.ldif:2:1:syntax error: foo\nfoo.ldif:2:1:version: 1\nfoo.ldif:2:1:^",
        ], $error);
    }

    //
    // assertRecordHas
    //

    public function test__assertRecordHas()
    {
        $this->markTestIncomplete('The test has not been implemented yet.');
    }

    //
    // assertParserStateHas
    //

    public function test__assertParserStateHas()
    {
        $previous = new \Exception('previous');

        $errorGetters = [
            'getSourceFileName'             => 'foo.ldif',
            'getSourceString'               => "# comment\nversion: 1\n",
	        'getSourceOffset'               => 10,
	        'getSourceCharOffset'           => 10,
	        'getSourceLineIndex'            => 1,
	        'getSourceLine'                 => "version: 1",
	        'getSourceLineAndOffset'        => [1, 0],
	        'getSourceLineAndCharOffset'    => [1, 0],
        ];

        $errorLocation = $this->getMockBuilder(SourceLocationInterface::class)->getMockForAbstractClass();
        foreach ($errorGetters as $method => $value) {
            $errorLocation->expects($this->atLeastOnce())
                     ->method($method)
                     ->with()
                     ->willReturn($value);
        }

        $line = 1 + __line__;
        $error = new ParserError($errorLocation, 'syntax error: foo', 0, $previous);


        $cursorGetters = [
            'getSourceFileName'             => 'foo.ldif',
            'getSourceString'               => "# comment\nversion: 1\n",
	        'getSourceOffset'               => 10,
	        'getSourceCharOffset'           => 10,
	        'getSourceLineIndex'            => 1,
	        'getSourceLine'                 => "version: 1",
	        'getSourceLineAndOffset'        => [1, 0],
	        'getSourceLineAndCharOffset'    => [1, 0],
            'getString'                     => "version: 1\n",
            'getOffset'                     => 0,
            'getCharOffset'                 => 0,
        ];

        $cursor = $this->getMockBuilder(CursorInterface::class)->getMockForAbstractClass();
        foreach ($cursorGetters as $method => $value) {
            $cursor->expects($this->exactly(2))
                   ->method($method)
                   ->with()
                   ->willReturn($value);
        }

        $getters = [
            'getCursor'                     => $cursor,
            'getErrors'                     => [$error],
            'getRecords'                    => [],
            'isOk'                          => false,
        ];

        $state = $this->getMockBuilder(ParserStateInterface::class)->getMockForAbstractClass();
        foreach ($getters as $method => $value) {
            $state->expects($this->exactly(2))
                  ->method($method)
                  ->with()
                  ->willReturn($value);
        }

        $this->assertParserStateHas([
            'cursor'                        => [
                'fileName'                  => 'foo.ldif',
                'sourceString'              => "# comment\nversion: 1\n",
                'sourceOffset'              => 10,
                'sourceCharOffset'          => 10,
                'sourceLineIndex'           => 1,
                'sourceLine'                => "version: 1",
                'sourceLineAndOffset'       => [1, 0],
                'sourceLineAndCharOffset'   => [1, 0],
                'string'                    => "version: 1\n",
                'offset'                    => 0,
                'charOffset'                => 0,
            ],
            'errors'                        => [
                [
                    'fileName'              => 'foo.ldif',
                    'sourceString'          => "# comment\nversion: 1\n",
                    'sourceOffset'          => 10,
                    'sourceCharOffset'      => 10,
                    'sourceLineIndex'       => 1,
                    'sourceLine'            => "version: 1",
                    'sourceLineAndOffset'   => [1, 0],
                    'sourceLineAndCharOffset' => [1, 0],
                    'message'               => "syntax error: foo",
                    'code'                  => 0,
                    'file'                  => __file__,
                    'line'                  => $line,
//                    'trace'               => ['T'],
//                    'traceAsString'       => 'T',
                    'previous'              => $previous,
                    'multilineMessage'      => "foo.ldif:2:1:syntax error: foo\nfoo.ldif:2:1:version: 1\nfoo.ldif:2:1:^",
                ]
            ],
            'records'                       => [
            ],
            'isOk'                          => false,
        ], $state);

        $this->assertParserStateHas([
            'getCursor()'                   => [
                'getSourceFileName()'       => 'foo.ldif',
                'getSourceString()'         => "# comment\nversion: 1\n",
                'getSourceOffset()'         => 10,
                'getSourceCharOffset()'     => 10,
                'getSourceLineIndex()'      => 1,
                'getSourceLine()'           => "version: 1",
                'getSourceLineAndOffset()'     => [1, 0],
                'getSourceLineAndCharOffset()' => [1, 0],
                'getString()'               => "version: 1\n",
                'getOffset()'               => 0,
                'getCharOffset()'           => 0,
            ],
            'getErrors()'                   => [
                [
                    'getSourceFileName()'   => 'foo.ldif',
                    'getSourceString()'     => "# comment\nversion: 1\n",
                    'getSourceOffset()'     => 10,
                    'getSourceCharOffset()' => 10,
                    'getSourceLineIndex()'  => 1,
                    'getSourceLine()'       => "version: 1",
                    'getSourceLineAndOffset()'     => [1, 0],
                    'getSourceLineAndCharOffset()' => [1, 0],
                    'getMessage()'          => "syntax error: foo",
                    'getCode()'             => 0,
                    'getFile()'             => __file__,
                    'getLine()'             => $line,
//                    'getTrace()'          => ['T'],
//                    'getTraceAsString()'  => 'T',
                    'getPrevious()'         => $previous,
                    'getMultilineMessage()' => "foo.ldif:2:1:syntax error: foo\nfoo.ldif:2:1:version: 1\nfoo.ldif:2:1:^",
                ]
            ],
            'getRecords()'                  => [
            ],
            'isOk()'                        => false,
        ], $state);
    }

    //
    // assertValueHas
    //

    public function test__assertValueHas__withStringSpec()
    {
        $getters = [
            'getType'                       => 1,
            'getSpec'                       => 'Zm9v',
	        'getContent'                    => 'foo',
        ];

        $valueObject = $this->getMockBuilder(ValueInterface::class)->getMockForAbstractClass();
        foreach ($getters as $method => $value) {
            $valueObject->expects($this->exactly(2))
                        ->method($method)
                        ->with()
                        ->willReturn($value);
        }

        $this->assertValueHas([
            'type'                          => 1,
            'spec'                          => 'Zm9v',
            'content'                       => 'foo',
        ], $valueObject);

        $this->assertValueHas([
            'getType()'                     => 1,
            'getSpec()'                     => 'Zm9v',
            'getContent()'                  => 'foo',
        ], $valueObject);
    }

    public function test__assertValueHas__withUriSpec()
    {
        $uriGetters = [
            '__toString'                    => 'http://jsmith:pass@example.com:123/foo/bar?q=1#f=2',
            'getScheme'                     => 'http',
            'getAuthority'                  => 'jsmith:pass@example.com:123',
            'getUserInfo'                   => 'jsmith:pass',
            'getHost'                       => 'example.com',
            'getPort'                       => 123,
            'getPath'                       => '/foo/bar',
            'getQuery'                      => 'q=1',
            'getFragment'                   => 'f=2',
        ];

        $uriObject = $this->getMockBuilder(UriInterface::class)->getMockForAbstractClass();
        foreach ($uriGetters as $method => $value) {
            $uriObject->expects($this->exactly(2))
                        ->method($method)
                        ->with()
                        ->willReturn($value);
        }

        $getters = [
            'getType'                       => 1,
            'getSpec'                       => $uriObject,
	        'getContent'                    => 'foo',
        ];

        $valueObject = $this->getMockBuilder(ValueInterface::class)->getMockForAbstractClass();
        foreach ($getters as $method => $value) {
            $valueObject->expects($this->exactly(2))
                        ->method($method)
                        ->with()
                        ->willReturn($value);
        }

        $this->assertValueHas([
            'type'                          => 1,
            'spec'                          => [
                'string'                    => 'http://jsmith:pass@example.com:123/foo/bar?q=1#f=2',
                'scheme'                    => 'http',
                'authority'                 => 'jsmith:pass@example.com:123',
                'userinfo'                  => 'jsmith:pass',
                'host'                      => 'example.com',
                'port'                      => 123,
                'path'                      => '/foo/bar',
                'query'                     => 'q=1',
                'fragment'                  => 'f=2',
            ],
            'content'                       => 'foo',
        ], $valueObject);

        $this->assertValueHas([
            'getType()'                     => 1,
            'getSpec()'                     => [
                '__toString()'              => 'http://jsmith:pass@example.com:123/foo/bar?q=1#f=2',
                'getScheme()'               => 'http',
                'getAuthority()'            => 'jsmith:pass@example.com:123',
                'getUserinfo()'             => 'jsmith:pass',
                'getHost()'                 => 'example.com',
                'getPort()'                 => 123,
                'getPath()'                 => '/foo/bar',
                'getQuery()'                => 'q=1',
                'getFragment()'             => 'f=2',
            ],
            'getContent()'                  => 'foo',
        ], $valueObject);
    }

    //
    // assertAttrValHas
    //

    public function test__assertAttrValHas__withStringValue()
    {
        $valueGetters = [
            'getType'                       => 1,
            'getSpec'                       => 'Zm9v',
	        'getContent'                    => 'foo',
        ];

        $valueObject = $this->getMockBuilder(ValueInterface::class)->getMockForAbstractClass();
        foreach ($valueGetters as $method => $value) {
            $valueObject->expects($this->atLeastOnce())
                        ->method($method)
                        ->with()
                        ->willReturn($value);
        }

        $attrValGetters = [
            'getAttribute'                  => 'bar',
            'getValueObject'                => $valueObject
        ];
        $attrVal = $this->getMockBuilder(AttrValInterface::class)->getMockForAbstractClass();
        foreach ($attrValGetters as $method => $value) {
            $attrVal->expects($this->exactly(2))
                    ->method($method)
                    ->with()
                    ->willReturn($value);
        }


        $this->assertAttrValHas([
            'attribute'                     => 'bar',
            'valueObject'                   => [
                'type'                      => 1,
                'spec'                      => 'Zm9v',
                'content'                   => 'foo',
            ]
        ], $attrVal);

        $this->assertAttrValHas([
            'getAttribute()'                => 'bar',
            'getValueObject()'              => [
                'getType()'                 => 1,
                'getSpec()'                 => 'Zm9v',
                'getContent()'              => 'foo',
            ]
        ], $attrVal);
    }

    public function test__assertAttrValHas__withUriValue()
    {
        $uriGetters = [
            '__toString'                    => 'http://jsmith:pass@example.com:123/foo/bar?q=1#f=2',
            'getScheme'                     => 'http',
            'getAuthority'                  => 'jsmith:pass@example.com:123',
            'getUserInfo'                   => 'jsmith:pass',
            'getHost'                       => 'example.com',
            'getPort'                       => 123,
            'getPath'                       => '/foo/bar',
            'getQuery'                      => 'q=1',
            'getFragment'                   => 'f=2',
        ];

        $uriObject = $this->getMockBuilder(UriInterface::class)->getMockForAbstractClass();
        foreach ($uriGetters as $method => $value) {
            $uriObject->expects($this->exactly(2))
                        ->method($method)
                        ->with()
                        ->willReturn($value);
        }

        $valueGetters = [
            'getType'                       => 1,
            'getSpec'                       => $uriObject,
	        'getContent'                    => 'foo',
        ];

        $valueObject = $this->getMockBuilder(ValueInterface::class)->getMockForAbstractClass();
        foreach ($valueGetters as $method => $value) {
            $valueObject->expects($this->exactly(2))
                        ->method($method)
                        ->with()
                        ->willReturn($value);
        }

        $attrValGetters = [
            'getAttribute'                  => 'bar',
            'getValueObject'                => $valueObject
        ];
        $attrVal = $this->getMockBuilder(AttrValInterface::class)->getMockForAbstractClass();
        foreach ($attrValGetters as $method => $value) {
            $attrVal->expects($this->exactly(2))
                    ->method($method)
                    ->with()
                    ->willReturn($value);
        }

        $this->assertAttrValHas([
            'attribute'                     => 'bar',
            'valueObject'                   => [
                'type'                      => 1,
                'spec'                      => [
                    'string'                => 'http://jsmith:pass@example.com:123/foo/bar?q=1#f=2',
                    'scheme'                => 'http',
                    'authority'             => 'jsmith:pass@example.com:123',
                    'userinfo'              => 'jsmith:pass',
                    'host'                  => 'example.com',
                    'port'                  => 123,
                    'path'                  => '/foo/bar',
                    'query'                 => 'q=1',
                    'fragment'              => 'f=2',
                ],
                'content'                   => 'foo',
            ]
        ], $attrVal);

        $this->assertAttrValHas([
            'getAttribute()'                => 'bar',
            'getValueObject()'              => [
                'getType()'                 => 1,
                'getSpec()'                 => [
                    '__toString()'          => 'http://jsmith:pass@example.com:123/foo/bar?q=1#f=2',
                    'getScheme()'           => 'http',
                    'getAuthority()'        => 'jsmith:pass@example.com:123',
                    'getUserinfo()'         => 'jsmith:pass',
                    'getHost()'             => 'example.com',
                    'getPort()'             => 123,
                    'getPath()'             => '/foo/bar',
                    'getQuery()'            => 'q=1',
                    'getFragment()'         => 'f=2',
                ],
                'getContent()'              => 'foo',
            ]
        ], $attrVal);
    }

    //
    // assertControlHas
    //

    public function test__assertControlHas__withNullValue()
    {
        $ctlGetters = [
            'getOid'                        => 'bar',
            'getCriticality'                => null,
            'getValueObject'                => null
        ];
        $ctl = $this->getMockBuilder(ControlInterface::class)->getMockForAbstractClass();
        foreach ($ctlGetters as $method => $value) {
            $ctl->expects($this->exactly(2))
                ->method($method)
                ->with()
                ->willReturn($value);
        }


        $this->assertControlHas([
            'oid'                           => 'bar',
            'criticality'                   => null,
            'valueObject'                   => null,
        ], $ctl);

        $this->assertControlHas([
            'getOid()'                      => 'bar',
            'getCriticality()'              => null,
            'getValueObject()'              => null,
        ], $ctl);
    }

    public function test__assertControlHas__withStringValue()
    {
        $valueGetters = [
            'getType'                       => 1,
            'getSpec'                       => 'Zm9v',
	        'getContent'                    => 'foo',
        ];

        $valueObject = $this->getMockBuilder(ValueInterface::class)->getMockForAbstractClass();
        foreach ($valueGetters as $method => $value) {
            $valueObject->expects($this->atLeastOnce())
                        ->method($method)
                        ->with()
                        ->willReturn($value);
        }

        $ctlGetters = [
            'getOid'                        => 'bar',
            'getCriticality'                => true,
            'getValueObject'                => $valueObject
        ];
        $ctl = $this->getMockBuilder(ControlInterface::class)->getMockForAbstractClass();
        foreach ($ctlGetters as $method => $value) {
            $ctl->expects($this->exactly(2))
                ->method($method)
                ->with()
                ->willReturn($value);
        }


        $this->assertControlHas([
            'oid'                           => 'bar',
            'criticality'                   => true,
            'valueObject'                   => [
                'type'                      => 1,
                'spec'                      => 'Zm9v',
                'content'                   => 'foo',
            ]
        ], $ctl);

        $this->assertControlHas([
            'getOid()'                      => 'bar',
            'getCriticality()'              => true,
            'getValueObject()'              => [
                'getType()'                 => 1,
                'getSpec()'                 => 'Zm9v',
                'getContent()'              => 'foo',
            ]
        ], $ctl);
    }

    public function test__assertControlHas__withUriValue()
    {
        $uriGetters = [
            '__toString'                    => 'http://jsmith:pass@example.com:123/foo/bar?q=1#f=2',
            'getScheme'                     => 'http',
            'getAuthority'                  => 'jsmith:pass@example.com:123',
            'getUserInfo'                   => 'jsmith:pass',
            'getHost'                       => 'example.com',
            'getPort'                       => 123,
            'getPath'                       => '/foo/bar',
            'getQuery'                      => 'q=1',
            'getFragment'                   => 'f=2',
        ];

        $uriObject = $this->getMockBuilder(UriInterface::class)->getMockForAbstractClass();
        foreach ($uriGetters as $method => $value) {
            $uriObject->expects($this->exactly(2))
                        ->method($method)
                        ->with()
                        ->willReturn($value);
        }

        $valueGetters = [
            'getType'                       => 1,
            'getSpec'                       => $uriObject,
	        'getContent'                    => 'foo',
        ];

        $valueObject = $this->getMockBuilder(ValueInterface::class)->getMockForAbstractClass();
        foreach ($valueGetters as $method => $value) {
            $valueObject->expects($this->exactly(2))
                        ->method($method)
                        ->with()
                        ->willReturn($value);
        }

        $ctlGetters = [
            'getOid'                        => 'bar',
            'getCriticality'                => true,
            'getValueObject'                => $valueObject
        ];
        $ctl = $this->getMockBuilder(ControlInterface::class)->getMockForAbstractClass();
        foreach ($ctlGetters as $method => $value) {
            $ctl->expects($this->exactly(2))
                ->method($method)
                ->with()
                ->willReturn($value);
        }

        $this->assertControlHas([
            'oid'                           => 'bar',
            'criticality'                   => true,
            'valueObject'                   => [
                'type'                      => 1,
                'spec'                      => [
                    'string'                => 'http://jsmith:pass@example.com:123/foo/bar?q=1#f=2',
                    'scheme'                => 'http',
                    'authority'             => 'jsmith:pass@example.com:123',
                    'userinfo'              => 'jsmith:pass',
                    'host'                  => 'example.com',
                    'port'                  => 123,
                    'path'                  => '/foo/bar',
                    'query'                 => 'q=1',
                    'fragment'              => 'f=2',
                ],
                'content'                   => 'foo',
            ]
        ], $ctl);

        $this->assertControlHas([
            'getOid()'                      => 'bar',
            'getCriticality()'              => true,
            'getValueObject()'              => [
                'getType()'                 => 1,
                'getSpec()'                 => [
                    '__toString()'          => 'http://jsmith:pass@example.com:123/foo/bar?q=1#f=2',
                    'getScheme()'           => 'http',
                    'getAuthority()'        => 'jsmith:pass@example.com:123',
                    'getUserinfo()'         => 'jsmith:pass',
                    'getHost()'             => 'example.com',
                    'getPort()'             => 123,
                    'getPath()'             => '/foo/bar',
                    'getQuery()'            => 'q=1',
                    'getFragment()'         => 'f=2',
                ],
                'getContent()'              => 'foo',
            ]
        ], $ctl);
    }

    //
    // assertUriHas
    //

    public function test__assertUriHas()
    {
        $getters = [
            '__toString'                    => 'http://jsmith:pass@example.com:123/foo/bar?q=1#f=2',
            'getScheme'                     => 'http',
            'getAuthority'                  => 'jsmith:pass@example.com:123',
            'getUserInfo'                   => 'jsmith:pass',
            'getHost'                       => 'example.com',
            'getPort'                       => 123,
            'getPath'                       => '/foo/bar',
            'getQuery'                      => 'q=1',
            'getFragment'                   => 'f=2',
        ];

        $uriObject = $this->getMockBuilder(UriInterface::class)->getMockForAbstractClass();
        foreach ($getters as $method => $value) {
            $uriObject->expects($this->exactly(2))
                        ->method($method)
                        ->with()
                        ->willReturn($value);
        }

        $this->assertUriHas([
            'string'                        => 'http://jsmith:pass@example.com:123/foo/bar?q=1#f=2',
            'scheme'                        => 'http',
            'authority'                     => 'jsmith:pass@example.com:123',
            'userinfo'                      => 'jsmith:pass',
            'host'                          => 'example.com',
            'port'                          => 123,
            'path'                          => '/foo/bar',
            'query'                         => 'q=1',
            'fragment'                      => 'f=2',
        ], $uriObject);

        $this->assertUriHas([
            '__toString()'                  => 'http://jsmith:pass@example.com:123/foo/bar?q=1#f=2',
            'getScheme()'                   => 'http',
            'getAuthority()'                => 'jsmith:pass@example.com:123',
            'getUserinfo()'                 => 'jsmith:pass',
            'getHost()'                     => 'example.com',
            'getPort()'                     => 123,
            'getPath()'                     => '/foo/bar',
            'getQuery()'                    => 'q=1',
            'getFragment()'                 => 'f=2',
        ], $uriObject);
    }
}

// vim: syntax=php sw=4 ts=4 et:
