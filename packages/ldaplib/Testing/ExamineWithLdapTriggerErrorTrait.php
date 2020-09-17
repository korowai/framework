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

use Korowai\Lib\Ldap\Exception\LdapException;
use Korowai\Lib\Ldap\Exception\ErrorException;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkErrorHandler;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ExamineWithLdapTriggerErrorTrait
{
    public function examineWithLdapTriggerError(
        callable $function,
        object $mock,
        string $mockMethod,
        array $mockArgs,
        LdapLinkInterface $ldapLinkMock,
        array $config,
        array $expect
    ) : void {
        $ldapLinkMock->expects($this->any())
                     ->method('getErrorHandler')
                     ->with()
                     ->willReturn(new LdapLinkErrorHandler($ldapLinkMock));

        $ldapLinkMock->expects($this->once())
                     ->method('isValid')
                     ->with()
                     ->willReturn($config['valid']);

        if ($config['valid']) {
            $ldapLinkMock->expects($this->once())
                         ->method('errno')
                         ->with()
                         ->willReturn($config['errno']);
        } else {
            $ldapLinkMock->expects($this->never())
                         ->method('errno');
        }

        $line = __line__ + 5;
        $mock->expects($this->once())
             ->method($mockMethod)
             ->with(...$mockArgs)
             ->willReturnCallback(function () use ($config) {
                trigger_error($config['message'], $config['severity']);
                return $config['return'];
             });

        $this->expectException($expect['exception']);
        $this->expectExceptionMessage($expect['message']);
        $this->expectExceptionCode($expect['code']);

        try {
            call_user_func($function);
        } catch (ErrorException $exception) {
            $this->assertSame(__file__.':'.$line, $exception->getFile().':'.$exception->getLine());
            $this->assertSame($expect['severity'], $exception->getSeverity());
            throw $exception;
        }
    }

    public static function feedWithLdapTriggerError() : array
    {
        return [
            // #0
            [
                'config' => [
                    'valid' => true,
                    'errno' => 123,
                    'return' => false,
                    'message' => 'error message',
                    'severity' => E_USER_WARNING,
                ],
                'expect' => [
                    'exception' => LdapException::class,
                    'message' => 'error message',
                    'code' => 123,
                    'severity' => E_USER_WARNING,
                ],
            ],
            // #1
            [
                'config' => [
                    'valid' => true,
                    'errno' => 0,
                    'return' => null,
                    'message' => 'error message',
                    'severity' => E_USER_WARNING,
                ],
                'expect' => [
                    'exception' => ErrorException::class,
                    'message' => 'error message',
                    'code' => 0,
                    'severity' => E_USER_WARNING,
                ],
            ],
            // #2
            [
                'config' => [
                    'valid' => true,
                    'errno' => false,
                    'return' => null,
                    'message' => 'error message',
                    'severity' => E_USER_WARNING,
                ],
                'expect' => [
                    'exception' => ErrorException::class,
                    'message' => 'error message',
                    'code' => 0,
                    'severity' => E_USER_WARNING,
                ]
            ],
            // #3
            [
                'config' => [
                    'valid' => false,
                    'errno' => null,
                    'return' => false,
                    'message' => 'error message',
                    'severity' => E_USER_WARNING,
                ],
                'expect' => [
                    'exception' => ErrorException::class,
                    'message' => 'error message',
                    'code' => 0,
                    'severity' => E_USER_WARNING,
                ],
            ],
        ];
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
