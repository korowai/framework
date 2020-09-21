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

use Korowai\Lib\Ldap\Exception\ExceptionInterface;

use Korowai\Testing\LdaplibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @coversNothing
 */
final class ExceptionInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class extends \Exception implements ExceptionInterface {
        };
    }

    public function test__implements__Throwable() : void
    {
        $this->assertImplementsInterface(\Throwable::class, ExceptionInterface::class);
    }

    public function test__dummyImplementation() : void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ExceptionInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap() : void
    {
        $expect = [];
        $this->assertObjectPropertyGetters($expect, ExceptionInterface::class);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
