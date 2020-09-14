<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Basic;

use Korowai\Testing\TestCase;
use Korowai\Testing\Basiclib\ResourceWrapperTestHelpersTrait;

use Korowai\Lib\Basic\ResourceWrapperTrait;
use Korowai\Lib\Basic\ResourceWrapperInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Basic\ResourceWrapperTrait
 */
final class ResourceWrapperTraitTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;
    use ResourceWrapperTestHelpersTrait;

    private static function createDummyResourceWrapper($resource) : ResourceWrapperInterface
    {
        return new class($resource) implements ResourceWrapperInterface {
            use ResourceWrapperTrait;
            public function __construct($resource)
            {
                $this->resource = $resource;
            }
            public function supportsResourceType(string $type) : bool
            {
                return $type === 'foo';
            }
        };
    }

    public function test__getResource() : void
    {
        $wrapper = static::createDummyResourceWrapper('foo');
        $this->assertSame('foo', $wrapper->getResource());
    }

    public function prov__isValid() : array
    {
        return static::feedIsValid('foo');
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__isValid
     */
    public function test__isValid($arg, $return, $expect) : void
    {
        $wrapper = static::createDummyResourceWrapper($arg);
        $this->examineIsValid($wrapper, $arg, $return, $expect);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=120:
