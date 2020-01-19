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

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class StaticRuleSetInterfaceTest extends TestCase
{
    public static function createDummyClass()
    {
        return get_class(new class implements StaticRuleSetInterface {
            use StaticRuleSetInterfaceTrait;
        });
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyClass();
        $this->assertImplementsInterface(StaticRuleSetInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'rules' => 'rules',
        ];
        $this->assertObjectPropertyGetters($expect, StaticRuleSetInterface::class);
    }

    public function test__rules()
    {
        $dummy = $this->createDummyClass();

        $dummy::$rules = [];
        $this->assertSame($dummy::$rules, $dummy::rules());
    }

    public function test__rules__withRetTypeError()
    {
        $dummy = $this->createDummyClass();
        $dummy::$rules = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy::rules();
    }

    public function test__regexp()
    {
        $dummy = $this->createDummyClass();

        $dummy::$regexp = '';
        $this->assertSame($dummy::$regexp, $dummy::regexp(''));
    }

    public function test__regexp__withArgTypeError()
    {
        $dummy = $this->createDummyClass();
        $dummy::$regexp = '';

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy::regexp(null);
    }

    public function test__regexp__withRetTypeError()
    {
        $dummy = $this->createDummyClass();
        $dummy::$regexp = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy::regexp('');
    }

    public function test__captures()
    {
        $dummy = $this->createDummyClass();

        $dummy::$captures = [];
        $this->assertSame($dummy::$captures, $dummy::captures(''));
    }

    public function test__captures__withArgTypeError()
    {
        $dummy = $this->createDummyClass();
        $dummy::$captures = [];

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy::captures(null);
    }

    public function test__captures__withRetTypeError()
    {
        $dummy = $this->createDummyClass();
        $dummy::$captures = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy::captures('');
    }

    public function test__errorCaptures()
    {
        $dummy = $this->createDummyClass();

        $dummy::$errorCaptures = [];
        $this->assertSame($dummy::$errorCaptures, $dummy::errorCaptures(''));
    }

    public function test__errorCaptures__withArgTypeError()
    {
        $dummy = $this->createDummyClass();
        $dummy::$errorCaptures = [];

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy::errorCaptures(null);
    }

    public function test__errorCaptures__withRetTypeError()
    {
        $dummy = $this->createDummyClass();
        $dummy::$errorCaptures = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy::errorCaptures('');
    }

    public function test__valueCaptures()
    {
        $dummy = $this->createDummyClass();

        $dummy::$valueCaptures = [];
        $this->assertSame($dummy::$valueCaptures, $dummy::valueCaptures(''));
    }

    public function test__valueCaptures__withArgTypeError()
    {
        $dummy = $this->createDummyClass();
        $dummy::$valueCaptures = [];

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy::valueCaptures(null);
    }

    public function test__valueCaptures__withRetTypeError()
    {
        $dummy = $this->createDummyClass();
        $dummy::$valueCaptures = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy::valueCaptures('');
    }


    public function test__findCapturedErrors()
    {
        $dummy = $this->createDummyClass();

        $dummy::$findCapturedErrors = [];
        $this->assertSame($dummy::$findCapturedErrors, $dummy::findCapturedErrors('', []));
    }

    public static function findCapturedErrors__withArgTypeError__cases()
    {
        return [
            [[null, []], \string::class],
            [['', null], 'array'],
        ];
    }

    /**
     * @dataProvider findCapturedErrors__withArgTypeError__cases
     */
    public function test__findCapturedErrors__withArgTypeError(array $args, string $message)
    {
        $dummy = $this->createDummyClass();
        $dummy::$findCapturedErrors = [];

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);
        $dummy::findCapturedErrors(...$args);
    }

    public function test__findCapturedErrors__withRetTypeError()
    {
        $dummy = $this->createDummyClass();
        $dummy::$findCapturedErrors = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy::findCapturedErrors('', []);
    }

    public function test__findCapturedValues()
    {
        $dummy = $this->createDummyClass();

        $dummy::$findCapturedValues = [];
        $this->assertSame($dummy::$findCapturedValues, $dummy::findCapturedValues('', []));
    }

    public static function findCapturedValues__withArgTypeError__cases()
    {
        return [
            [[null, []], \string::class],
            [['', null], 'array'],
        ];
    }

    /**
     * @dataProvider findCapturedValues__withArgTypeError__cases
     */
    public function test__findCapturedValues__withArgTypeError(array $args, string $message)
    {
        $dummy = $this->createDummyClass();
        $dummy::$findCapturedValues = [];

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);
        $dummy::findCapturedValues(...$args);
    }

    public function test__findCapturedValues__withRetTypeError()
    {
        $dummy = $this->createDummyClass();
        $dummy::$findCapturedValues = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy::findCapturedValues('', []);
    }

    public function test__getErrorMessage()
    {
        $dummy = $this->createDummyClass();

        $dummy::$errorMessage = '';
        $this->assertSame($dummy::$errorMessage, $dummy::getErrorMessage('', ''));
        $this->assertSame($dummy::$errorMessage, $dummy::getErrorMessage('', null));
        $this->assertSame($dummy::$errorMessage, $dummy::getErrorMessage(''));
    }

    public static function getErrorMessage__withArgTypeError__cases()
    {
        return [
            [['', []], \string::class],
            [[null, ''], \string::class],
            [[null], \string::class],
        ];
    }

    /**
     * @dataProvider getErrorMessage__withArgTypeError__cases
     */
    public function test__getErrorMessage__withArgTypeError(array $args, string $message)
    {
        $dummy = $this->createDummyClass();
        $dummy::$errorMessage = '';

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);
        $dummy::getErrorMessage(...$args);
    }

    public function test__getErrorMessage__withRetTypeError()
    {
        $dummy = $this->createDummyClass();
        $dummy::$errorMessage = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy::getErrorMessage('', '');
    }
}

// vim: syntax=php sw=4 ts=4 et:
