<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\Properties;

use Korowai\Testing\Properties\ObjectPropertySelector;
use Korowai\Testing\Properties\PropertySelectorInterface;
use PHPUnit\Framework\InvalidArgumentException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ObjectPropertySelectorTestTrait
{
    abstract public function createObjectPropertySelector(): PropertySelectorInterface;

    abstract public static function assertSame($expected, $value, string $message = ''): void;

    abstract public function expectError(): void;

    abstract public function expectErrorMessage(string $message): void;

    abstract public function expectException(string $exception): void;

    abstract public function expectExceptionMessage(string $message): void;

    abstract public function expectExceptionMessageMatches(string $regularExpression): void;

    //
    // canSelectFrom()
    //

    // @codeCoverageIgnoreStart
    public function provObjectPropertySelectorCanSelectFrom(): array
    {
        return [
            // #0
            'string' => [
                'subject' => 'foo',
                'expect' => false,
            ],

            // #1
            'array' => [
                'subject' => [],
                'expect' => false,
            ],

            'class' => [
                'subject' => self::class,
                'expect' => false,
            ],

            // #2
            'object' => [
                'subject' => new class() {
                },
                'expect' => true,
            ],

            // #3
            'new ObjectPropertySelector' => [
                'subject' => $this->createObjectPropertySelector(),
                'expect' => true,
            ],
        ];
    }

    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provObjectPropertySelectorCanSelectFrom
     *
     * @param mixed $subject
     */
    public function testObjectPropertySelectorCanSelectFrom($subject, bool $expect): void
    {
        $properties = $this->createObjectPropertySelector();
        $this->assertSame($expect, $properties->canSelectFrom($subject));
    }

    //
    // selectProperty
    //

    // @codeCoverageIgnoreStart
    public static function provObjectPropertySelectorSelectProperty(): array
    {
        return [
            // #0
            [
                'object' => new class() {
                    public $foo = 'FOO';
                },
                'key' => 'foo',
                'return' => true,
                'expect' => 'FOO',
            ],

            // #1
            [
                'object' => new class() {
                    public $foo = 'FOO';
                },
                'key' => 'bar',
                'return' => false,
                'expect' => null,
            ],

            // #2
            [
                'object' => new class() {
                    public function foo()
                    {
                        return 'FOO';
                    }
                },
                'key' => 'foo()',
                'return' => true,
                'expect' => 'FOO',
            ],

            // #3
            [
                'object' => new class() {
                    public static function foo()
                    {
                        return 'FOO';
                    }
                },
                'key' => 'foo()',
                'return' => true,
                'expect' => 'FOO',
            ],

            // #4
            [
                'object' => new class() {
                    public function foo()
                    {
                        return 'FOO';
                    }
                },
                'key' => 'bar()',
                'return' => false,
                'expect' => null,
            ],
        ];
    }

    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provObjectPropertySelectorSelectProperty
     *
     * @param mixed $key
     * @param mixed $return
     * @param mixed $expect
     */
    public function testObjectPropertySelectorSelectProperty(object $object, $key, $return, $expect): void
    {
        $properties = $this->createObjectPropertySelector();
        $this->assertSame($return, $properties->selectProperty($object, $key, $retval));
        $this->assertSame($expect, $retval);
    }

    public function testObjectPropertySelectorSelectPropertyThrowsOnPrivateMethod(): void
    {
        $object = new class() {
            private function foo()
            {
                // @codeCoverageIgnoreStart
            }

            // @codeCoverageIgnoreEnd
        };
        $properties = $this->createObjectPropertySelector();

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('private method');

        $properties->selectProperty($object, 'foo()');

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    public function testObjectPropertySelectorSelectPropertyThrowsOnPrivateAttribute(): void
    {
        $object = new class() {
            private $foo = 'FOO';
        };
        $properties = $this->createObjectPropertySelector();

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('private property');

        $properties->selectProperty($object, 'foo');

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    public function testObjectPropertySelectorSelectPropertyThrowsOnStaticProperty(): void
    {
        $object = new class() {
            public static $foo = 'FOO';
        };
        $properties = $this->createObjectPropertySelector();

        $this->expectError();
        $this->expectErrorMessage('static property');

        $properties->selectProperty($object, 'foo');

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    // @codeCoverageIgnoreStart
    public static function provObjectPropertySelectorSelectPropertyThrowsOnNonobject(): array
    {
        return [
            // #0
            [
                'key' => 'foo',
                'method' => 'selectWithAttribute',
            ],

            // #1
            [
                'key' => 'foo()',
                'method' => 'selectWithMethod',
            ],
        ];
    }

    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provObjectPropertySelectorSelectPropertyThrowsOnNonobject
     */
    public function testObjectPropertySelectorSelectPropertyThrowsOnNonobject(string $key, string $method): void
    {
        $properties = $this->createObjectPropertySelector();

        $method = preg_quote(ObjectPropertySelector::class.'::'.$method.'()', '/');
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/Argument #1 of '.$method.' must be an object/');

        $properties->selectProperty(123, $key);

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd
}
// vim: syntax=php sw=4 ts=4 et:
