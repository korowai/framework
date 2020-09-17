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
use Korowai\Testing\Ldaplib\ExamineWithLdapTriggerErrorTrait;

use Korowai\Lib\Ldap\BindingTrait;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkErrorHandler;
use Korowai\Lib\Ldap\BindingInterface;
use Korowai\Lib\Ldap\Exception\LdapException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait BindingTestTrait
{
    use CreateLdapLinkMockTrait;
    use ExamineWithLdapTriggerErrorTrait;

    abstract public function createBindingInstance(
        LdapLinkInterface $ldapLink,
        bool $bound = false
    ) : BindingInterface;

    private function examineBindingMethodWithTriggerError(
        string $method,
        string $backendMethod,
        array $args,
        array $config,
        array $expect
    ) : void {
        $link = $this->createLdapLinkMock();
        $bind = $this->createBindingInstance($link);

        $this->examineWithLdapTriggerError(
            function () use ($bind, $method, $args) : void {
                $bind->$method(...$args);
            },
            $link,
            $backendMethod,
            $args,
            $link,
            $config,
            $expect
        );
        // @codeCoverageIgnoreStart
        return; // unreachable
        // @codeCoverageIgnoreEnd
    }

    //
    //
    // TESTS
    //
    //

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // bind()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__bind() : array
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
        // @codeCoverageIgnoreStart
        return static::feedWithLdapTriggerError();
        // @codeCoverageIgnoreEnd
    }

    /**
     * @dataProvider prov__bind__withTriggerError
     */
    public function test__bind__withTriggerError(array $config, array $expect) : void
    {
        $this->examineBindingMethodWithTriggerError('bind', 'bind', [], $config, $expect);
        // @codeCoverageIgnoreStart
        return;
        // @codeCoverageIgnoreEnd
    }

    public static function prov__bind__whenLdapLinkTriggersUnalteringLdapError() : array
    {
        // @codeCoverageIgnoreStart
        return [
            // #0
            [
                49, 'DN contains a null byte',
            ],

            [
                49, 'Password contains a null byte',
            ],
        ];
        // @codeCoverageIgnoreEnd
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
             ->method('getErrorHandler')
             ->with()
             ->willReturn($errorHandler = new LdapLinkErrorHandler($link));

        $link->expects($this->once())
             ->method('bind')
             ->with()
             ->will($this->returnCallback(function () use ($message) {
                 trigger_error($message);
                 // @codeCoverageIgnoreStart
                 return false;
                 // @codeCoverageIgnoreEnd
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
        // @codeCoverageIgnoreStart
        return;
        // @codeCoverageIgnoreEnd
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // unbind()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__unbind() : array
    {
        return [
            ['bound' => true,  'return' => true,  'expect' => ['isBound' => false]],
            ['bound' => true,  'return' => false, 'expect' => ['isBound' => true ]],
            ['bound' => false, 'return' => false, 'expect' => ['isBound' => false]],
            ['bound' => false, 'return' => true,  'expect' => ['isBound' => false]],
        ];
    }

    /**
     * @dataProvider prov__unbind
     */
    public function test__unbind(bool $bound, bool $return, array $expect) : void
    {
        $link = $this->createLdapLinkMock();

        $bind = $this->createBindingInstance($link, $bound);

        $link->expects($this->once())
             ->method('unbind')
             ->with()
             ->willReturn($return);

        $bind->unbind();
        $this->assertSame($expect['isBound'], $bind->isBound());
    }

    public static function prov__unbind__withTriggerError() : array
    {
        // @codeCoverageIgnoreStart
        return static::feedWithLdapTriggerError();
        // @codeCoverageIgnoreEnd
    }

    /**
     * @dataProvider prov__unbind__withTriggerError
     */
    public function test__unbind__withTriggerError(array $config, array $expect):  void
    {
        $this->examineBindingMethodWithTriggerError('unbind', 'unbind', [], $config, $expect);
        // @codeCoverageIgnoreStart
        return;
        // @codeCoverageIgnoreEnd
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
