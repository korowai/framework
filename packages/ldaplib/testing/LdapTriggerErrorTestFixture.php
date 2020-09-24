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

use Korowai\Lib\Ldap\ErrorException;
use Korowai\Lib\Ldap\LdapException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapTriggerErrorTestFixture
{
    /**
     * @var array
     * @psalm-readonly
     */
    private $params;

    /**
     * @var array
     * @psalm-readonly
     */
    private $expect;

    private function __construct(array $params, array $expect)
    {
        $this->params = $params;
        $this->expect = $expect;
    }

    /**
     * Returns array of fixture instances suitable to be returned from within a dataProvider.
     */
    public static function getFixtures(): array
    {
        return [
            // #0
            new self([
                'valid' => true,
                'errno' => 123,
                'return' => false,
                'message' => 'error message',
                'severity' => E_USER_WARNING,
            ], [
                'exception' => LdapException::class,
                'message' => 'error message',
                'code' => 123,
                'severity' => E_USER_WARNING,
            ]),
            // #1
            new self([
                'valid' => true,
                'errno' => 0,
                'return' => null,
                'message' => 'error message',
                'severity' => E_USER_WARNING,
            ], [
                'exception' => ErrorException::class,
                'message' => 'error message',
                'code' => 0,
                'severity' => E_USER_WARNING,
            ]),
            // #2
            new self([
                'valid' => true,
                'errno' => false,
                'return' => null,
                'message' => 'error message',
                'severity' => E_USER_WARNING,
            ], [
                'exception' => ErrorException::class,
                'message' => 'error message',
                'code' => 0,
                'severity' => E_USER_WARNING,
            ]),
            // #3
            new self([
                'valid' => false,
                'errno' => null,
                'return' => false,
                'message' => 'error message',
                'severity' => E_USER_WARNING,
            ], [
                'exception' => ErrorException::class,
                'message' => 'error message',
                'code' => 0,
                'severity' => E_USER_WARNING,
            ]),
        ];
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function getExpect(): array
    {
        return $this->expect;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
