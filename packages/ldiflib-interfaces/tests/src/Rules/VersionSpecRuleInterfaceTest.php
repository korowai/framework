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
use Korowai\Lib\Ldif\Rules\VersionSpecRuleInterface;
use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\Rules\VersionSpecRuleInterfaceTrait
 *
 * @internal
 */
final class VersionSpecRuleInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class() implements VersionSpecRuleInterface {
            use VersionSpecRuleInterfaceTrait;
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
        $this->assertImplementsInterface($extends, VersionSpecRuleInterface::class);
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(VersionSpecRuleInterface::class, $dummy);
    }
}

// vim: syntax=php sw=4 ts=4 et:
