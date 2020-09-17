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
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapTriggerErrorTestSubject
{
    /**
     * @var MockObject
     *
     * @psalm-readonly
     */
    private $mock;

    /**
     * @var string
     *
     * @psalm-readonly
     */
    private $method;

    /**
     * @var array
     *
     * @psalm-readonly
     */
    private $args;

    /**
     * @param MockObject $mock
     * @param string $method
     * @param array $args
     */
    public function __construct(MockObject $mock, string $method, array $args)
    {
        $this->mock = $mock;
        $this->method = $method;
        $this->args = $args;
    }

    public function getMock() : MockObject
    {
        return $this->mock;
    }

    public function getMethod() : string
    {
        return $this->method;
    }

    public function getArgs() : array
    {
        return $this->args;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
