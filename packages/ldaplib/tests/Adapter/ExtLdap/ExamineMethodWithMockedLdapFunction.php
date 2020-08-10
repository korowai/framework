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

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\MockObject\Stub\Stub;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ExamineMethodWithMockedLdapFunction
{
    abstract public static function makeArgsForLdapFunctionMock(array $resources, array $args) : array;
    abstract public function getLdapFunctionMock(string $name);

    private function examineMethodWithMockedLdapFunction(
        object $object,
        string $method,
        array $resources,
        array &$args,
        $will,
        $expect,
        $function = null
    ) {
        if (!$will instanceof Stub) {
            $will = static::returnValue($will);
        }

        if ($function === null) {
            $function = "ldap_$method";
        }

        $ldapArgs = static::makeArgsForLdapFunctionMock($resources, $args);

        $this   ->getLdapFunctionMock($function)
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->will($will);

        $actual = $object->{$method}(...$args);

        if ($expect instanceof Constraint) {
            $this->assertThat($actual, $expect);
        } else {
            $this->assertSame($expect, $actual);
        }

        return $actual;
    }
}

// vim: syntax=php sw=4 ts=4 et:
