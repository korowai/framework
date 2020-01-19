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

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class RuleInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements RuleInterface {
            use RuleInterfaceTrait;
        };
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(RuleInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'toString'      => '__toString',
            'regexp'        => 'regexp',
            'captures'      => 'captures',
            'errorCaptures' => 'errorCaptures',
            'valueCaptures' => 'valueCaptures',
        ];
        $this->assertObjectPropertyGetters($expect, RuleInterface::class);
    }

    public function test__toString()
    {
        $dummy = $this->createDummyInstance();

        $dummy->toString = '';
        $this->assertSame($dummy->toString, $dummy->__toString());
    }

    public function test__toString__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->toString = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->__toString();
    }

    public function test__regexp()
    {
        $dummy = $this->createDummyInstance();

        $dummy->regexp = '';
        $this->assertSame($dummy->regexp, $dummy->regexp());
    }

    public function test__regexp__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->regexp = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->regexp();
    }

    public function test__captures()
    {
        $dummy = $this->createDummyInstance();

        $dummy->captures = [];
        $this->assertSame($dummy->captures, $dummy->captures());
    }

    public function test__captures__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->captures = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->captures();
    }

    public function test__errorCaptures()
    {
        $dummy = $this->createDummyInstance();

        $dummy->errorCaptures = [];
        $this->assertSame($dummy->errorCaptures, $dummy->errorCaptures());
    }

    public function test__errorCaptures__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->errorCaptures = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->errorCaptures();
    }

    public function test__valueCaptures()
    {
        $dummy = $this->createDummyInstance();

        $dummy->valueCaptures = [];
        $this->assertSame($dummy->valueCaptures, $dummy->valueCaptures());
    }

    public function test__valueCaptures__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->valueCaptures = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->valueCaptures();
    }

    public function test__findCapturedErrors()
    {
        $dummy = $this->createDummyInstance();

        $dummy->findCapturedErrors = [];
        $this->assertSame($dummy->findCapturedErrors, $dummy->findCapturedErrors([]));
    }

    public function test__findCapturedErrors__withArgTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->findCapturedErrors = [];

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->findCapturedErrors(null);
    }

    public function test__findCapturedErrors__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->findCapturedErrors = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->findCapturedErrors([]);
    }

    public function test__findCapturedValues__withArgTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->findCapturedValues = [];

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->findCapturedValues(null);
    }

    public function test__findCapturedValues__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->findCapturedValues = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->findCapturedValues([]);
    }

    public function test__getErrorMessage()
    {
        $dummy = $this->createDummyInstance();

        $dummy->errorMessage = '';
        $this->assertSame($dummy->errorMessage, $dummy->getErrorMessage());
        $this->assertSame($dummy->errorMessage, $dummy->getErrorMessage(''));
    }

    public function test__getErrorMessage__withArgTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->errorMessage = '';

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->getErrorMessage(null);
    }

    public function test__getErrorMessage__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->errorMessage = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->getErrorMessage('');
    }
}

// vim: syntax=php sw=4 ts=4 et:
