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

use Korowai\Lib\Ldif\Rules\AbstractDnSpecRule;
use Korowai\Lib\Ldif\Rules\AbstractNameSpecRule;
use Korowai\Testing\Ldiflib\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Rules\AbstractDnSpecRule
 *
 * @internal
 */
final class AbstractDnSpecRuleTest extends TestCase
{
    use ExtendsClassTrait;

    public function testExtendsAbstractNameSpecRule(): void
    {
        $this->assertExtendsClass(AbstractNameSpecRule::class, AbstractDnSpecRule::class);
    }

    public function testPrefix(): void
    {
        $rule = $this->getMockBuilder(AbstractDnSpecRule::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass()
        ;
        $this->assertSame('dn', $rule->prefix());
    }
}

// vim: syntax=php sw=4 ts=4 et:
