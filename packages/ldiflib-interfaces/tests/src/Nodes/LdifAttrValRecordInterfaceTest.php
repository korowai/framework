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
use Korowai\Lib\Ldif\Nodes\HasAttrValSpecsInterface;
use Korowai\Lib\Ldif\Nodes\LdifAttrValRecordInterface;
use Korowai\Lib\Ldif\RecordInterface;
use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\Nodes\LdifAttrValRecordInterfaceTrait
 *
 * @internal
 */
final class LdifAttrValRecordInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class() implements LdifAttrValRecordInterface {
            use LdifAttrValRecordInterfaceTrait;
        };
    }

    public static function provExtendsInterface(): array
    {
        return [
            [HasAttrValSpecsInterface::class],
            [RecordInterface::class],
            [NodeInterface::class],
        ];
    }

    /**
     * @dataProvider provExtendsInterface
     */
    public function testExtendsInterface(string $extends): void
    {
        $this->assertImplementsInterface($extends, LdifAttrValRecordInterface::class);
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(LdifAttrValRecordInterface::class, $dummy);
    }
}

// vim: syntax=php sw=4 ts=4 et:
