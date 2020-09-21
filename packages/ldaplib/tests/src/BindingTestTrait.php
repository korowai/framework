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

use PHPUnit\Framework\MockObject\MockObject;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestFixture;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestSubject;

use Korowai\Lib\Ldap\BindingTrait;
use Korowai\Lib\Ldap\Core\LdapLinkInterface;
use Korowai\Lib\Ldap\Core\LdapLinkErrorHandler;
use Korowai\Lib\Ldap\BindingInterface;
use Korowai\Lib\Ldap\LdapException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait BindingTestTrait
{
    abstract public function createBindingInstance(
        LdapLinkInterface $ldapLink,
        bool $bound = false
    ) : BindingInterface;

    abstract public function examineLdapLinkErrorHandler(
        callable $function,
        LdapTriggerErrorTestSubject $subject,
        MockObject $link,
        LdapTriggerErrorTestFixture $fixture
    ) : void;

    abstract public static function feedLdapLinkErrorHandler() : array;

    abstract protected function createMock(string $class);

    private function examineBindingMethodWithTriggerError(
        string $method,
        array $args,
        LdapTriggerErrorTestFixture $fixture
    ) : void {
        $link = $this->createMock(LdapLinkInterface::class);
        $bind = $this->createBindingInstance($link);

        $function = function () use ($bind, $method, $args) : void {
            $bind->$method(...$args);
        };

        $subject = new LdapTriggerErrorTestSubject($link, $method);

        $this->examineLdapLinkErrorHandler($function, $subject, $link, $fixture);
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
        $link = $this->createMock(LdapLinkInterface::class);

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
        return static::feedLdapLinkErrorHandler();
        // @codeCoverageIgnoreEnd
    }

    /**
     * @dataProvider prov__bind__withTriggerError
     */
    public function test__bind__withTriggerError(LdapTriggerErrorTestFixture $fixture) : void
    {
        $this->examineBindingMethodWithTriggerError('bind', [], $fixture);
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
        $link = $this->createMock(LdapLinkInterface::class);
        $bind = $this->createBindingInstance($link, true);

        $link->expects($this->once())
             ->method('isValid')
             ->willReturn(true);

        $link->expects($this->once())
             ->method('errno')
             ->willReturn($errno);

        $link->expects($this->once())
             ->method('getErrorHandler')
             ->willReturn($errorHandler = new LdapLinkErrorHandler($link));

        $link->expects($this->once())
             ->method('bind')
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
        $link = $this->createMock(LdapLinkInterface::class);

        $bind = $this->createBindingInstance($link, $bound);

        $link->expects($this->once())
             ->method('unbind')
             ->willReturn($return);

        $bind->unbind();
        $this->assertSame($expect['isBound'], $bind->isBound());
    }

    public static function prov__unbind__withTriggerError() : array
    {
        // @codeCoverageIgnoreStart
        return static::feedLdapLinkErrorHandler();
        // @codeCoverageIgnoreEnd
    }

    /**
     * @dataProvider prov__unbind__withTriggerError
     */
    public function test__unbind__withTriggerError(LdapTriggerErrorTestFixture $fixture):  void
    {
        $this->examineBindingMethodWithTriggerError('unbind', [], $fixture);
        // @codeCoverageIgnoreStart
        return;
        // @codeCoverageIgnoreEnd
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
