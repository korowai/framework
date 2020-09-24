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
use Korowai\Testing\Properties\ActualProperties;
use Korowai\Testing\Properties\RecursivePropertiesSelector;
use Korowai\Testing\Properties\RecursivePropertiesUnwrapper;
use Korowai\Testing\Properties\RecursivePropertiesSelectorInterface;
use Korowai\Testing\Properties\ExpectedProperties;
use Korowai\Testing\Properties\PropertiesInterface;
use Korowai\Testing\Properties\ObjectPropertySelector;
use Korowai\Testing\Properties\ClassPropertySelector;
use Korowai\Testing\Properties\CircularDependencyException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Properties\RecursivePropertiesSelector
 */
final class RecursivePropertiesSelectorTest extends TestCase
{
    public const UNIQUE_TAG = RecursivePropertiesUnwrapper::UNIQUE_TAG;

    public static function createExpectedObjectProperties(...$args) : ExpectedProperties
    {
        return new ExpectedProperties(new ObjectPropertySelector, ...$args);
    }

    public static function createExpectedClassProperties(...$args) : ExpectedProperties
    {
        return new ExpectedProperties(new ClassPropertySelector, ...$args);
    }

    //
    //
    // TESTS
    //
    //

    public function test__implements__RecursivePropertiesSelectorInterface() : void
    {
        $this->assertImplementsInterface(RecursivePropertiesSelectorInterface::class, RecursivePropertiesSelector::class);
    }

    //
    // unwrap()
    //

    public static function prov__selectProperties() : array
    {
        $actAct = new ActualProperties([
            'act' => 'x:ACT',
        ]);

        $expExp = new ExpectedProperties(new ObjectPropertySelector, [
            'exp' => 'x:EXP',
        ]);

        return [
            // #0
            [
                'selector' => static::createExpectedObjectProperties([]),
                'subject'  => new class { },
                'expect'   => [
                    self::UNIQUE_TAG => true,
                ],
            ],

            // #1
            [
                'selector' => static::createExpectedObjectProperties([
                    'foo'  => 'e:FOO',
                ]),
                'subject'  => new class {
                    public $foo = 'a:FOO';
                },
                'expect'   => [
                    'foo'  => 'a:FOO',
                    self::UNIQUE_TAG => true,
                ],
            ],

            // #2
            [
                'selector' => static::createExpectedObjectProperties([
                    'foo'  => 'e:FOO',
                    'bar'  => [
                        'baz' => 'e:BAZ'
                    ],
                ]),
                'subject'  => new class {
                    public $foo = 'a:FOO';
                    public $bar = [
                        'baz' => 'a:BAZ'
                    ];
                },
                'expect'   => [
                    'foo'  => 'a:FOO',
                    'bar'  => [
                        'baz' => 'a:BAZ',
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

            // #3
            [
                'selector' => static::createExpectedObjectProperties([
                    'foo'  => 'e:FOO',
                    'bar'  => [
                        'baz' => 'e:BAZ'
                    ],
                ]),
                'subject'  => new class {
                    public $foo = 'a:FOO';
                    public $bar = 'a:BAR';
                },
                'expect'   => [
                    'foo'  => 'a:FOO',
                    'bar'  => 'a:BAR',
                    self::UNIQUE_TAG => true,
                ],
            ],

            // #4
            [
                'selector' => static::createExpectedObjectProperties([
                    'foo'  => 'e:FOO',
                    'bar'  => static::createExpectedObjectProperties([
                        'baz' => 'e:BAZ',
                    ]),
                ]),
                'subject'  => new class {
                    public $foo = 'a:FOO';
                    public $bar;

                    public function __construct()
                    {
                        $this->bar = new class {
                            public $baz = 'a:BAZ';
                        };
                    }
                },
                'expect'   => [
                    'foo'  => 'a:FOO',
                    'bar'  => [
                        'baz' => 'a:BAZ',
                        self::UNIQUE_TAG => true,
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

            // #5
            [
                'selector' => static::createExpectedObjectProperties([
                    'foo'  => 'e:FOO',
                    'bar'  => 'e:BAR',
                ]),
                'subject'  => new class($actAct) {
                    public $foo = 'a:FOO';
                    public $bar;

                    public function __construct($bar)
                    {
                        $this->bar = $bar;
                    }
                },
                'expect'   => [
                    'foo'  => 'a:FOO',
                    'bar'  => [
                        'act' => 'x:ACT',
                        self::UNIQUE_TAG => true,
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

            // #6
            [
                'selector' => static::createExpectedObjectProperties([
                    'foo'  => 'e:FOO',
                    'bar'  => 'e:BAR',
                ]),
                'subject'  => new class($expExp) {
                    public $foo = 'a:FOO';
                    public $bar;

                    public function __construct($bar)
                    {
                        $this->bar = $bar;
                    }
                },
                'expect'   => [
                    'foo'  => 'a:FOO',
                    'bar'  => $expExp,
                    self::UNIQUE_TAG => true,
                ],
            ],

            // #7
            [
                'selector' => static::createExpectedClassProperties([]),
                'subject'  => new class { },
                'expect'   => [
                    self::UNIQUE_TAG => true,
                ],
            ],

            // #8
            [
                'selector' => static::createExpectedClassProperties([
                    'foo'  => 'e:FOO',
                ]),
                'subject'  => get_class(new class {
                    public static $foo = 'a:FOO';
                }),
                'expect'   => [
                    'foo'  => 'a:FOO',
                    self::UNIQUE_TAG => true,
                ],
            ],
        ];
    }


    /**
     * @dataProvider prov__selectProperties
     */
    public function test__selectProperties(ExpectedProperties $selector, $subject, array $expect) : void
    {
        $selector = new RecursivePropertiesSelector($selector);
        $unwrapper = new RecursivePropertiesUnwrapper;
        $selected = $selector->selectProperties($subject);
        $this->assertInstanceOf(ActualProperties::class, $selected);
        $this->assertSame($expect, $unwrapper->unwrap($selected));
    }
}
// vim: syntax=php sw=4 ts=4 et tw=119:
