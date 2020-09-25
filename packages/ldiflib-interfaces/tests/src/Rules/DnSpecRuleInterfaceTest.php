<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Rules;

use Korowai\Lib\Ldif\RuleInterface;
use Korowai\Lib\Ldif\Rules\DnSpecRuleInterface;
use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\Rules\DnSpecRuleInterfaceTrait
 *
 * @internal
 */
final class DnSpecRuleInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class() implements DnSpecRuleInterface {
            use DnSpecRuleInterfaceTrait;
        };
    }

    public static function provExtendsInterface(): array
    {
        return [
            [RuleInterface::class],
        ];
    }

    /**
     * @dataProvider provExtendsInterface
     */
    public function testExtendsInterface(string $extends): void
    {
        $this->assertImplementsInterface($extends, DnSpecRuleInterface::class);
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(DnSpecRuleInterface::class, $dummy);
    }
}

// vim: syntax=php sw=4 ts=4 et:
