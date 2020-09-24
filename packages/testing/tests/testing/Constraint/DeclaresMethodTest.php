<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\Constraint;

use Korowai\Testing\Constraint\DeclaresMethod;
use Korowai\Testing\TestCase;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Constraint\DeclaresMethod
 *
 * @internal
 */
final class DeclaresMethodTest extends TestCase
{
    public function testExtendsConstraint(): void
    {
        $this->assertExtendsClass(Constraint::class, DeclaresMethod::class);
    }

    public function testConstruct(): void
    {
        $this->assertInstanceOf(DeclaresMethod::class, new DeclaresMethod('foo'));
    }

    public function testToString(): void
    {
        $this->assertSame('declares method foo()', (new DeclaresMethod('foo'))->toString());
    }

    public function testEvaluateOnObject(): void
    {
        $constraint = new DeclaresMethod('testEvaluateOnObject');
        $this->assertTrue($constraint->evaluate($this, '', true));
        $this->assertNull($constraint->evaluate($this, '', false));
        $this->assertThat($this, $constraint);
    }

    public function testEvaluateOnObjectWithFailure(): void
    {
        $constraint = new DeclaresMethod('assertThat');

        $this->assertFalse($constraint->evaluate($this, '', true));

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage('Failed asserting that class '.__CLASS__.' declares method assertThat().');

        $this->assertThat($this, $constraint);
    }

    public function testEvaluateOnClass(): void
    {
        $constraint = new DeclaresMethod('testEvaluateOnClass');
        $this->assertTrue($constraint->evaluate($this, '', true));
        $this->assertNull($constraint->evaluate($this, '', false));
        $this->assertThat(self::class, $constraint);
    }

    public function testEvaluateOnClassWithFailure(): void
    {
        $constraint = new DeclaresMethod('assertThat');

        $this->assertFalse($constraint->evaluate($this, '', true));

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage('Failed asserting that class '.__CLASS__.' declares method assertThat().');

        $this->assertThat(self::class, $constraint);
    }

    public function testEvaluateOnString(): void
    {
        $constraint = new DeclaresMethod('testEvaluateOnString');
        $this->assertFalse($constraint->evaluate('@##@##', '', true));
    }

    public function testEvaluateOnStringWithFailure(): void
    {
        $constraint = new DeclaresMethod('testEvaluateOnStringWithFailure');

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage('Failed asserting that string declares method testEvaluateOnStringWithFailure().');
        $this->assertThat('@##@##', $constraint);
    }

    public function testEvaluateOnArray(): void
    {
        $constraint = new DeclaresMethod('testEvaluateOnArray');
        $this->assertFalse($constraint->evaluate([], '', true));
    }

    public function testEvaluateOnArrayWithFailure(): void
    {
        $constraint = new DeclaresMethod('testEvaluateOnArrayWithFailure');

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage('Failed asserting that array declares method testEvaluateOnArrayWithFailure().');
        $this->assertThat([], $constraint);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
