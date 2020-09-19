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

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;
use PHPUnit\Framework\MockObject\Stub\Stub;
use PHPUnit\Framework\MockObject\Stub\ReturnStub;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ExamineCallWithMockedLdapFunctionTrait
{
    abstract public static function makeArgsForLdapFunctionMock(array $resources, array $args) : array;
    abstract public function getLdapFunctionMock(string $name);
    abstract public static function assertSame($expected, $actual, string $message = '') : void;
    abstract public static function assertThat($value, Constraint $constraint, string $message ='') : void;
    abstract public static function once() : InvokedCount;
    abstract public static function returnValue($value) : ReturnStub;

    private function examineCallWithMockedLdapFunction(
        callable $function,
        array $resources,
        array &$args,
        $will,
        $expect,
        string $ldapFunction
    ) {
        if (!$will instanceof Stub) {
            $will = static::returnValue($will);
        }

        $ldapArgs = static::makeArgsForLdapFunctionMock($resources, $args);

        $this   ->getLdapFunctionMock($ldapFunction)
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->will($will);

        $actual = $function(...$args);

        if ($expect instanceof Constraint) {
            $this->assertThat($actual, $expect);
        } else {
            $this->assertSame($expect, $actual);
        }

        return $actual;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
