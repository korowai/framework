<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Rfc\Traits;

use Korowai\Lib\Rfc\RuleInterface;
use Korowai\Lib\Rfc\Traits\DecoratesRuleInterface;
use Korowai\Lib\Rfc\Traits\ExposesRuleInterface;
use Korowai\Testing\TestCase;
use Tailors\PHPUnit\UsesTraitTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Rfc\Traits\DecoratesRuleInterface
 *
 * @internal
 */
final class DecoratesRuleInterfaceTest extends TestCase
{
    use UsesTraitTrait;

    public function testUsesExposesRuleInterface(): void
    {
        $this->assertUsesTrait(ExposesRuleInterface::class, DecoratesRuleInterface::class);
    }

    public function testRfcRule(): void
    {
        $rule = $this->getMockBuilder(RuleInterface::class)
            ->getMockForAbstractClass()
        ;

        $obj = new class() implements RuleInterface {
            use DecoratesRuleInterface;
        };

        $this->assertNull($obj->getRfcRule());
        $this->assertSame($obj, $obj->setRfcRule($rule));
        $this->assertSame($rule, $obj->getRfcRule());
        $this->assertSame($obj, $obj->setRfcRule(null));
        $this->assertNull($obj->getRfcRule());
    }
}

// vim: syntax=php sw=4 ts=4 et:
