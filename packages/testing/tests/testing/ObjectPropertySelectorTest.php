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
use Korowai\Testing\ObjectPropertySelector;
use Korowai\Testing\PropertySelectorInterface;
use Korowai\Testing\PropertiesInterface;
use PHPUnit\Framework\InvalidArgumentException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\ObjectPropertySelector
 */
final class ObjectPropertySelectorTest extends TestCase
{
    //
    //
    // TESTS
    //
    //

    public function test__implements__PropertySelectorInterface() : void
    {
        $this->assertImplementsInterface(PropertySelectorInterface::class, ObjectPropertySelector::class);
    }

    public function test__extends__AbstractPropertySelector() : void
    {
        $this->assertExtendsClass(AbstractPropertySelector::class, ObjectPropertySelector::class);
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
                'expect'  => false,
            ],

            // #2
            'object' => [
                'subject' => new class {},
                'expect'  => true,
            ],

            // #3
            'new ObjectPropertySelector' => [
                'subject' => new ObjectPropertySelector,
                'expect'  => true,
            ]
        ];
    }

    /**
     * @dataProvider prov__canSelectFrom
     */
    public function test__canSelectFrom($subject, bool $expect) : void
    {
        $properties = new ObjectPropertySelector;
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
                'object'  => new class {
                    public $foo = 'FOO';
                },
                'key'    => 'foo',
                'return' => true,
                'expect' => 'FOO',
            ],

            // #1
            [
                'object'  => new class {
                    public $foo = 'FOO';
                },
                'key'    => 'bar',
                'return' => false,
                'expect' => null,
            ],

            // #2
            [
                'object'  => new class {
                    public function foo() {
                        return 'FOO';
                    }
                },
                'key'    => 'foo()',
                'return' => true,
                'expect' => 'FOO',
            ],

            // #3
            [
                'object'  => new class {
                    public static function foo() {
                        return 'FOO';
                    }
                },
                'key'    => 'foo()',
                'return' => true,
                'expect' => 'FOO',
            ],

            // #4
            [
                'object'  => new class {
                    public function foo() {
                        return 'FOO';
                    }
                },
                'key'    => 'bar()',
                'return' => false,
                'expect' => null,
            ],
        ];
    }

    /**
     * @dataProvider prov__selectProperty
     */
    public function test__selectProperty(object $object, $key, $return, $expect) : void
    {
        $properties = new ObjectPropertySelector;
        $this->assertSame($return, $properties->selectProperty($object, $key, $retval));
        $this->assertSame($expect, $retval);
    }

    public function test__selectProperty__throwsOnPrivateMethod() : void
    {
        $object = new class {
            private function foo() { }
        };
        $properties = new ObjectPropertySelector;

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('private method');

        $properties->selectProperty($object, 'foo()');
    }

    public function test__selectProperty__throwsOnPrivateAttribute() : void
    {
        $object = new class {
            private $foo = 'FOO';
        };
        $properties = new ObjectPropertySelector;

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('private property');

        $properties->selectProperty($object, 'foo');
    }

    public function test__selectProperty__throwsOnStaticProperty() : void
    {
        $object = new class {
            public static $foo = 'FOO';
        };
        $properties = new ObjectPropertySelector;

        $this->expectError();
        $this->expectErrorMessage('static property');

        $properties->selectProperty($object, 'foo');
    }

    public static function prov__selectProperty__throwsOnNonobject() : array
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
     * @dataProvider prov__selectProperty__throwsOnNonobject
     */
    public function test__selectProperty__throwsOnNonobject(string $key, string $method) : void
    {
        $properties = new ObjectPropertySelector;

        $method = preg_quote(ObjectPropertySelector::class.'::'.$method.'()', '/');
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/Argument #1 of '.$method.' must be an object/');

        $properties->selectProperty(123, $key);
    }
}
// vim: syntax=php sw=4 ts=4 et tw=119:
