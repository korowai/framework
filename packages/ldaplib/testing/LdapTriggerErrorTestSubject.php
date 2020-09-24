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
    private $with;

    /**
     * Initializes the object.
     */
    public function __construct(MockObject $mock, string $method, array $with = [])
    {
        $this->mock = $mock;
        $this->method = $method;
        $this->with = $with;
    }

    public function mock(): MockObject
    {
        return $this->mock;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function with(): array
    {
        return $this->with;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
