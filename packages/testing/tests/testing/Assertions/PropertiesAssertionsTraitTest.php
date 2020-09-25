<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\Assertions;

use Korowai\Testing\Assertions\PropertiesAssertionsTrait;
use PHPUnit\Framework\Constraint\UnaryOperator;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Assertions\PropertiesAssertionsTrait
 * @covers \Korowai\Testing\Constraint\AbstractPropertiesComparator
 * @covers \Korowai\Testing\Constraint\ClassPropertiesEqualTo
 * @covers \Korowai\Testing\Constraint\ClassPropertiesIdenticalTo
 * @covers \Korowai\Testing\Constraint\ObjectPropertiesEqualTo
 * @covers \Korowai\Testing\Constraint\ObjectPropertiesIdenticalTo
 *
 * @internal
 */
final class PropertiesAssertionsTraitTest extends TestCase
{
    use PropertiesAssertionsTrait;

    public function provStaticMethodExists(): array
    {
        return [
            ['assertClassPropertiesEqualTo'],
            ['assertClassPropertiesIdenticalTo'],
            ['assertObjectPropertiesEqualTo'],
            ['assertObjectPropertiesIdenticalTo'],
            ['assertNotClassPropertiesEqualTo'],
            ['assertNotClassPropertiesIdenticalTo'],
            ['assertNotObjectPropertiesEqualTo'],
            ['assertNotObjectPropertiesIdenticalTo'],
            ['classPropertiesEqualTo'],
            ['classPropertiesIdenticalTo'],
            ['objectPropertiesEqualTo'],
            ['objectPropertiesIdenticalTo'],
        ];
    }

    /**
     * @dataProvider provStaticMethodExists
     */
    public function testStaticMethodExists(string $name): void
    {
        $classAndMethod = [self::class, $name];
        self::assertTrue(method_exists(...$classAndMethod));
        $method = new \ReflectionMethod(...$classAndMethod);
        self::assertTrue($method->isStatic());
    }

    public static function provConstraintWithInvalidExpectationSpec(): array
    {
        $specs = [
            '3-int-keys' => [
                'array' => [
                    'a' => 'A', 0 => 'B', 2 => 'C', 7 => 'D', 'e' => 'E',
                ],
                'expect' => [
                    'exception' => \PHPUnit\Framework\Exception::class,
                    'message' => 'The array of expected properties contains 3 invalid key(s)',
                ],
            ],
        ];

        return [
            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'method' => 'objectPropertiesIdenticalTo',
            ] + $specs['3-int-keys'],

            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'method' => 'objectPropertiesEqualTo',
            ] + $specs['3-int-keys'],

            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'method' => 'classPropertiesIdenticalTo',
            ] + $specs['3-int-keys'],

            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'method' => 'classPropertiesEqualTo',
            ] + $specs['3-int-keys'],
        ];
    }

    /**
     * @dataProvider provConstraintWithInvalidExpectationSpec
     */
    public function testConstraintWithInvalidExpectationSpec(string $method, array $array, array $expect): array
    {
        self::expectException($expect['exception']);
        self::expectExceptionMessage($expect['message']);

        call_user_func([self::class, $method], $array);
    }

    public static function provAssertionWithIncompatibleValue(): array
    {
        return [
            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'method' => 'objectPropertiesIdenticalTo',
                'array' => ['foo' => 'FOO'],
                'value' => 123,
                'expect' => [
                    'exception' => ExpectationFailedException::class,
                    'message' => '/^Failed asserting that 123 is an object with properties identical to specified.$/',
                ],
            ],

            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'method' => 'objectPropertiesEqualTo',
                'array' => ['foo' => 'FOO'],
                'value' => 123,
                'expect' => [
                    'exception' => ExpectationFailedException::class,
                    'message' => '/^Failed asserting that 123 is an object with properties equal to specified.$/',
                ],
            ],

            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'method' => 'classPropertiesIdenticalTo',
                'array' => ['foo' => 'FOO'],
                'value' => 123,
                'expect' => [
                    'exception' => ExpectationFailedException::class,
                    'message' => '/^Failed asserting that 123 is a class with properties identical to specified.$/',
                ],
            ],

            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'method' => 'classPropertiesEqualTo',
                'array' => ['foo' => 'FOO'],
                'value' => 123,
                'expect' => [
                    'exception' => ExpectationFailedException::class,
                    'message' => '/^Failed asserting that 123 is a class with properties equal to specified.$/',
                ],
            ],
        ];
    }

    /**
     * @dataProvider provAssertionWithIncompatibleValue
     *
     * @param mixed $value
     */
    public function testAssertionWithIncompatibleValue(string $method, array $array, $value, array $expect): void
    {
        $matcher = call_user_func([self::class, $method], $array);

        self::expectException($expect['exception']);
        self::expectExceptionMessageMatches($expect['message']);

        self::assertThat($value, $matcher);
    }

    //
    // object
    //

    public static function provObjectPropertiesIdenticalTo(): array
    {
        $esmith = new class() {
            public $name = 'Emily';
            public $last = 'Smith';
            public $age = 20;
            public $husband;
            public $family = [];
            private $salary = 98;

            public function getSalary()
            {
                return $this->salary;
            }

            public function getDebit()
            {
                return -$this->salary;
            }

            public function marry($husband)
            {
                $this->husband = $husband;
                $this->family[] = $husband;
            }
        };

        $jsmith = new class() {
            public $name = 'John';
            public $last = 'Smith';
            public $age = 21;
            public $wife;
            public $family = [];
            private $salary = 123;

            public function getSalary()
            {
                return $this->salary;
            }

            public function getDebit()
            {
                return -$this->salary;
            }

            public function marry($wife)
            {
                $this->wife = $wife;
                $this->family[] = $wife;
            }
        };

        $esmith->marry($jsmith);
        $jsmith->marry($esmith);

        $registry = new class() {
            public $persons = [];
            public $families = [];

            public function addFamily(string $key, array $persons)
            {
                $this->families[$key] = $persons;
                $this->persons = array_merge($this->persons, $persons);
            }
        };

        $registry->addFamily('smith', [$esmith, $jsmith]);

        return [
            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'expect' => ['name' => 'John', 'last' => 'Smith', 'age' => 21, 'wife' => $esmith],
                'object' => $jsmith,
            ],

            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'expect' => [
                    'name' => 'John',
                    'last' => 'Smith',
                    'age' => 21,
                    'wife' => $esmith,
                ],
                'object' => $jsmith,
            ],

            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'expect' => ['name' => 'John', 'last' => 'Smith', 'age' => 21],
                'object' => $jsmith,
            ],

            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'expect' => ['name' => 'John', 'last' => 'Smith'],
                'object' => $jsmith,
            ],

            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'expect' => ['age' => 21],
                'object' => $jsmith,
            ],

            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'expect' => ['age' => 21, 'getSalary()' => 123, 'getDebit()' => -123],
                'object' => $jsmith,
            ],

            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'expect' => [
                    'name' => 'John',
                    'last' => 'Smith',
                    'age' => 21,
                    'wife' => self::objectPropertiesIdenticalTo([
                        'name' => 'Emily',
                        'last' => 'Smith',
                        'age' => 20,
                        'husband' => $jsmith,
                        'getSalary()' => 98,
                    ]),
                ],
                'object' => $jsmith,
            ],

            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'expect' => [
                    'name' => 'John',
                    'last' => 'Smith',
                    'age' => 21,
                    'wife' => self::objectPropertiesIdenticalTo([
                        'name' => 'Emily',
                        'last' => 'Smith',
                        'age' => 20,
                        'husband' => self::objectPropertiesIdenticalTo([
                            'name' => 'John',
                            'last' => 'Smith',
                            'age' => 21,
                            'getSalary()' => 123,
                        ]),
                        'getSalary()' => 98,
                    ]),
                ],
                'object' => $jsmith,
            ],

            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'expect' => [
                    'family' => [$esmith],
                ],
                'object' => $jsmith,
            ],

            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'expect' => [
                    'family' => [
                        self::objectPropertiesIdenticalTo(['name' => 'Emily', 'last' => 'Smith']),
                    ],
                ],
                'object' => $jsmith,
            ],

            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'expect' => [
                    'persons' => [
                        self::objectPropertiesIdenticalTo(['name' => 'Emily', 'last' => 'Smith']),
                        self::objectPropertiesIdenticalTo(['name' => 'John', 'last' => 'Smith']),
                    ],
                    'families' => [
                        'smith' => [
                            self::objectPropertiesIdenticalTo(['name' => 'Emily', 'last' => 'Smith']),
                            self::objectPropertiesIdenticalTo(['name' => 'John', 'last' => 'Smith']),
                        ],
                    ],
                ],
                'object' => $registry,
            ],

            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'expect' => [
                    'persons' => [
                        $esmith,
                        $jsmith,
                    ],
                    'families' => [
                        'smith' => [
                            $esmith,
                            $jsmith,
                        ],
                    ],
                ],
                'object' => $registry,
            ],
        ];
    }

    public static function provObjectPropertiesEqualButNotIdenticalTo(): array
    {
        $object = new class() {
            public $emptyString = '';
            public $null;
            public $string123 = '123';
            public $int321 = 321;
            public $boolFalse = false;
        };

        return [
            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'expect' => [
                    'emptyString' => null,
                    'null' => '',
                    'string123' => 123,
                    'int321' => '321',
                    'boolFalse' => 0,
                ],
                'object' => $object,
            ],
        ];
    }

    public static function provObjectPropertiesNotEqualTo(): array
    {
        $hbrown = new class() {
            public $name = 'Helen';
            public $last = 'Brown';
            public $age = 44;
        };

        $esmith = new class() {
            public $name = 'Emily';
            public $last = 'Smith';
            public $age = 20;
            public $husband;
            public $family = [];
            private $salary = 98;

            public function getSalary()
            {
                return $this->salary;
            }

            public function getDebit()
            {
                return -$this->salary;
            }

            public function marry($husband)
            {
                $this->husband = $husband;
                $this->family[] = $husband;
            }
        };

        $jsmith = new class() {
            public $name = 'John';
            public $last = 'Smith';
            public $age = 21;
            public $wife;
            public $family = [];
            private $salary = 123;

            public function getSalary()
            {
                return $this->salary;
            }

            public function getDebit()
            {
                return -$this->salary;
            }

            public function marry($wife)
            {
                $this->wife = $wife;
                $this->family[] = $wife;
            }
        };

        $esmith->marry($jsmith);
        $jsmith->marry($esmith);

        $registry = new class() {
            public $persons = [];
            public $families = [];

            public function addFamily(string $key, array $persons)
            {
                $this->families[$key] = $persons;
                $this->persons = array_merge($this->persons, $persons);
            }
        };

        $registry->addFamily('smith', [$esmith, $jsmith]);

        return [
            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'expect' => ['name' => 'John', 'last' => 'Brown', 'age' => 21],
                'object' => $jsmith,
            ],

            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'expect' => ['name' => 'John', 'last' => 'Smith', 'wife' => null],
                'object' => $jsmith,
            ],

            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'expect' => ['name' => 'John', 'last' => 'Smith', 'wife' => 'Emily'],
                'object' => $jsmith,
            ],

            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'expect' => ['name' => 'John', 'last' => 'Smith', 'wife' => $hbrown],
                'object' => $jsmith,
            ],

            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'expect' => ['name' => 'John', 'last' => 'Brown'],
                'object' => $jsmith,
            ],

            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'expect' => ['age' => 19],
                'object' => $jsmith,
            ],

            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'expect' => ['age' => 21, 'getSalary()' => 1230],
                'object' => $jsmith,
            ],

            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'expect' => [
                    'name' => 'John',
                    'last' => 'Smith',
                    'age' => 21,
                    'wife' => [
                        'name' => 'Emily',
                        'last' => 'Smith',
                        'age' => 20,
                        'husband' => [
                            'name' => 'John',
                            'last' => 'Smith',
                            'age' => 21,
                            'getSalary()' => 123,
                        ],
                        'getSalary()' => 98,
                    ],
                ],
                'object' => $jsmith,
            ],

            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'expect' => [
                    'family' => [
                        ['name' => 'Emily', 'last' => 'Smith'],
                    ],
                ],
                'object' => $jsmith,
            ],

            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'expect' => [
                    'persons' => [
                        ['name' => 'Emily', 'last' => 'Smith'],
                        ['name' => 'John', 'last' => 'Smith'],
                    ],
                    'families' => [
                        'smith' => [
                            ['name' => 'Emily', 'last' => 'Smith'],
                            ['name' => 'John', 'last' => 'Smith'],
                        ],
                    ],
                ],
                'object' => $registry,
            ],

            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'expect' => [
                    'persons' => [
                        $esmith,
                        $jsmith,
                    ],
                    // the following should not match as the 'families' property is an array, not an object.
                    'families' => self::objectPropertiesIdenticalTo([
                        'smith' => [
                            $esmith,
                            $jsmith,
                        ],
                    ]),
                ],
                'object' => $registry,
            ],
        ];
    }

    /**
     * @dataProvider provObjectPropertiesIdenticalTo
     */
    public function testObjectPropertiesIdenticalTo(array $expect, object $object)
    {
        self::assertThat($object, self::objectPropertiesIdenticalTo($expect));
    }

    /**
     * @dataProvider provObjectPropertiesNotEqualTo
     * @dataProvider provObjectPropertiesEqualButNotIdenticalTo
     */
    public function testLogicalNotObjectPropertiesIdenticalTo(array $expect, object $object)
    {
        self::assertThat($object, self::logicalNot(self::objectPropertiesIdenticalTo($expect)));
    }

    /**
     * @dataProvider provObjectPropertiesIdenticalTo
     */
    public function testAssertObjectPropertiesIdenticalTo(array $expect, object $object)
    {
        self::assertObjectPropertiesIdenticalTo($expect, $object);
    }

    /**
     * @dataProvider provObjectPropertiesNotEqualTo
     * @dataProvider provObjectPropertiesEqualButNotIdenticalTo
     */
    public function testAssertObjectPropertiesIdenticalToFails(array $expect, object $object)
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that object class\@.+ is an object '.
            'with properties identical to specified./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertObjectPropertiesIdenticalTo($expect, $object, 'Lorem ipsum.');
    }

    /**
     * @dataProvider provObjectPropertiesNotEqualTo
     */
    public function testAssertNotObjectPropertiesIdenticalTo(array $expect, object $object)
    {
        self::assertNotObjectPropertiesIdenticalTo($expect, $object);
    }

    /**
     * @dataProvider provObjectPropertiesIdenticalTo
     */
    public function testAssertNotObjectPropertiesIdenticalToFails(array $expect, object $object)
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that object class@.+ fails to be an object '.
            'with properties identical to specified./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertNotObjectPropertiesIdenticalTo($expect, $object, 'Lorem ipsum.');
    }

    /**
     * @dataProvider provObjectPropertiesIdenticalTo
     * @dataProvider provObjectPropertiesEqualButNotIdenticalTo
     */
    public function testObjectPropertiesEqualTo(array $expect, object $object)
    {
        self::assertThat($object, self::objectPropertiesEqualTo($expect));
    }

    /**
     * @dataProvider provObjectPropertiesNotEqualTo
     */
    public function testLogicalNotObjectPropertiesEqualTo(array $expect, object $object)
    {
        self::assertThat($object, self::logicalNot(self::objectPropertiesEqualTo($expect)));
    }

    /**
     * @dataProvider provObjectPropertiesIdenticalTo
     * @dataProvider provObjectPropertiesEqualButNotIdenticalTo
     */
    public function testAssertObjectPropertiesEqualTo(array $expect, object $object)
    {
        self::assertObjectPropertiesEqualTo($expect, $object);
    }

    /**
     * @dataProvider provObjectPropertiesNotEqualTo
     */
    public function testAssertObjectPropertiesEqualToFails(array $expect, object $object)
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that object class\@.+ is an object '.
            'with properties equal to specified./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertObjectPropertiesEqualTo($expect, $object, 'Lorem ipsum.');
    }

    /**
     * @dataProvider provObjectPropertiesNotEqualTo
     */
    public function testAssertNotObjectPropertiesEqualTo(array $expect, object $object)
    {
        self::assertNotObjectPropertiesEqualTo($expect, $object);
    }

    /**
     * @dataProvider provObjectPropertiesIdenticalTo
     * @dataProvider provObjectPropertiesEqualButNotIdenticalTo
     */
    public function testAssertNotObjectPropertiesEqualToFails(array $expect, object $object)
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that object class@.+ fails to be an object '.
            'with properties equal to specified./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertNotObjectPropertiesEqualTo($expect, $object, 'Lorem ipsum.');
    }

    //
    // class
    //

    public static function provClassPropertiesIdenticalTo(): array
    {
        return [
            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'expect' => [
                    'emptyString' => '',
                    'null' => null,
                    'string123' => '123',
                    'int321' => 321,
                    'boolFalse' => false,
                ],
                'class' => get_class(new class() {
                    public static $emptyString = '';
                    public static $null;
                    public static $string123 = '123';
                    public static $int321 = 321;
                    public static $boolFalse = false;
                }),
            ],
        ];
    }

    public static function provClassPropertiesEqualButNotIdenticalTo(): array
    {
        return [
            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'expect' => [
                    'emptyString' => null,
                    'null' => '',
                    'string123' => 123,
                    'int321' => '321',
                    'boolFalse' => 0,
                ],
                'class' => get_class(new class() {
                    public static $emptyString = '';
                    public static $null;
                    public static $string123 = '123';
                    public static $int321 = 321;
                    public static $boolFalse = false;
                }),
            ],
        ];
    }

    public static function provClassPropertiesNotEqualTo(): array
    {
        return [
            'PropertiesAssertionsTraitTest.php:'.__LINE__ => [
                'expect' => [
                    'emptyString' => 'foo',
                    'null' => 1,
                    'string123' => '321',
                    'int321' => 123,
                    'boolFalse' => true,
                ],
                'class' => get_class(new class() {
                    public static $emptyString = '';
                    public static $null;
                    public static $string123 = '123';
                    public static $int321 = 321;
                    public static $boolFalse = false;
                }),
            ],
        ];
    }

    /**
     * @dataProvider provClassPropertiesIdenticalTo
     */
    public function testClassPropertiesIdenticalTo(array $expect, string $class)
    {
        self::assertThat($class, self::classPropertiesIdenticalTo($expect));
    }

    /**
     * @dataProvider provClassPropertiesNotEqualTo
     * @dataProvider provClassPropertiesEqualButNotIdenticalTo
     */
    public function testLogicalNotClassPropertiesIdenticalTo(array $expect, string $class)
    {
        self::assertThat($class, self::logicalNot(self::classPropertiesIdenticalTo($expect)));
    }

    /**
     * @dataProvider provClassPropertiesIdenticalTo
     */
    public function testAssertClassPropertiesIdenticalTo(array $expect, string $class)
    {
        self::assertClassPropertiesIdenticalTo($expect, $class);
    }

    /**
     * @dataProvider provClassPropertiesNotEqualTo
     * @dataProvider provClassPropertiesEqualButNotIdenticalTo
     */
    public function testAssertClassPropertiesIdenticalToFails(array $expect, string $class)
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that .+ is a class '.
            'with properties identical to specified./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertClassPropertiesIdenticalTo($expect, $class, 'Lorem ipsum.');
    }

    /**
     * @dataProvider provClassPropertiesNotEqualTo
     */
    public function testAssertNotClassPropertiesIdenticalTo(array $expect, string $class)
    {
        self::assertNotClassPropertiesIdenticalTo($expect, $class);
    }

    /**
     * @dataProvider provClassPropertiesIdenticalTo
     */
    public function testAssertNotClassPropertiesIdenticalToFails(array $expect, string $class)
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that .+ fails to be a class '.
            'with properties identical to specified./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertNotClassPropertiesIdenticalTo($expect, $class, 'Lorem ipsum.');
    }

    /**
     * @dataProvider provClassPropertiesIdenticalTo
     * @dataProvider provClassPropertiesEqualButNotIdenticalTo
     */
    public function testClassPropertiesEqualTo(array $expect, string $class)
    {
        self::assertThat($class, self::classPropertiesEqualTo($expect));
    }

    /**
     * @dataProvider provClassPropertiesNotEqualTo
     */
    public function testLogicalNotClassPropertiesEqualTo(array $expect, string $class)
    {
        self::assertThat($class, self::logicalNot(self::classPropertiesEqualTo($expect)));
    }

    /**
     * @dataProvider provClassPropertiesIdenticalTo
     * @dataProvider provClassPropertiesEqualButNotIdenticalTo
     */
    public function testAssertClassPropertiesEqualTo(array $expect, string $class)
    {
        self::assertClassPropertiesEqualTo($expect, $class);
    }

    /**
     * @dataProvider provClassPropertiesNotEqualTo
     */
    public function testAssertClassPropertiesEqualToFails(array $expect, string $class)
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that .+ is a class '.
            'with properties equal to specified./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertClassPropertiesEqualTo($expect, $class, 'Lorem ipsum.');
    }

    /**
     * @dataProvider provClassPropertiesNotEqualTo
     */
    public function testAssertNotClassPropertiesEqualTo(array $expect, string $class)
    {
        self::assertNotClassPropertiesEqualTo($expect, $class);
    }

    /**
     * @dataProvider provClassPropertiesIdenticalTo
     * @dataProvider provClassPropertiesEqualButNotIdenticalTo
     */
    public function testAssertNotClassPropertiesEqualToFails(array $expect, string $class)
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that .+ fails to be a class '.
            'with properties equal to specified./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertNotClassPropertiesEqualTo($expect, $class, 'Lorem ipsum.');
    }

    //
    // misc.
    //

    public function testObjectPropertiesConstraintsWithAndOperator(): void
    {
        $this->assertThat(
            new class() {
                public $foo = 'FOO';
                public $bar = '';
            },
            $this->logicalAnd(
                $this->objectPropertiesIdenticalTo([
                    'foo' => 'FOO',
                ]),
                $this->objectPropertiesEqualTo([
                    'bar' => null,
                ])
            )
        );
    }

    public function testObjectPropertiesConstraintsWithAndOperatorFails(): void
    {
        $regexp = '/is an object with properties identical to specified'.
            ' and is an object with properties equal to specified/';

        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        $this->assertThat(
            new class() {
                public $foo = '';
                public $bar = 'BAR';
            },
            $this->logicalAnd(
                $this->objectPropertiesIdenticalTo([
                    'foo' => 'FOO',
                ]),
                $this->objectPropertiesEqualTo([
                    'bar' => null,
                ])
            )
        );
    }

    // for full coverage of failureDescriptionInContext()
    public function testFailureDescriptionOfCustomUnaryOperator(): void
    {
        $constraint = $this->objectPropertiesIdenticalTo([
            'foo' => 'FOO',
        ]);

        $unary = $this->getMockBuilder(UnaryOperator::class)
            ->setConstructorArgs([$constraint])
            ->getMockForAbstractClass()
        ;

        $unary->expects($this->any())
            ->method('operator')
            ->willReturn('!')
        ;
        $unary->expects($this->any())
            ->method('precedence')
            ->willReturn(1)
        ;

        $regexp = '/is an object with properties identical to specified/';

        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        $this->assertThat(new class() {
        }, $unary);
    }
}

// vim: syntax=php sw=4 ts=4 et:
