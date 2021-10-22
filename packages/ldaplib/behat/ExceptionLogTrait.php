<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Behat;

trait ExceptionLogTrait
{
    protected $exceptions = [];

    protected function clearExceptions(): void
    {
        $this->exceptions = [];
    }

    protected function appendException($exception): void
    {
        $this->exceptions[] = $exception;
    }

    protected function lastException()
    {
        if (count($this->exceptions) < 1) {
            return null;
        }

        return $this->exceptions[count($this->exceptions) - 1];
    }
}

// vim: syntax=php sw=4 ts=4 et:
