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
use Korowai\Lib\Ldap\Exception\ExceptionInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkErrorHandler;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ExamineLdapLinkErrorHandlerTrait
{
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

        $mock   = $subject->getMock();
        $method = $subject->getMethod();
        $args   = $subject->getArgs();

        $params = $fixture->getParams();
        $expect = $fixture->getExpect();

        $link->expects($this->any())
             ->method('getErrorHandler')
             ->with()
             ->willReturn(new LdapLinkErrorHandler($link));

        $link->expects($this->once())
             ->method('isValid')
             ->with()
             ->willReturn($params['valid']);

        if ($params['valid']) {
            $link->expects($this->once())
                 ->method('errno')
                 ->with()
                 ->willReturn($params['errno']);
        } else {
            $link->expects($this->never())
                 ->method('errno');
        }

        $line = __line__ + 5;
        $mock->expects($this->once())
             ->method($method)
             ->with(...$args)
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

    public static function feedLdapLinkErrorHandler() : array
    {
        return LdapTriggerErrorTestFixture::getFixtures();
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
