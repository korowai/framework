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
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Assertions\PropertiesAssertionsTrait
 * @covers \Korowai\Testing\Constraint\AbstractPropertiesComparator
 * @covers \Korowai\Testing\Constraint\ClassHasPropertiesEqualTo
 * @covers \Korowai\Testing\Constraint\ClassHasPropertiesIdenticalTo
 * @covers \Korowai\Testing\Constraint\ObjectHasPropertiesEqualTo
 * @covers \Korowai\Testing\Constraint\ObjectHasPropertiesIdenticalTo
 *
 * @internal
 */
final class PropertiesAssertionsTraitTest extends TestCase
{
    use PropertiesAssertionsTrait;

    public function provStaticMethodExists(): array
    {
        return [
            ['assertClassHasPropertiesEqualTo'],
            ['assertClassHasPropertiesIdenticalTo'],
            ['assertObjectHasPropertiesEqualTo'],
            ['assertObjectHasPropertiesIdenticalTo'],
            ['assertNotClassHasPropertiesEqualTo'],
            ['assertNotClassHasPropertiesIdenticalTo'],
            ['assertNotObjectHasPropertiesEqualTo'],
            ['assertNotObjectHasPropertiesIdenticalTo'],
            ['classHasPropertiesEqualTo'],
            ['classHasPropertiesIdenticalTo'],
            ['objectHasPropertiesEqualTo'],
            ['objectHasPropertiesIdenticalTo'],
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
            // #0
            [
                'expect' => ['name' => 'John', 'last' => 'Smith', 'age' => 21, 'wife' => $esmith],
                'object' => $jsmith,
            ],

            // #1
            [
                'expect' => [
                    'name' => 'John',
                    'last' => 'Smith',
                    'age' => 21,
                    'wife' => $esmith,
                ],
                'object' => $jsmith,
            ],

            // #2
            [
                'expect' => ['name' => 'John', 'last' => 'Smith', 'age' => 21],
                'object' => $jsmith,
            ],

            // #3
            [
                'expect' => ['name' => 'John', 'last' => 'Smith'],
                'object' => $jsmith,
            ],

            // #4
            [
                'expect' => ['age' => 21],
                'object' => $jsmith,
            ],

            // #5
            [
                'expect' => ['age' => 21, 'getSalary()' => 123, 'getDebit()' => -123],
                'object' => $jsmith,
            ],

            // #6
            [
                'expect' => [
                    'name' => 'John',
                    'last' => 'Smith',
                    'age' => 21,
                    'wife' => self::objectHasPropertiesIdenticalTo([
                        'name' => 'Emily',
                        'last' => 'Smith',
                        'age' => 20,
                        'husband' => $jsmith,
                        'getSalary()' => 98,
                    ]),
                ],
                'object' => $jsmith,
            ],

            // #7
            [
                'expect' => [
                    'name' => 'John',
                    'last' => 'Smith',
                    'age' => 21,
                    'wife' => self::objectHasPropertiesIdenticalTo([
                        'name' => 'Emily',
                        'last' => 'Smith',
                        'age' => 20,
                        'husband' => self::objectHasPropertiesIdenticalTo([
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

            // #8
            [
                'expect' => [
                    'family' => [$esmith],
                ],
                'object' => $jsmith,
            ],

            // #9
            [
                'expect' => [
                    'family' => [
                        self::objectHasPropertiesIdenticalTo(['name' => 'Emily', 'last' => 'Smith']),
                    ],
                ],
                'object' => $jsmith,
            ],

            // #10
            [
                'expect' => [
                    'persons' => [
                        self::objectHasPropertiesIdenticalTo(['name' => 'Emily', 'last' => 'Smith']),
                        self::objectHasPropertiesIdenticalTo(['name' => 'John', 'last' => 'Smith']),
                    ],
                    'families' => [
                        'smith' => [
                            self::objectHasPropertiesIdenticalTo(['name' => 'Emily', 'last' => 'Smith']),
                            self::objectHasPropertiesIdenticalTo(['name' => 'John', 'last' => 'Smith']),
                        ],
                    ],
                ],
                'object' => $registry,
            ],

            // #11
            [
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

    public static function provObjectPropertiesNotIdenticalTo(): array
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
            // #0
            [
                'expect' => ['name' => 'John', 'last' => 'Brown', 'age' => 21],
                'object' => $jsmith,
            ],

            // #1
            [
                'expect' => ['name' => 'John', 'last' => 'Smith', 'wife' => null],
                'object' => $jsmith,
            ],

            // #2
            [
                'expect' => ['name' => 'John', 'last' => 'Smith', 'wife' => 'Emily'],
                'object' => $jsmith,
            ],

            // #3
            [
                'expect' => ['name' => 'John', 'last' => 'Smith', 'wife' => $hbrown],
                'object' => $jsmith,
            ],

            // #4
            [
                'expect' => ['name' => 'John', 'last' => 'Brown'],
                'object' => $jsmith,
            ],

            // #5
            [
                'expect' => ['age' => 19],
                'object' => $jsmith,
            ],

            // #6
            [
                'expect' => ['age' => 21, 'getSalary()' => 1230],
                'object' => $jsmith,
            ],

            // #7
            [
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

            // #8
            [
                'expect' => [
                    'family' => [
                        ['name' => 'Emily', 'last' => 'Smith'],
                    ],
                ],
                'object' => $jsmith,
            ],

            // #9
            [
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

            // #10
            [
                'expect' => [
                    'persons' => [
                        $esmith,
                        $jsmith,
                    ],
                    // the following should not match as the 'families' property is an array, not an object.
                    'families' => self::objectHasPropertiesIdenticalTo([
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
    public function testObjectHasPropertiesIdenticalTo(array $expect, object $object)
    {
        self::assertThat($object, self::objectHasPropertiesIdenticalTo($expect));
    }

    /**
     * @dataProvider provObjectPropertiesNotIdenticalTo
     */
    public function testLogicalNotObjectHasPropertiesIdenticalTo(array $expect, object $object)
    {
        self::assertThat($object, self::logicalNot(self::objectHasPropertiesIdenticalTo($expect)));
    }

    public function testObjectHasPropertiesIdenticalToWithNonObject(): void
    {
        $matcher = self::objectHasPropertiesIdenticalTo(['a' => 'A']);
        $expectedMessage = '/^Failed asserting that 123 is an object '.
            'with properties identical to specified.$/';

        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($expectedMessage);

        self::assertThat(123, $matcher);
    }

    public function testObjectHasPropertiesIdenticalToWithInvalidArray(): void
    {
        self::expectException(\PHPUnit\Framework\Exception::class);
        self::expectExceptionMessage('The array of expected properties contains 3 invalid key(s)');

        self::objectHasPropertiesIdenticalTo(['a' => 'A', 0 => 'B', 2 => 'C', 7 => 'D', 'e' => 'E']);
    }

    /**
     * @dataProvider provObjectPropertiesIdenticalTo
     */
    public function testAssertObjectHasPropertiesIdenticalTo(array $expect, object $object)
    {
        self::assertObjectHasPropertiesIdenticalTo($expect, $object);
    }

    /**
     * @dataProvider provObjectPropertiesNotIdenticalTo
     */
    public function testAssertObjectHasPropertiesIdenticalToFails(array $expect, object $object)
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that object class\@.+ is an object '.
            'with properties identical to specified./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertObjectHasPropertiesIdenticalTo($expect, $object, 'Lorem ipsum.');
    }

    /**
     * @dataProvider provObjectPropertiesNotIdenticalTo
     */
    public function testAssertNotObjectHasPropertiesIdenticalTo(array $expect, object $object)
    {
        self::assertNotObjectHasPropertiesIdenticalTo($expect, $object);
    }

    /**
     * @dataProvider provObjectPropertiesIdenticalTo
     */
    public function testAssertNotObjectHasPropertiesIdenticalToFails(array $expect, object $object)
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that object class@.+ fails to be an object '.
            'with properties identical to specified./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertNotObjectHasPropertiesIdenticalTo($expect, $object, 'Lorem ipsum.');
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
