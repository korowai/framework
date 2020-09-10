<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap;

use Korowai\Testing\Ldaplib\CreateLdapLinkMockTrait;
use Korowai\Testing\Ldaplib\ExamineCallWithLdapTriggerErrorTrait;

use Korowai\Lib\Ldap\BindingTrait;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\BindingInterface;
use Korowai\Lib\Ldap\Exception\LdapException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait BindingTestTrait
{
    use CreateLdapLinkMockTrait;
    use ExamineCallWithLdapTriggerErrorTrait;

    abstract public function createBindingInstance(
        LdapLinkInterface $ldapLink,
        bool $bound = false
    ) : BindingInterface;

    private function examineMethodWithTriggerError(
        string $method,
        string $backendMethod,
        array $args,
        array $config,
        array $expect
    ) : void {
        $ldap = $this->createLdapLinkMock('ldap link', ['isValid', 'errno']);
        $bind = $this->createBindingInstance($ldap);

        $this->examineCallWithLdapTriggerError(
            function () use ($bind, $method, $args) : void {
                $bind->$method(...$args);
            },
            $ldap,
            $backendMethod,
            $args,
            $ldap,
            $config,
            $expect
        );
    }

    //
    //
    // TESTS
    //
    //

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // bind()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function prov__bind() : array
    {
        return [
            // #0
            [
                'args' => []
            ],
            // #1
            [
                'args' => ['dc=korowai,dc=org']
            ],
            // #2
            [
                'args' => ['dc=korowai,dc=org', '$3cr3t']
            ],
        ];
    }

    /**
     * @dataProvider prov__bind
     */
    public function test__bind(array $args) : void
    {
        $link = $this->createLdapLinkMock();

        $link->expects($this->once())
             ->method('bind')
             ->with(...$args)
             ->willReturn(true);

        $bind = $this->createBindingInstance($link);

        $bind->bind(...$args);
        $this->assertTrue($bind->isBound());
    }

    public static function prov__bind__withTriggerError() : array
    {
        return static::feedCallWithLdapTriggerError();
    }

    /**
     * @dataProvider prov__bind__withTriggerError
     */
    public function test__bind__withTriggerError(array $config, array $expect) : void
    {
        $this->examineMethodWithTriggerError('bind', 'bind', [], $config, $expect);
    }

    public function prov__bind__whenLdapLinkTriggersUnalteringLdapError() : array
    {
        return [
            // #0
            [
                49, 'DN contains a null byte',
            ],

            [
                49, 'Password contains a null byte',
            ],
        ];
    }

    /**
     * @dataProvider prov__bind__whenLdapLinkTriggersUnalteringLdapError
     */
    public function test__bind__whenLdapLinkTriggersUnalteringLdapError(int $errno, string $message) : void
    {
        $link = $this->createLdapLinkMock();
        $bind = $this->createBindingInstance($link, true);

        $link->expects($this->once())
             ->method('isValid')
             ->with()
             ->willReturn(true);

        $link->expects($this->once())
             ->method('errno')
             ->with()
             ->willReturn($errno);

        $link->expects($this->once())
             ->method('bind')
             ->with()
             ->will($this->returnCallback(function () use ($message) {
                 trigger_error($message);
                 return false;
             }));

        $this->expectException(LdapException::class);
        $this->expectExceptionMessage($message);
        $this->expectExceptionCode($errno);

        try {
            $bind->bind();
        } catch (\Throwable $throwable) {
            $this->assertTrue($bind->isBound());
            throw $throwable;
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // unbind()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function test__unbind() : void
    {
        $link = $this->createLdapLinkMock();

        $bind = $this->createBindingInstance($link, true);

        $link->expects($this->once())
             ->method('unbind')
             ->with()
             ->willReturn(true);

        $bind->unbind();
        $this->assertFalse($bind->isBound());
    }

    public static function prov__unbind__withTriggerError() : array
    {
        return static::feedCallWithLdapTriggerError();
    }

    /**
     * @dataProvider prov__unbind__withTriggerError
     */
    public function test__unbind__withTriggerError(array $config, array $expect):  void
    {
        $this->examineMethodWithTriggerError('unbind', 'unbind', [], $config, $expect);
    }
}

// vim: syntax=php sw=4 ts=4 et: