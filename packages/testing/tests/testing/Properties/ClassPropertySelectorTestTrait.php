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

use Korowai\Testing\TestCase;
use Korowai\Testing\Properties\AbstractPropertySelector;
use Korowai\Testing\Properties\ActualProperties;
use Korowai\Testing\Properties\ActualPropertiesInterface;
use Korowai\Testing\Properties\ClassPropertySelector;
use Korowai\Testing\Properties\PropertySelectorInterface;
use Korowai\Testing\Properties\PropertiesInterface;
use PHPUnit\Framework\InvalidArgumentException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ClassPropertySelectorTestTrait
{
    abstract public function createClassPropertySelector() : PropertySelectorInterface;
    abstract public static function assertSame($expected, $value, string $message = '') : void;
    abstract public function expectError() : void;
    abstract public function expectErrorMessage(string $message) : void;
    abstract public function expectException(string $exception) : void;
    abstract public function expectExceptionMessage(string $message) : void;
    abstract public function expectExceptionMessageMatches(string $regularExpression) : void;

    //
    // canSelectFrom()
    //
    public static function prov__ClassPropertySelector__canSelectFrom() : array
    {
        return [
            // #0
            'string' => [
                'subject' => 'foo',
                'expect'  => false,
            ],

            // #1
            'array' => [
                'subject' => [],
                'expect'  => false,
            ],

            'class' => [
                'subject' => self::class,
                'expect'  => true,
            ],

            // #2
            'object' => [
                'subject' => get_class(new class {}),
                'expect'  => true,
            ],

            // #3
            'new ClassPropertySelector' => [
                'subject' => ClassPropertySelector::class,
                'expect'  => true,
            ]
        ];
    }

    /**
     * @dataProvider prov__ClassPropertySelector__canSelectFrom
     */
    public function test__ClassPropertySelector__canSelectFrom($subject, bool $expect) : void
    {
        $selector = $this->createClassPropertySelector();
        $this->assertSame($expect, $selector->canSelectFrom($subject));
    }

    //
    // selectProperty
    //
    public static function prov__ClassPropertySelector__selectProperty() : array
    {
        return [
            // #0
            [
                'class'  => get_class(new class {
                    public static $foo = 'FOO';
                }),
                'key'    => 'foo',
                'return' => true,
                'expect' => 'FOO',
            ],

            // #1
            [
                'class'  => get_class(new class {
                    public static $foo = 'FOO';
                }),
                'key'    => 'bar',
                'return' => false,
                'expect' => null,
            ],

            // #2
            [
                'class'  => get_class(new class {
                    public static function foo() {
                        return 'FOO';
                    }
                }),
                'key'    => 'foo()',
                'return' => true,
                'expect' => 'FOO',
            ],

            // #3
            [
                'class'  => get_class(new class {
                    public static function foo() {
                        return 'FOO';
                    }
                }),
                'key'    => 'bar()',
                'return' => false,
                'expect' => null,
            ],
        ];
    }

    /**
     * @dataProvider prov__ClassPropertySelector__selectProperty
     */
    public function test__ClassPropertySelector__selectProperty(string $class, $key, $return, $expect) : void
    {
        $selector = $this->createClassPropertySelector();
        $this->assertSame($return, $selector->selectProperty($class, $key, $retval));
        $this->assertSame($expect, $retval);
    }

    public function test__ClassPropertySelector__selectProperty__throwsOnPrivateMethod() : void
    {
        $class = get_class(new class {
            private static function foo() { }
        });
        $selector = $this->createClassPropertySelector();

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('private method');

        $selector->selectProperty($class, 'foo()');
    }

    public function test__ClassPropertySelector__selectProperty__throwsOnPrivateAttribute() : void
    {
        $class = get_class(new class {
            private $foo = 'FOO';
        });
        $selector = $this->createClassPropertySelector();

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('private property');

        $selector->selectProperty($class, 'foo');
    }

    public function test__ClassPropertySelector__selectProperty__throwsOnNonStaticMethod() : void
    {
        $class = get_class(new class {
            public function foo() {}
        });
        $selector = $this->createClassPropertySelector();

        $this->expectError();
        $this->expectErrorMessage('should not be called statically');

        $selector->selectProperty($class, 'foo()');
    }

    public function test__ClassPropertySelector__selectProperty__throwsOnNonStaticProperty() : void
    {
        $class = get_class(new class {
            public $foo = 'FOO';
        });
        $selector = $this->createClassPropertySelector();

        $this->expectException(\Error::Class);
        $this->expectExceptionMessage('undeclared static property');

        $selector->selectProperty($class, 'foo');
    }

    public static function prov__ClassPropertySelector__selectProperty__throwsOnNonClass() : array
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

    /**
     * @dataProvider prov__ClassPropertySelector__selectProperty__throwsOnNonClass
     */
    public function test__ClassPropertySelector__selectProperty__throwsOnNonClass(string $key, string $method) : void
    {
        $selector = $this->createClassPropertySelector();

        $method = preg_quote(ClassPropertySelector::class.'::'.$method.'()', '/');
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/Argument #1 of '.$method.' must be a class/');

        $selector->selectProperty(123, $key);
    }
}
// vim: syntax=php sw=4 ts=4 et tw=119:
