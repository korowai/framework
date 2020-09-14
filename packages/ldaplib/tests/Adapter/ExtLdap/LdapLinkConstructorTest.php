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

use Korowai\Testing\Ldaplib\TestCase;
use Korowai\Testing\Ldaplib\MakeArgsForLdapFunctionMockTrait;
use Korowai\Testing\Ldaplib\GetLdapFunctionMockTrait;

use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkConstructor;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkConstructorInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLink;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkConstructor
 */
final class LdapLinkConstructorTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;
    use GetLdapFunctionMockTrait;
    use MakeArgsForLdapFunctionMockTrait;

    //
    //
    // TESTS
    //
    //

    public function test__implements__LdapLinkConstructorInterface() : void
    {
        $this->assertImplementsInterface(LdapLinkConstructorInterface::class, LdapLinkConstructor::class);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // connect()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__connect() : array
    {
        return [
            // #0
            [
                'args'   => [],
                'return' => 'ldap link 1',
                'expect' => ['getResource()' => 'ldap link 1'],
            ],
            // #1
            [
                'args'   => ['ldapi:///'],
                'return' => 'ldap link 2',
                'expect' => ['getResource()' => 'ldap link 2'],
            ],
            // #2
            [
                'args'   => ['localhost', 123],
                'return' => 'ldap link 3',
                'expect' => ['getResource()' => 'ldap link 3'],
            ],
            // #3
            [
                'args'   => [null],
                'return' => 'ldap link 4',
                'expect' => ['getResource()' => 'ldap link 4'],
            ],
            // #4
            [
                'args'   => [null, 123],
                'return' => 'ldap link 5',
                'expect' => ['getResource()' => 'ldap link 5'],
            ],
            // #5
            [
                'args'   => ['ldapi:///', 123],
                'return' => 'ldap link 6',
                'expect' => ['getResource()' => 'ldap link 6'],
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__connect
     */
    public function test__connect(array $args, $return, $expect) : void
    {
        $ldapArgs = $this->makeArgsForLdapFunctionMock([], $args);
        if (count($args) === 2 && $args[1] === null) {
            unset($ldapArgs[1]);
        }
        $this   ->getLdapFunctionMock("ldap_connect")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $constructor = new LdapLinkConstructor;

        $link = $constructor->connect(...$args);
        if ($return) {
            $this->assertInstanceOf(LdapLink::class, $link);
            $this->assertSame($return, $link->getResource());
            $this->assertHasPropertiesSameAs($expect, $link);
        } else {
            $this->assertSame($expect, $link);
        }
    }

    public function prov__connect__witLdapLinkReturningFalse() : array
    {
        return [
            // #0
            [
                'args'   => ['#$%'],
                'return' => false,
            ],
            // #1
            [
                'args'   => ['#$%'],
                'return' => null
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__connect__witLdapLinkReturningFalse
     */
    public function test__connect__witLdapLinkReturningFalse(array $args, $return) : void
    {
        $ldapArgs = $this->makeArgsForLdapFunctionMock([], $args);
        if (count($args) === 2 && $args[1] === null) {
            unset($ldapArgs[1]);
        }
        $this   ->getLdapFunctionMock("ldap_connect")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $constructor = new LdapLinkConstructor;

        $this->expectException(\ErrorException::class);
        $this->expectExceptionMessage('LdapLink::connect() returned false');

        $link = $constructor->connect(...$args);
    }


    /**
     * @runInSeparateProcess
     */
    public function test__connect__withLdapConnectTriggeringError() : void
    {
        $this   ->getLdapFunctionMock("ldap_connect")
                ->expects($this->once())
                ->with("#$%")
                ->will($this->returnCallback(function () {
                    trigger_error('error message');
                    return false;
                }));

        $constructor = new LdapLinkConstructor;

        $this->expectException(\ErrorException::class);
        $this->expectExceptionMessage('error message');

        $link = $constructor->connect("#$%");
    }
}

// vim: syntax=php sw=4 ts=4 et tw=120:
