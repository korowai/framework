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

use Korowai\Lib\Ldap\Adapter\ExtLdap\Binding;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperInterface;
use Korowai\Lib\Ldap\Adapter\BindingInterface;
use Korowai\Lib\Ldap\Exception\LdapException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class BindingTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;
    use GetLdapFunctionMock;

    public function createLdapLinkMock($valid, $unbind = true)
    {
        $link = $this->createMock(LdapLinkInterface::class);
        if ($valid === true || $valid === false) {
            $link->method('isValid')->willReturn($valid);
        }
        if ($unbind === true || $unbind === false) {
            $link->method('unbind')->willReturn($unbind);
        }
        return $link;
    }

    //
    //
    // TESTS
    //
    //

    public function test__implements__BindingInterface() : void
    {
        $this->assertImplementsInterface(BindingInterface::class, Binding::class);
    }

    public function test__implements__LdapLinkWrapperInterface() : void
    {
        $this->assertImplementsInterface(LdapLinkWrapperInterface::class, Binding::class);
    }

    public function prov__construct() : array
    {
        $link = $this->createMock(LdapLinkInterface::class);
        return [
            // #0
            [
                'args' => [$link],
                'expect' => [
                    'ldapLink' => $link,
                    'isBound' => false
                ],
            ],
            // #1
            [
                'args' => [$link, false],
                'expect' => [
                    'ldapLink' => $link,
                    'isBound' => false
                ],
            ],
            // #2
            [
                'args' => [$link, true],
                'expect' => [
                    'ldapLink' => $link,
                    'isBound' => true
                ],
            ],
        ];
    }

    /**
     * @dataProvider prov__construct
     */
    public function test__construct(array $args, array $expect) : void
    {
        $bind = new Binding(...$args);
        $this->assertHasPropertiesSameAs($expect, $bind);
    }

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
        $link = $this->createMock(LdapLinkInterface::class);

        $link->expects($this->once())
             ->method('bind')
             ->with(...$args)
             ->willReturn(true);

        $bind = new Binding($link);
        $bind->bind(...$args);
        $this->assertTrue($bind->isBound());
    }

    public function test__bind__whenLdapLinkTriggersLdapError() : void
    {
        $link = $this->createMock(LdapLinkInterface::class);
        $bind = new Binding($link, true);

        $link->expects($this->once())
             ->method('isValid')
             ->with()
             ->willReturn(true);

        $link->expects($this->once())
             ->method('errno')
             ->with()
             ->willReturn(123);

        $link->expects($this->once())
             ->method('bind')
             ->with()
             ->will($this->returnCallback(function () {
                 trigger_error("An LDAP error");
                 return false;
             }));

        $this->expectException(LdapException::class);
        $this->expectExceptionMessage("An LDAP error");
        $this->expectExceptionCode(123);

        try {
            $bind->bind();
        } catch (\Throwable $throwable) {
            $this->assertFalse($bind->isBound());
            throw $throwable;
        }
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
        $link = $this->createMock(LdapLinkInterface::class);
        $bind = new Binding($link, true);

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

    public function test__bind__whenLdapLinkTriggersNonLdapError() : void
    {
        $link = $this->createMock(LdapLinkInterface::class);
        $bind = new Binding($link, true);

        $link->expects($this->once())
             ->method('isValid')
             ->with()
             ->willReturn(true);

        $link->expects($this->once())
             ->method('errno')
             ->with()
             ->willReturn(0);

        $link->expects($this->once())
             ->method('bind')
             ->with()
             ->will($this->returnCallback(function () {
                 trigger_error("Non LDAP error");
                 return false;
             }));

        $this->expectException(\ErrorException::class);
        $this->expectExceptionMessage("Non LDAP error");
        $this->expectExceptionCode(0);

        try {
            $bind->bind();
        } catch (\Throwable $throwable) {
            $this->assertTrue($bind->isBound());
            throw $throwable;
        }
    }

//    public function test__getOption__Uninitialized() : void
//    {
//        $link = $this->createLdapLinkMock(false);
//        $link->expects($this->never())
//             ->method('get_option');
//
//        $bind = new Binding($link);
//
//        $this->expectException(LdapException::class);
//        $this->expectExceptionCode(-1);
//        $this->expectExceptionMessage('Uninitialized LDAP link');
//
//        $bind->getOption(0);
//    }
//
//    /**
//     * @runInSeparateProcess
//     */
//    public function test__getOption__Failure() : void
//    {
//        $link = $this->createLdapLinkMock(true);
//        $link->expects($this->once())
//             ->method('get_option')
//             ->with(0)
//             ->willReturn(false);
//        $link->expects($this->once())
//             ->method('errno')
//             ->with()
//             ->willReturn(2);
//
//        $this   ->getLdapFunctionMock('ldap_err2str')
//                ->expects($this->once())
//                ->with(2)
//                ->willReturn('Error message');
//
//        $bind = new Binding($link);
//
//        $this->expectException(LdapException::class);
//        $this->expectExceptionCode(2);
//        $this->expectExceptionMessage('Error message');
//
//        $bind->getOption(0);
//    }
//
//    public function test__getOption() : void
//    {
//        $link = $this->getMockBuilder(LdapLinkInterface::class)
//                     ->setMethods(['get_option', 'unbind', 'isValid'])
//                     ->getMockForAbstractClass();
//
//        $callback = function ($option, &$retval) {
//            $retval = 'option zero';
//            return true;
//        };
//
//        $link->expects($this->once())
//             ->method('get_option')
//             ->with(0, $this->anything())
//             ->will($this->returnCallback($callback));
//        $link->expects($this->never())
//             ->method('unbind')
//             ->with()
//             ->willReturn(true);
//        $link->expects($this->once())
//             ->method('isValid')
//             ->with()
//             ->willReturn(true);
//
//        $bind = new Binding($link);
//        $this->assertSame('option zero', $bind->getOption(0));
//    }
//
//    public function test__setOption__whenUninitialized() : void
//    {
//        $link = $this->createLdapLinkMock(false);
//        $link->expects($this->never())
//             ->method('set_option');
//
//        $bind = new Binding($link);
//
//        $this->expectException(LdapException::class);
//        $this->expectExceptionCode(-1);
//        $this->expectExceptionMessage('Uninitialized LDAP link');
//
//        $bind->setOption(0, 'option zero');
//    }
//
//    /**
//     * @runInSeparateProcess
//     */
//    public function test__setOption__Failure() : void
//    {
//        $link = $this->createLdapLinkMock(true);
//        $link->expects($this->once())
//             ->method('set_option')
//             ->with(0, 'option zero')
//             ->willReturn(false);
//        $link->expects($this->once())->method('errno')->with()->willReturn(2);
//
//        $this->getLdapFunctionMock('ldap_err2str')
//             ->expects($this->once())
//             ->with(2)
//             ->willReturn('Error message');
//
//        $bind = new Binding($link);
//
//        $this->expectException(LdapException::class);
//        $this->expectExceptionCode(2);
//        $this->expectExceptionMessage('Error message');
//
//        $bind->setOption(0, 'option zero');
//    }
//
//    public function test__setOption() : void
//    {
//        $link = $this->createLdapLinkMock(true);
//        $link->expects($this->once())
//             ->method('set_option')
//             ->with(0, 'new value')
//             ->willReturn(true);
//
//        $bind = new Binding($link);
//        $bind->setOption(0, 'new value');
//    }
//
    public function test__unbind() : void
    {
        //$link = $this->createLdapLinkMock(true, true);
        $link = $this->createMock(LdapLinkInterface::class);

        $bind = new Binding($link, true);

        $link->expects($this->once())
             ->method('unbind')
             ->with()
             ->willReturn(true);

        $bind->unbind();
        $this->assertFalse($bind->isBound());
    }

    public function prov__unbind__whenLdapLinkTriggersError() : array
    {
        return [
            // #0
            [
                'class' => LdapException::class,
                'errno' => 123,
                'message' => 'An LDAP error',
            ],
            // #1
            [
                'class' => \ErrorException::class,
                'errno' => 0,
                'message' => 'A non-LDAP error',
            ],
        ];
    }

    /**
     * @dataProvider prov__unbind__whenLdapLinkTriggersError
     */
    public function test__unbind__whenLdapLinkTriggersError(string $class, int $errno, string $message) : void
    {
        $link = $this->createMock(LdapLinkInterface::class);

        $bind = new Binding($link, true);

        $link->expects($this->once())
             ->method('isValid')
             ->with()
             ->willReturn(true);
        $link->expects($this->once())
             ->method('errno')
             ->with()
             ->willReturn($errno);
        $link->expects($this->once())
             ->method('unbind')
             ->with()
             ->will($this->returnCallback(function () use ($message) {
                 trigger_error($message);
                 return false;
             }));

        $this->expectException($class);
        $this->expectExceptionMessage($message);
        $this->expectExceptionCode($errno);

        try {
            $bind->unbind();
        } catch (\Throwable $throwable) {
            $this->assertTrue($bind->isBound());
            throw $throwable;
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
