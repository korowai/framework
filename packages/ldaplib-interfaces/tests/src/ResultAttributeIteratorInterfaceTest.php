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

use Korowai\Lib\Ldap\ResultAttributeIteratorInterface;
use Korowai\Testing\LdaplibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldap\ResultAttributeIteratorInterfaceTrait
 *
 * @internal
 */
final class ResultAttributeIteratorInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class() implements ResultAttributeIteratorInterface {
            use ResultAttributeIteratorInterfaceTrait;
        };
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ResultAttributeIteratorInterface::class, $dummy);
    }
}

// vim: syntax=php sw=4 ts=4 et:
