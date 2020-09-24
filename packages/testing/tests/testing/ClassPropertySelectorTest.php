<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing;

use Korowai\Testing\TestCase;
use Korowai\Testing\AbstractPropertySelector;
use Korowai\Testing\ActualProperties;
use Korowai\Testing\ActualPropertiesInterface;
use Korowai\Testing\ClassPropertySelector;
use Korowai\Testing\PropertySelectorInterface;
use Korowai\Testing\PropertiesInterface;
use PHPUnit\Framework\InvalidArgumentException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\ClassPropertySelector
 */
final class ClassPropertySelectorTest extends TestCase
{
    //
    //
    // TESTS
    //
    //

    public function test__implements__PropertySelectorInterface() : void
    {
        $this->assertImplementsInterface(PropertySelectorInterface::class, ClassPropertySelector::class);
    }

    public function test__extends__AbstractPropertySelector() : void
    {
        $this->assertExtendsClass(AbstractPropertySelector::class, ClassPropertySelector::class);
    }

    //
    // canSelectFrom()
    //
    public static function prov__canSelectFrom() : array
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
     * @dataProvider prov__canSelectFrom
     */
    public function test__canSelectFrom($subject, bool $expect) : void
    {
        $properties = new ClassPropertySelector;
        $this->assertSame($expect, $properties->canSelectFrom($subject));
    }

    //
    // selectProperty
    //
    public static function prov__selectProperty() : array
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
     * @dataProvider prov__selectProperty
     */
    public function test__selectProperty(string $class, $key, $return, $expect) : void
    {
        $properties = new ClassPropertySelector;
        $this->assertSame($return, $properties->selectProperty($class, $key, $retval));
        $this->assertSame($expect, $retval);
    }

    public function test__selectProperty__throwsOnPrivateMethod() : void
    {
        $class = get_class(new class {
            private static function foo() { }
        });
        $properties = new ClassPropertySelector;

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('private method');

        $properties->selectProperty($class, 'foo()');
    }

    public function test__selectProperty__throwsOnPrivateAttribute() : void
    {
        $class = get_class(new class {
            private $foo = 'FOO';
        });
        $properties = new ClassPropertySelector;

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('private property');

        $properties->selectProperty($class, 'foo');
    }

    public function test__selectProperty__throwsOnNonStaticMethod() : void
    {
        $class = get_class(new class {
            public function foo() {}
        });
        $properties = new ClassPropertySelector;

        $this->expectError();
        $this->expectErrorMessage('should not be called statically');

        $properties->selectProperty($class, 'foo()');
    }

    public function test__selectProperty__throwsOnNonStaticProperty() : void
    {
        $class = get_class(new class {
            public $foo = 'FOO';
        });
        $properties = new ClassPropertySelector;

        $this->expectException(\Error::Class);
        $this->expectExceptionMessage('undeclared static property');

        $properties->selectProperty($class, 'foo');
    }

    public static function prov__selectProperty__throwsOnNonClass() : array
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
     * @dataProvider prov__selectProperty__throwsOnNonClass
     */
    public function test__selectProperty__throwsOnNonClass(string $key, string $method) : void
    {
        $properties = new ClassPropertySelector;

        $method = preg_quote(ClassPropertySelector::class.'::'.$method.'()', '/');
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/Argument #1 of '.$method.' must be a class/');

        $properties->selectProperty(123, $key);
    }
}
// vim: syntax=php sw=4 ts=4 et tw=119:
