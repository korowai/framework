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

use Korowai\Testing\Properties\ClassPropertySelector;
use Korowai\Testing\Properties\PropertySelectorInterface;
use PHPUnit\Framework\InvalidArgumentException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ClassPropertySelectorTestTrait
{
    abstract public function createClassPropertySelector(): PropertySelectorInterface;

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
    public static function provClassPropertySelectorCanSelectFrom(): array
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
                'expect' => true,
            ],

            // #2
            'object' => [
                'subject' => get_class(new class() {
                }),
                'expect' => true,
            ],

            // #3
            'new ClassPropertySelector' => [
                'subject' => ClassPropertySelector::class,
                'expect' => true,
            ],
        ];
    }

    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provClassPropertySelectorCanSelectFrom
     *
     * @param mixed $subject
     */
    public function testClassPropertySelectorCanSelectFrom($subject, bool $expect): void
    {
        $selector = $this->createClassPropertySelector();
        $this->assertSame($expect, $selector->canSelectFrom($subject));
    }

    //
    // selectProperty
    //

    // @codeCoverageIgnoreStart
    public static function provClassPropertySelectorSelectProperty(): array
    {
        return [
            // #0
            [
                'class' => get_class(new class() {
                    public static $foo = 'FOO';
                }),
                'key' => 'foo',
                'return' => true,
                'expect' => 'FOO',
            ],

            // #1
            [
                'class' => get_class(new class() {
                    public static $foo = 'FOO';
                }),
                'key' => 'bar',
                'return' => false,
                'expect' => null,
            ],

            // #2
            [
                'class' => get_class(new class() {
                    public static function foo()
                    {
                        return 'FOO';
                    }
                }),
                'key' => 'foo()',
                'return' => true,
                'expect' => 'FOO',
            ],

            // #3
            [
                'class' => get_class(new class() {
                    public static function foo()
                    {
                        return 'FOO';
                    }
                }),
                'key' => 'bar()',
                'return' => false,
                'expect' => null,
            ],
        ];
    }

    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provClassPropertySelectorSelectProperty
     *
     * @param mixed $key
     * @param mixed $return
     * @param mixed $expect
     */
    public function testClassPropertySelectorSelectProperty(string $class, $key, $return, $expect): void
    {
        $selector = $this->createClassPropertySelector();
        $this->assertSame($return, $selector->selectProperty($class, $key, $retval));
        $this->assertSame($expect, $retval);
    }

    public function testClassPropertySelectorSelectPropertyThrowsOnPrivateMethod(): void
    {
        $class = get_class(new class() {
            private static function foo()
            {
                // @codeCoverageIgnoreStart
            }

            // @codeCoverageIgnoreEnd
        });
        $selector = $this->createClassPropertySelector();

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('private method');

        $selector->selectProperty($class, 'foo()');

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    public function testClassPropertySelectorSelectPropertyThrowsOnPrivateAttribute(): void
    {
        $class = get_class(new class() {
            private $foo = 'FOO';
        });
        $selector = $this->createClassPropertySelector();

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('private property');

        $selector->selectProperty($class, 'foo');

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    public function testClassPropertySelectorSelectPropertyThrowsOnNonStaticMethod(): void
    {
        $class = get_class(new class() {
            public function foo()
            {
                // @codeCoverageIgnoreStart
            }

            // @codeCoverageIgnoreEnd
        });
        $selector = $this->createClassPropertySelector();

        $this->expectError();
        $this->expectErrorMessage('should not be called statically');

        $selector->selectProperty($class, 'foo()');

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    public function testClassPropertySelectorSelectPropertyThrowsOnNonStaticProperty(): void
    {
        $class = get_class(new class() {
            public $foo = 'FOO';
        });
        $selector = $this->createClassPropertySelector();

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('undeclared static property');

        $selector->selectProperty($class, 'foo');

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    // @codeCoverageIgnoreStart
    public static function provClassPropertySelectorSelectPropertyThrowsOnNonClass(): array
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
     * @dataProvider provClassPropertySelectorSelectPropertyThrowsOnNonClass
     */
    public function testClassPropertySelectorSelectPropertyThrowsOnNonClass(string $key, string $method): void
    {
        $selector = $this->createClassPropertySelector();

        $method = preg_quote(ClassPropertySelector::class.'::'.$method.'()', '/');
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/Argument #1 of '.$method.' must be a class/');

        $selector->selectProperty(123, $key);

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd
}
// vim: syntax=php sw=4 ts=4 et:
