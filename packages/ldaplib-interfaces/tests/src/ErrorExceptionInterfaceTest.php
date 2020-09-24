<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap;

use Korowai\Lib\Ldap\ErrorExceptionInterface;
use Korowai\Lib\Ldap\ExceptionInterface;
use Korowai\Testing\LdaplibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @coversNothing
 *
 * @internal
 */
final class ErrorExceptionInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class() extends \ErrorException implements ErrorExceptionInterface {
        };
    }

    public function testImplementsExceptionInterface(): void
    {
        $this->assertImplementsInterface(ExceptionInterface::class, ErrorExceptionInterface::class);
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ErrorExceptionInterface::class, $dummy);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
