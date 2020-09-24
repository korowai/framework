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

use Korowai\Lib\Rfc\RuleInterface;
use Korowai\Testing\RfclibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Rfc\RuleInterfaceTrait
 *
 * @internal
 */
final class RuleInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class() implements RuleInterface {
            use RuleInterfaceTrait;
        };
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(RuleInterface::class, $dummy);
    }

    public function testToString(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->toString = '';
        $this->assertSame($dummy->toString, $dummy->__toString());
    }

    public function testToStringWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->toString = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->__toString();
    }

    public function testRegexp(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->regexp = '';
        $this->assertSame($dummy->regexp, $dummy->regexp());
    }

    public function testRegexpWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->regexp = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->regexp();
    }

    public function testCaptures(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->captures = [];
        $this->assertSame($dummy->captures, $dummy->captures());
    }

    public function testCapturesWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->captures = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->captures();
    }

    public function testErrorCaptures(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->errorCaptures = [];
        $this->assertSame($dummy->errorCaptures, $dummy->errorCaptures());
    }

    public function testErrorCapturesWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->errorCaptures = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->errorCaptures();
    }

    public function testValueCaptures(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->valueCaptures = [];
        $this->assertSame($dummy->valueCaptures, $dummy->valueCaptures());
    }

    public function testValueCapturesWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->valueCaptures = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->valueCaptures();
    }

    public function testFindCapturedErrors(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->findCapturedErrors = [];
        $this->assertSame($dummy->findCapturedErrors, $dummy->findCapturedErrors([]));
    }

    public function testFindCapturedErrorsWithArgTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->findCapturedErrors = [];

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->findCapturedErrors(null);
    }

    public function testFindCapturedErrorsWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->findCapturedErrors = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->findCapturedErrors([]);
    }

    public function testFindCapturedValuesWithArgTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->findCapturedValues = [];

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->findCapturedValues(null);
    }

    public function testFindCapturedValuesWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->findCapturedValues = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->findCapturedValues([]);
    }

    public function testGetErrorMessage(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->errorMessage = '';
        $this->assertSame($dummy->errorMessage, $dummy->getErrorMessage());
        $this->assertSame($dummy->errorMessage, $dummy->getErrorMessage(''));
    }

    public function testGetErrorMessageWithArgTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->errorMessage = '';

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->getErrorMessage(null);
    }

    public function testGetErrorMessageWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->errorMessage = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->getErrorMessage('');
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
