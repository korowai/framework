<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Exception;

use Korowai\Lib\Ldap\Exception\ExceptionInterface;
use Korowai\Lib\Ldap\Exception\LdapExceptionInterface;

use Korowai\Testing\LdaplibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @coversNothing
 */
final class LdapExceptionInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class extends \Exception implements LdapExceptionInterface {
        };
    }

    public function test__implements__ExceptionInterface() : void
    {
        $this->assertImplementsInterface(ExceptionInterface::class, LdapExceptionInterface::class);
    }

    public function test__dummyImplementation() : void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(LdapExceptionInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap() : void
    {
        $expect = [];
        $this->assertObjectPropertyGetters($expect, LdapExceptionInterface::class);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119: