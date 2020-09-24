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

use Korowai\Lib\Ldap\BindingInterface;
use Korowai\Lib\Ldap\Core\LdapLinkErrorHandler;
use Korowai\Lib\Ldap\Core\LdapLinkInterface;
use Korowai\Lib\Ldap\LdapException;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestFixture;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestSubject;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait BindingTestTrait
{
    abstract public function createBindingInstance(
        LdapLinkInterface $ldapLink,
        bool $bound = false
    ): BindingInterface;

    abstract public function examineLdapLinkErrorHandler(
        callable $function,
        LdapTriggerErrorTestSubject $subject,
        MockObject $link,
        LdapTriggerErrorTestFixture $fixture
    ): void;

    abstract public static function feedLdapLinkErrorHandler(): array;

    //
    //
    // TESTS
    //
    //

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // bind()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provBind(): array
    {
        return [
            // #0
            [
                'args' => [],
            ],
            // #1
            [
                'args' => ['dc=korowai,dc=org'],
            ],
            // #2
            [
                'args' => ['dc=korowai,dc=org', '$3cr3t'],
            ],
        ];
    }

    /**
     * @dataProvider provBind
     */
    public function testBind(array $args): void
    {
        $link = $this->createMock(LdapLinkInterface::class);

        $link->expects($this->once())
            ->method('bind')
            ->with(...$args)
            ->willReturn(true)
        ;

        $bind = $this->createBindingInstance($link);

        $bind->bind(...$args);
        $this->assertTrue($bind->isBound());
    }

    public static function provBindWithTriggerError(): array
    {
        // @codeCoverageIgnoreStart
        return static::feedLdapLinkErrorHandler();
        // @codeCoverageIgnoreEnd
    }

    /**
     * @dataProvider provBindWithTriggerError
     */
    public function testBindWithTriggerError(LdapTriggerErrorTestFixture $fixture): void
    {
        $this->examineBindingMethodWithTriggerError('bind', [], $fixture);
        // @codeCoverageIgnoreStart

        // @codeCoverageIgnoreEnd
    }

    public static function provBindWhenLdapLinkTriggersUnalteringLdapError(): array
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
     * @dataProvider provBindWhenLdapLinkTriggersUnalteringLdapError
     */
    public function testBindWhenLdapLinkTriggersUnalteringLdapError(int $errno, string $message): void
    {
        $link = $this->createMock(LdapLinkInterface::class);
        $bind = $this->createBindingInstance($link, true);

        $link->expects($this->once())
            ->method('isValid')
            ->willReturn(true)
        ;

        $link->expects($this->once())
            ->method('errno')
            ->willReturn($errno)
        ;

        $link->expects($this->once())
            ->method('getErrorHandler')
            ->willReturn($errorHandler = new LdapLinkErrorHandler($link))
        ;

        $link->expects($this->once())
            ->method('bind')
            ->will($this->returnCallback(function () use ($message) {
                 trigger_error($message);
                 // @codeCoverageIgnoreStart
                 return false;
                 // @codeCoverageIgnoreEnd
             }))
        ;

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

        // @codeCoverageIgnoreEnd
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // unbind()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provUnbind(): array
    {
        return [
            ['bound' => true,  'return' => true,  'expect' => ['isBound' => false]],
            ['bound' => true,  'return' => false, 'expect' => ['isBound' => true]],
            ['bound' => false, 'return' => false, 'expect' => ['isBound' => false]],
            ['bound' => false, 'return' => true,  'expect' => ['isBound' => false]],
        ];
    }

    /**
     * @dataProvider provUnbind
     */
    public function testUnbind(bool $bound, bool $return, array $expect): void
    {
        $link = $this->createMock(LdapLinkInterface::class);

        $bind = $this->createBindingInstance($link, $bound);

        $link->expects($this->once())
            ->method('unbind')
            ->willReturn($return)
        ;

        $bind->unbind();
        $this->assertSame($expect['isBound'], $bind->isBound());
    }

    public static function provUnbindWithTriggerError(): array
    {
        // @codeCoverageIgnoreStart
        return static::feedLdapLinkErrorHandler();
        // @codeCoverageIgnoreEnd
    }

    /**
     * @dataProvider provUnbindWithTriggerError
     */
    public function testUnbindWithTriggerError(LdapTriggerErrorTestFixture $fixture): void
    {
        $this->examineBindingMethodWithTriggerError('unbind', [], $fixture);
        // @codeCoverageIgnoreStart

        // @codeCoverageIgnoreEnd
    }

    abstract protected function createMock(string $class);

    private function examineBindingMethodWithTriggerError(
        string $method,
        array $args,
        LdapTriggerErrorTestFixture $fixture
    ): void {
        $link = $this->createMock(LdapLinkInterface::class);
        $bind = $this->createBindingInstance($link);

        $function = function () use ($bind, $method, $args): void {
            $bind->{$method}(...$args);
        };

        $subject = new LdapTriggerErrorTestSubject($link, $method);

        $this->examineLdapLinkErrorHandler($function, $subject, $link, $fixture);
        // @codeCoverageIgnoreStart
         // unreachable
        // @codeCoverageIgnoreEnd
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
