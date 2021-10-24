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
trait StaticRuleSetInterfaceTrait
{
    public static $rules;
    public static $regexp;
    public static $captures;
    public static $errorCaptures;
    public static $valueCaptures;
    public static $findCapturedErrors;
    public static $findCapturedValues;
    public static $errorMessage;

    public static function rules(): array
    {
        return self::$rules;
    }

    public static function regexp(string $ruleName): string
    {
        return self::$regexp;
    }

    public static function captures(string $ruleName): array
    {
        return self::$captures;
    }

    public static function errorCaptures(string $ruleName): array
    {
        return self::$errorCaptures;
    }

    public static function valueCaptures(string $ruleName): array
    {
        return self::$valueCaptures;
    }

    public static function findCapturedErrors(string $ruleName, array $matches): array
    {
        return self::$findCapturedErrors;
    }

    public static function findCapturedValues(string $ruleName, array $matches): array
    {
        return self::$findCapturedValues;
    }

    public static function getErrorMessage(string $errorKey, string $ruleName = null): string
    {
        return self::$errorMessage;
    }
}

// vim: syntax=php sw=4 ts=4 et:
