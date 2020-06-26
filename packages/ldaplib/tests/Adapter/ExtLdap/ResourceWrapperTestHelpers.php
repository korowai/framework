<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Adapter\ExtLdap;

use Korowai\Lib\Ldap\Adapter\ExtLdap\ResourceWrapperInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ResourceWrapperTestHelpers
{
    abstract public function getLdapFunctionMock(string $name);

    /**
     * Mocks is_resource() and get_resource_type() for argument $arg.
     *
     * @param  mixed $arg
     *      Expected argument value to be passed to mocked functions.
     * @param  mixed $return
     *      Value to be returned by mocked function; is_resource() will return
     *      ``(bool)$return`` while get_resource_type() will return
     *      ``(string)$return``.
     * @param  string $namespace
     */
    private function mockResourceFunctions($arg, $return, string $namespace = '') : void
    {
        if ($return !== null) {
            $this->getLdapFunctionMock('is_resource')
                 ->expects($this->any())
                 ->with($this->identicalTo($arg))
                 ->willReturn((bool)$return);
            if ($return) {
                $this->getLdapFunctionMock('get_resource_type')
                     ->expects($this->any())
                     ->with($this->identicalTo($arg))
                     ->willReturn((string)$return);
            }
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // supportsResourceType()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    private static function feedSupportsResourceType(string $supportedType) : array
    {
        return [
            // #0
            [
                'args'   => [$supportedType],
                'expect' => true,
            ],

            // #1
            [
                'args'   => ['Un$uPP0rt3D'],
                'expect' => false,
            ],
        ];
    }

    private function examineSupportsResourceType(
        ResourceWrapperInterface $wrapper,
        array $args,
        $expect
    ) : void {
        $this->assertSame($expect, $wrapper->supportsResourceType(...$args));
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // isValid()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    private static function feedIsValid(string $supportedType) : array
    {
        return [
            // #0
            [
                'arg'    => null,
                'return' => null,
                'expect' => false,
            ],

            // #1
            [
                'arg'    => 'foo',
                'return' => null,
                'expect' => false,
            ],

            // #2
            [
                'arg'    => 'mocked false',
                'return' => false,
                'expect' => false,
            ],

            // #3
            [
                'arg'    => 'mocked unknown',
                'return' => 'unknown',
                'expect' => false,
            ],

            // #4
            [
                'arg'    => $supportedType,
                'return' => $supportedType,
                'expect' => true,
            ],
        ];
    }

    private function examineIsValid(ResourceWrapperInterface $wrapper, $arg, $return, $expect) : void
    {
        $this->mockResourceFunctions($arg, $return);
        $this->assertSame($expect, $wrapper->isValid());
    }
}

// vim: syntax=php sw=4 ts=4 et:
