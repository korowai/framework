<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif;

use Korowai\Lib\Ldif\RecordInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class RecordInterfaceTest extends TestCase
{
    public function test__dummyImplementation()
    {
        $dummy = new class implements RecordInterface {
            use RecordInterfaceTrait;
        };
        $this->assertImplementsInterface(RecordInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'dn'        => 'getDn',
        ];
        $this->assertObjectPropertyGetters($expect, RecordInterface::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
