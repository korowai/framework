<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Basic;

use Korowai\Lib\Basic\SingletonInterface;

use Korowai\Testing\BasiclibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Basic\SingletonInterfaceTrait
 */
final class SingletonInterfaceTest extends TestCase
{
    public static function createDummyClass()
    {
        return get_class(new class implements SingletonInterface {
            use SingletonInterfaceTrait;
        });
    }

    public function test__dummyImplementation() : void
    {
        $dummy = $this->createDummyClass();
        $this->assertImplementsInterface(SingletonInterface::class, $dummy);
    }

    public function test__getInstance() : void
    {
        $dummy = $this->createDummyClass();
        $dummy::$instance = 'instance';
        $this->assertSame($dummy::$instance, $dummy::getInstance());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
