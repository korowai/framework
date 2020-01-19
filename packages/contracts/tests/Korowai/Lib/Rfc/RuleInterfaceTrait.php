<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Rfc;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait RuleInterfaceTrait
{
    public $toString = null;
    public $regexp = null;
    public $captures = null;
    public $errorCaptures = null;
    public $valueCaptures = null;
    public $findCapturedErrors = null;
    public $findCapturedValues = null;
    public $errorMessage = null;

    public function __toString() : string
    {
        return $this->toString;
    }

    public function regexp() : string
    {
        return $this->regexp;
    }

    public function captures() : array
    {
        return $this->captures;
    }

    public function errorCaptures() : array
    {
        return $this->errorCaptures;
    }

    public function valueCaptures() : array
    {
        return $this->valueCaptures;
    }

    public function findCapturedErrors(array $matches) : array
    {
        return $this->findCapturedErrors;
    }

    public function findCapturedValues(array $matches) : array
    {
        return $this->findCapturedValues;
    }

    public function getErrorMessage(string $errorKey = '') : string
    {
        return $this->errorMessage;
    }
}

// vim: syntax=php sw=4 ts=4 et:
