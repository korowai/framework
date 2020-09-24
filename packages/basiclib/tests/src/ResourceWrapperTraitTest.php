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

use Korowai\Lib\Basic\ResourceWrapperInterface;
use Korowai\Lib\Basic\ResourceWrapperTrait;
use Korowai\Testing\Basiclib\ResourceWrapperTestHelpersTrait;
use Korowai\Testing\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Basic\ResourceWrapperTrait
 *
 * @internal
 */
final class ResourceWrapperTraitTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;
    use ResourceWrapperTestHelpersTrait;

    public function testGetResource(): void
    {
        $wrapper = static::createDummyResourceWrapper('foo');
        $this->assertSame('foo', $wrapper->getResource());
    }

    public function prov__isValid(): array
    {
        return static::feedIsValid('foo');
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__isValid
     *
     * @param mixed $arg
     * @param mixed $return
     * @param mixed $expect
     */
    public function testIsValid($arg, $return, $expect): void
    {
        $wrapper = static::createDummyResourceWrapper($arg);
        $this->examineIsValid($wrapper, $arg, $return, $expect);
    }

    private static function createDummyResourceWrapper($resource): ResourceWrapperInterface
    {
        return new class($resource) implements ResourceWrapperInterface {
            use ResourceWrapperTrait;

            public function __construct($resource)
            {
                $this->resource = $resource;
            }

            public function supportsResourceType(string $type): bool
            {
                return 'foo' === $type;
            }
        };
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
