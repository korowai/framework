<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Nodes;

use Korowai\Lib\Ldif\NodeInterface;
use Korowai\Lib\Ldif\Nodes\LdifChangeRecordInterface;
use Korowai\Lib\Ldif\Nodes\LdifDeleteRecordInterface;
use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\Nodes\LdifDeleteRecordInterfaceTrait
 *
 * @internal
 */
final class LdifDeleteRecordInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class() implements LdifDeleteRecordInterface {
            use LdifDeleteRecordInterfaceTrait;
        };
    }

    public static function provExtendsInterface(): array
    {
        return [
            [LdifChangeRecordInterface::class],
            [NodeInterface::class],
        ];
    }

    /**
     * @dataProvider provExtendsInterface
     */
    public function testExtendsInterface(string $extends): void
    {
        $this->assertImplementsInterface($extends, LdifDeleteRecordInterface::class);
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(LdifDeleteRecordInterface::class, $dummy);
    }
}

// vim: syntax=php sw=4 ts=4 et:
