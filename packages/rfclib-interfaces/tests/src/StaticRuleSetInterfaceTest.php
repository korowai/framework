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
 */
final class StaticRuleSetInterfaceTest extends TestCase
{
    public static function createDummyClass()
    {
        return get_class(new class implements StaticRuleSetInterface {
            use StaticRuleSetInterfaceTrait;
        });
    }

    public function test__dummyImplementation() : void
    {
        $dummy = $this->createDummyClass();
        $this->assertImplementsInterface(StaticRuleSetInterface::class, $dummy);
    }

    public function test__rules() : void
    {
        $dummy = $this->createDummyClass();

        $dummy::$rules = [];
        $this->assertSame($dummy::$rules, $dummy::rules());
    }

    public function test__rules__withRetTypeError() : void
    {
        $dummy = $this->createDummyClass();
        $dummy::$rules = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy::rules();
    }

    public function test__regexp() : void
    {
        $dummy = $this->createDummyClass();

        $dummy::$regexp = '';
        $this->assertSame($dummy::$regexp, $dummy::regexp(''));
    }

    public function test__regexp__withArgTypeError() : void
    {
        $dummy = $this->createDummyClass();
        $dummy::$regexp = '';

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy::regexp(null);
    }

    public function test__regexp__withRetTypeError() : void
    {
        $dummy = $this->createDummyClass();
        $dummy::$regexp = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy::regexp('');
    }

    public function test__captures() : void
    {
        $dummy = $this->createDummyClass();

        $dummy::$captures = [];
        $this->assertSame($dummy::$captures, $dummy::captures(''));
    }

    public function test__captures__withArgTypeError() : void
    {
        $dummy = $this->createDummyClass();
        $dummy::$captures = [];

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy::captures(null);
    }

    public function test__captures__withRetTypeError() : void
    {
        $dummy = $this->createDummyClass();
        $dummy::$captures = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy::captures('');
    }

    public function test__errorCaptures() : void
    {
        $dummy = $this->createDummyClass();

        $dummy::$errorCaptures = [];
        $this->assertSame($dummy::$errorCaptures, $dummy::errorCaptures(''));
    }

    public function test__errorCaptures__withArgTypeError() : void
    {
        $dummy = $this->createDummyClass();
        $dummy::$errorCaptures = [];

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy::errorCaptures(null);
    }

    public function test__errorCaptures__withRetTypeError() : void
    {
        $dummy = $this->createDummyClass();
        $dummy::$errorCaptures = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy::errorCaptures('');
    }

    public function test__valueCaptures() : void
    {
        $dummy = $this->createDummyClass();

        $dummy::$valueCaptures = [];
        $this->assertSame($dummy::$valueCaptures, $dummy::valueCaptures(''));
    }

    public function test__valueCaptures__withArgTypeError() : void
    {
        $dummy = $this->createDummyClass();
        $dummy::$valueCaptures = [];

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy::valueCaptures(null);
    }

    public function test__valueCaptures__withRetTypeError() : void
    {
        $dummy = $this->createDummyClass();
        $dummy::$valueCaptures = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy::valueCaptures('');
    }


    public function test__findCapturedErrors() : void
    {
        $dummy = $this->createDummyClass();

        $dummy::$findCapturedErrors = [];
        $this->assertSame($dummy::$findCapturedErrors, $dummy::findCapturedErrors('', []));
    }

    public static function prov__findCapturedErrors__withArgTypeError() : array
    {
        return [
            [[null, []], \string::class],
            [['', null], 'array'],
        ];
    }

    /**
     * @dataProvider prov__findCapturedErrors__withArgTypeError
     */
    public function test__findCapturedErrors__withArgTypeError(array $args, string $message) : void
    {
        $dummy = $this->createDummyClass();
        $dummy::$findCapturedErrors = [];

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);
        $dummy::findCapturedErrors(...$args);
    }

    public function test__findCapturedErrors__withRetTypeError() : void
    {
        $dummy = $this->createDummyClass();
        $dummy::$findCapturedErrors = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy::findCapturedErrors('', []);
    }

    public function test__findCapturedValues() : void
    {
        $dummy = $this->createDummyClass();

        $dummy::$findCapturedValues = [];
        $this->assertSame($dummy::$findCapturedValues, $dummy::findCapturedValues('', []));
    }

    public static function prov__findCapturedValues__withArgTypeError() : array
    {
        return [
            [[null, []], \string::class],
            [['', null], 'array'],
        ];
    }

    /**
     * @dataProvider prov__findCapturedValues__withArgTypeError
     */
    public function test__findCapturedValues__withArgTypeError(array $args, string $message) : void
    {
        $dummy = $this->createDummyClass();
        $dummy::$findCapturedValues = [];

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);
        $dummy::findCapturedValues(...$args);
    }

    public function test__findCapturedValues__withRetTypeError() : void
    {
        $dummy = $this->createDummyClass();
        $dummy::$findCapturedValues = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy::findCapturedValues('', []);
    }

    public function test__getErrorMessage() : void
    {
        $dummy = $this->createDummyClass();

        $dummy::$errorMessage = '';
        $this->assertSame($dummy::$errorMessage, $dummy::getErrorMessage('', ''));
        $this->assertSame($dummy::$errorMessage, $dummy::getErrorMessage('', null));
        $this->assertSame($dummy::$errorMessage, $dummy::getErrorMessage(''));
    }

    public static function prov__getErrorMessage__withArgTypeError() : array
    {
        return [
            [['', []], \string::class],
            [[null, ''], \string::class],
            [[null], \string::class],
        ];
    }

    /**
     * @dataProvider prov__getErrorMessage__withArgTypeError
     */
    public function test__getErrorMessage__withArgTypeError(array $args, string $message) : void
    {
        $dummy = $this->createDummyClass();
        $dummy::$errorMessage = '';

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);
        $dummy::getErrorMessage(...$args);
    }

    public function test__getErrorMessage__withRetTypeError() : void
    {
        $dummy = $this->createDummyClass();
        $dummy::$errorMessage = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy::getErrorMessage('', '');
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
