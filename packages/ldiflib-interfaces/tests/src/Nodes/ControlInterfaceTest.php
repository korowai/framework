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
use Korowai\Lib\Ldif\Nodes\ControlInterface;
use Korowai\Lib\Ldif\Nodes\ValueSpecInterface;
use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\Nodes\ControlInterfaceTrait
 *
 * @internal
 */
final class ControlInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class() implements ControlInterface {
            use ControlInterfaceTrait;
        };
    }

    public static function prov__extendsInterface(): array
    {
        return [
            [NodeInterface::class],
        ];
    }

    /**
     * @dataProvider prov__extendsInterface
     */
    public function testExtendsInterface(string $extends): void
    {
        $this->assertImplementsInterface($extends, ControlInterface::class);
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ControlInterface::class, $dummy);
    }

    public function testGetOid(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->oid = '';
        $this->assertSame($dummy->oid, $dummy->getOid());
    }

    public function testGetOidWithNull(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->oid = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->getOid();
    }

    public function testGetCriticality(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->criticality = false;
        $this->assertSame($dummy->criticality, $dummy->getCriticality());

        $dummy->criticality = null;
        $this->assertSame($dummy->criticality, $dummy->getCriticality());
    }

    public function testGetCriticalityWithTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->criticality = '';

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\bool::class.' or null');
        $dummy->getCriticality();
    }

    public function testGetValueSpec(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->valueSpec = $this->createStub(ValueSpecInterface::class);
        $this->assertSame($dummy->valueSpec, $dummy->getValueSpec());
    }

    public function testGetValueSpecWithTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->valueSpec = '';

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ValueSpecInterface::class);
        $dummy->getValueSpec();
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
