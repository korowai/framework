<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Rfc;

use Korowai\Lib\Rfc\StaticRuleSetInterface;
use Korowai\Testing\RfclibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Rfc\StaticRuleSetInterfaceTrait
 *
 * @internal
 */
final class StaticRuleSetInterfaceTest extends TestCase
{
    public static function createDummyClass()
    {
        return get_class(new class() implements StaticRuleSetInterface {
            use StaticRuleSetInterfaceTrait;
        });
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyClass();
        $this->assertImplementsInterface(StaticRuleSetInterface::class, $dummy);
    }

    public function testRules(): void
    {
        $dummy = $this->createDummyClass();

        $dummy::$rules = [];
        $this->assertSame($dummy::$rules, $dummy::rules());
    }

    public function testRulesWithRetTypeError(): void
    {
        $dummy = $this->createDummyClass();
        $dummy::$rules = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy::rules();
    }

    public function testRegexp(): void
    {
        $dummy = $this->createDummyClass();

        $dummy::$regexp = '';
        $this->assertSame($dummy::$regexp, $dummy::regexp(''));
    }

    public function testRegexpWithArgTypeError(): void
    {
        $dummy = $this->createDummyClass();
        $dummy::$regexp = '';

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy::regexp(null);
    }

    public function testRegexpWithRetTypeError(): void
    {
        $dummy = $this->createDummyClass();
        $dummy::$regexp = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy::regexp('');
    }

    public function testCaptures(): void
    {
        $dummy = $this->createDummyClass();

        $dummy::$captures = [];
        $this->assertSame($dummy::$captures, $dummy::captures(''));
    }

    public function testCapturesWithArgTypeError(): void
    {
        $dummy = $this->createDummyClass();
        $dummy::$captures = [];

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy::captures(null);
    }

    public function testCapturesWithRetTypeError(): void
    {
        $dummy = $this->createDummyClass();
        $dummy::$captures = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy::captures('');
    }

    public function testErrorCaptures(): void
    {
        $dummy = $this->createDummyClass();

        $dummy::$errorCaptures = [];
        $this->assertSame($dummy::$errorCaptures, $dummy::errorCaptures(''));
    }

    public function testErrorCapturesWithArgTypeError(): void
    {
        $dummy = $this->createDummyClass();
        $dummy::$errorCaptures = [];

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy::errorCaptures(null);
    }

    public function testErrorCapturesWithRetTypeError(): void
    {
        $dummy = $this->createDummyClass();
        $dummy::$errorCaptures = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy::errorCaptures('');
    }

    public function testValueCaptures(): void
    {
        $dummy = $this->createDummyClass();

        $dummy::$valueCaptures = [];
        $this->assertSame($dummy::$valueCaptures, $dummy::valueCaptures(''));
    }

    public function testValueCapturesWithArgTypeError(): void
    {
        $dummy = $this->createDummyClass();
        $dummy::$valueCaptures = [];

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy::valueCaptures(null);
    }

    public function testValueCapturesWithRetTypeError(): void
    {
        $dummy = $this->createDummyClass();
        $dummy::$valueCaptures = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy::valueCaptures('');
    }

    public function testFindCapturedErrors(): void
    {
        $dummy = $this->createDummyClass();

        $dummy::$findCapturedErrors = [];
        $this->assertSame($dummy::$findCapturedErrors, $dummy::findCapturedErrors('', []));
    }

    public static function provFindCapturedErrorsWithArgTypeError(): array
    {
        return [
            [[null, []], \string::class],
            [['', null], 'array'],
        ];
    }

    /**
     * @dataProvider provFindCapturedErrorsWithArgTypeError
     */
    public function testFindCapturedErrorsWithArgTypeError(array $args, string $message): void
    {
        $dummy = $this->createDummyClass();
        $dummy::$findCapturedErrors = [];

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);
        $dummy::findCapturedErrors(...$args);
    }

    public function testFindCapturedErrorsWithRetTypeError(): void
    {
        $dummy = $this->createDummyClass();
        $dummy::$findCapturedErrors = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy::findCapturedErrors('', []);
    }

    public function testFindCapturedValues(): void
    {
        $dummy = $this->createDummyClass();

        $dummy::$findCapturedValues = [];
        $this->assertSame($dummy::$findCapturedValues, $dummy::findCapturedValues('', []));
    }

    public static function provFindCapturedValuesWithArgTypeError(): array
    {
        return [
            [[null, []], \string::class],
            [['', null], 'array'],
        ];
    }

    /**
     * @dataProvider provFindCapturedValuesWithArgTypeError
     */
    public function testFindCapturedValuesWithArgTypeError(array $args, string $message): void
    {
        $dummy = $this->createDummyClass();
        $dummy::$findCapturedValues = [];

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);
        $dummy::findCapturedValues(...$args);
    }

    public function testFindCapturedValuesWithRetTypeError(): void
    {
        $dummy = $this->createDummyClass();
        $dummy::$findCapturedValues = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy::findCapturedValues('', []);
    }

    public function testGetErrorMessage(): void
    {
        $dummy = $this->createDummyClass();

        $dummy::$errorMessage = '';
        $this->assertSame($dummy::$errorMessage, $dummy::getErrorMessage('', ''));
        $this->assertSame($dummy::$errorMessage, $dummy::getErrorMessage('', null));
        $this->assertSame($dummy::$errorMessage, $dummy::getErrorMessage(''));
    }

    public static function provGetErrorMessageWithArgTypeError(): array
    {
        return [
            [['', []], \string::class],
            [[null, ''], \string::class],
            [[null], \string::class],
        ];
    }

    /**
     * @dataProvider provGetErrorMessageWithArgTypeError
     */
    public function testGetErrorMessageWithArgTypeError(array $args, string $message): void
    {
        $dummy = $this->createDummyClass();
        $dummy::$errorMessage = '';

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);
        $dummy::getErrorMessage(...$args);
    }

    public function testGetErrorMessageWithRetTypeError(): void
    {
        $dummy = $this->createDummyClass();
        $dummy::$errorMessage = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy::getErrorMessage('', '');
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
