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

use Korowai\Lib\Ldap\Exception\LdapException;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ExamineMethodWithBackendTriggerError
{
    private function examineMethodWithBackendTriggerError(
        object $object,
        string $method,
        object $backendMock,
        string $backendMethod,
        object $ldapMock,
        array $args,
        array $config,
        array $expect
    ) : void {

        $ldapMock->expects($this->once())
                 ->method('isValid')
                 ->with()
                 ->willReturn($config['valid']);

        if ($config['valid']) {
            $ldapMock->expects($this->once())
                     ->method('errno')
                     ->with()
                     ->willReturn($config['errno']);
        } else {
            $ldapMock->expects($this->never())
                     ->method('errno');
        }

        $line = __line__ + 5;
        $backendMock->expects($this->once())
                 ->method($backendMethod)
                 ->with(...$args)
                 ->willReturnCallback(function () use ($config) {
                     trigger_error($config['message'], $config['severity']);
                     return $config['return'];
                 });

        $this->expectException($expect['exception']);
        $this->expectExceptionMessage($expect['message']);
        $this->expectExceptionCode($expect['code']);

        try {
            call_user_func_array([$object, $method], $args);
        } catch (\ErrorException $e) {
            $this->assertSame(__file__, $e->getFile());
            $this->assertSame($line, $e->getLine());
            $this->assertSame($expect['severity'], $e->getSeverity());
            throw $e;
        }
    }

    public static function feedMethodWithBackendTriggerError() : array
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
                    'exception' => \ErrorException::class,
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
                    'exception' => \ErrorException::class,
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
                    'exception' => \ErrorException::class,
                    'message' => 'error message',
                    'code' => 0,
                    'severity' => E_USER_WARNING,
                ],
            ],
        ];
    }
}

// vim: syntax=php sw=4 ts=4 et:
