<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Ldaplib;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\AnyInvokedCount;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;
use Korowai\Lib\Ldap\Exception\ExceptionInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkErrorHandler;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ExamineLdapLinkErrorHandlerTrait
{
    abstract public static function any() : AnyInvokedCount;
    abstract public static function once() : InvokedCount;
    abstract public static function never() : InvokedCount;
    abstract public function expectException(string $exception) : void;
    abstract public function expectExceptionMessage(string $message) : void;
    abstract public function expectExceptionCode($code) : void;
    abstract public static function assertSame($expected, $actual, string $message = '') : void;

    /**
     * Tests that when $function gets invoked appropriate exception is thrown.
     *
     * @param  calable $function
     * @param  LdapTriggerErrorTestSubject $subject
     * @param  MockObject $link
     * @param  LdapTriggerErrorTestFixture $fixture
     */
    public function examineLdapLinkErrorHandler(
        callable $function,
        LdapTriggerErrorTestSubject $subject,
        MockObject $link,
        LdapTriggerErrorTestFixture $fixture
    ) : void {
        if (!$link instanceof LdapLinkInterface) {
            $message = 'Argument 3 to '.__class__.'::examineLdapLinkErrorHandler() '.
                       'must be an instance of'.LdapLinkInterface::class;
            throw new \InvalidArgumentException($message);
        }

        $mock   = $subject->mock();
        $method = $subject->method();
        $with   = $subject->with();

        $params = $fixture->getParams();
        $expect = $fixture->getExpect();

        $link->expects($this->any())
             ->method('getErrorHandler')
             ->willReturn(new LdapLinkErrorHandler($link));

        $link->expects($this->once())
             ->method('isValid')
             ->willReturn($params['valid']);

        if ($params['valid']) {
            $link->expects($this->once())
                 ->method('errno')
                 ->willReturn($params['errno']);
        } else {
            $link->expects($this->never())
                 ->method('errno');
        }

        $line = __line__ + 5;
        $mock->expects($this->once())
             ->method($method)
             ->with(...$with)
             ->willReturnCallback(function () use ($params) {
                 trigger_error($params['message'], $params['severity']);
                 return $params['return'];
             });

        $this->expectException($expect['exception']);
        $this->expectExceptionMessage($expect['message']);
        $this->expectExceptionCode($expect['code']);

        try {
            call_user_func($function);
        } catch (ExceptionInterface $exception) {
            $this->assertSame(__file__.':'.$line, $exception->getFile().':'.$exception->getLine());
            $this->assertSame($expect['severity'], $exception->getSeverity());
            throw $exception;
        }
    }

    /**
     * Returns an array of instances of LdapTriggerErrorTestFixture.
     *
     * @return LdapTriggerErrorTestFixture[]
     * @psalm-return list<LdapTriggerErrorTestFixture>
     */
    public static function feedLdapLinkErrorHandler() : array
    {
        return array_map(function (LdapTriggerErrorTestFixture $fixture) : array {
            return [ $fixture ];
        }, LdapTriggerErrorTestFixture::getFixtures());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
