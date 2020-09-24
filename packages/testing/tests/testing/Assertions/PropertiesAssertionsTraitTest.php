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

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\ExpectationFailedException;
use Korowai\Testing\Assertions\PropertiesAssertionsTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Assertions\PropertiesAssertionsTrait
 */
final class PropertiesAssertionsTraitTest extends TestCase
{
    use PropertiesAssertionsTrait;

    public function staticMethodsThatMustAppear()
    {
        return [
            ['assertObjectHasPropertiesIdenticalTo'],
            ['assertNotObjectHasPropertiesIdenticalTo'],
            ['objectHasPropertiesIdenticalTo'],
        ];
    }

    /**
     * @dataProvider staticMethodsThatMustAppear
     */
    public function test__staticMethodExists(string $name) : void
    {
        $classAndMethod = [self::class, $name];
        self::assertTrue(method_exists(...$classAndMethod));
        $method = new \ReflectionMethod(...$classAndMethod);
        self::assertTrue($method->isStatic());
    }

    public static function prov__matchingProperties()
    {
        $esmith = new class {
            public $name = 'Emily';
            public $last = 'Smith';
            public $age = 20;
            public $husband = null;
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

        $jsmith = new class {
            public $name = 'John';
            public $last = 'Smith';
            public $age = 21;
            public $wife = null;
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

        $registry = new class {
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
                'expect'  => ['name' => 'John', 'last' => 'Smith', 'age' => 21, 'wife' => $esmith],
                'object'  => $jsmith
            ],

            // #1
            [
                'expect'  => [
                    'name' => 'John',
                    'last' => 'Smith',
                    'age' => 21,
                    'wife' => $esmith,
                ],
                'object'  => $jsmith
            ],

            // #2
            [
                'expect'  => ['name' => 'John', 'last' => 'Smith', 'age' => 21],
                'object'  => $jsmith
            ],

            // #3
            [
                'expect'  => ['name' => 'John', 'last' => 'Smith'],
                'object'  => $jsmith
            ],

            // #4
            [
                'expect'  => ['age' => 21],
                'object'  => $jsmith
            ],

            // #5
            [
                'expect'  => ['age' => 21, 'getSalary()' => 123, 'getDebit()' => -123],
                'object'  => $jsmith
            ],

            // #6
            [
                'expect'  => [
                    'name' => 'John',
                    'last' => 'Smith',
                    'age' => 21,
                    'wife' => self::objectHasPropertiesIdenticalTo([
                        'name' => 'Emily',
                        'last' => 'Smith',
                        'age' => 20,
                        'husband' => $jsmith,
                        'getSalary()' => 98
                    ])
                ],
                'object'  => $jsmith
            ],

            // #7
            [
                'expect'  => [
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
                        'getSalary()' => 98
                    ])
                ],
                'object'  => $jsmith
            ],

            // #8
            [
                'expect' => [
                    'family' => [ $esmith ],
                ],
                'object' => $jsmith
            ],

            // #9
            [
                'expect' => [
                    'family' => [
                        self::objectHasPropertiesIdenticalTo(['name' => 'Emily', 'last' => 'Smith']),
                    ],
                ],
                'object' => $jsmith
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
                        ]
                    ]
                ],
                'object' => $registry
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
                        ]
                    ]
                ],
                'object' => $registry
            ],
        ];
    }

    /**
     * @dataProvider prov__matchingProperties
     */
    public function test__objectHasPropertiesIdenticalTo__withMatchingProperties(array $expect, object $object) {
        self::assertThat($object, self::objectHasPropertiesIdenticalTo($expect));
    }

    public static function prov__nonMatchingProperties()
    {
        $hbrown = new class {
            public $name = 'Helen';
            public $last = 'Brown';
            public $age = 44;
        };

        $esmith = new class {
            public $name = 'Emily';
            public $last = 'Smith';
            public $age = 20;
            public $husband = null;
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

        $jsmith = new class {
            public $name = 'John';
            public $last = 'Smith';
            public $age = 21;
            public $wife = null;
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

        $registry = new class {
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
                'object' => $jsmith
            ],

            // #1
            [
                'expect' => ['name' => 'John', 'last' => 'Smith', 'wife' => null],
                'object' => $jsmith
            ],

            // #2
            [
                'expect' => ['name' => 'John', 'last' => 'Smith', 'wife' => 'Emily'],
                'object' => $jsmith
            ],

            // #3
            [
                'expect' => ['name' => 'John', 'last' => 'Smith', 'wife' => $hbrown],
                'object' => $jsmith
            ],

            // #4
            [
                'expect' => ['name' => 'John', 'last' => 'Brown'],
                'object' => $jsmith
            ],

            // #5
            [
                'expect' => ['age' => 19],
                'object' => $jsmith
            ],

            // #6
            [
                'expect' => ['age' => 21, 'getSalary()' => 1230],
                'object' => $jsmith
            ],

            // #7
            [
                'expect'  => [
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
                        'getSalary()' => 98
                    ]
                ],
                'object'  => $jsmith
            ],

            // #8
            [
                'expect' => [
                    'family' => [
                        ['name' => 'Emily', 'last' => 'Smith'],
                    ],
                ],
                'object' => $jsmith
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
                        ]
                    ]
                ],
                'object' => $registry
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
                        ]
                    ]),
                ],
                'object' => $registry
            ],
        ];
    }

    /**
     * @dataProvider prov__nonMatchingProperties
     */
    public function test__objectHasPropertiesIdenticalTo__withNonMatchingProperties(array $expect, object $object) {
        self::assertThat($object, self::logicalNot(self::objectHasPropertiesIdenticalTo($expect)));
    }

    public function test__objectHasPropertiesIdenticalTo__withNonObject() : void
    {
        $matcher = self::objectHasPropertiesIdenticalTo(['a' => 'A']);
        $expectedMessage = '/^Failed asserting that 123 is an object '.
            'with selected properties identical to given ones.$/';

        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($expectedMessage);

        self::assertThat(123, $matcher);
    }

    public function test__objectHasPropertiesIdenticalTo__withInvalidArray() : void
    {
        self::expectException(\PHPUnit\Framework\Exception::class);
        self::expectExceptionMessage('The array of expected properties contains 3 invalid key(s)');

        self::objectHasPropertiesIdenticalTo(['a' => 'A', 0 => 'B', 2 => 'C', 7 => 'D', 'e' => 'E']);
    }

    protected static function adjustCase(array $case, string $message = '')
    {
        $args = func_get_args();
        if (is_callable($case[2] ?? null)) {
            $case[] = $case[2];
            $case[2] = $args[1] ?? '';
        } elseif (($msg = $args[1] ?? null) !== null) {
            $case[2] = $msg;
        }
        return $case;
    }

    /**
     * @dataProvider prov__matchingProperties
     */
    public function test__assertObjectHasPropertiesIdenticalTo__withMatchingProperties(array $expect, object $object) {
        self::assertObjectHasPropertiesIdenticalTo(...(self::adjustCase(func_get_args())));
    }

    /**
     * @dataProvider prov__nonMatchingProperties
     */
    public function test__assertObjectHasPropertiesIdenticalTo__withNonMatchingProperties(array $expect, object $object) {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that object class\@.+ is an object '.
            'with selected properties identical to given ones./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertObjectHasPropertiesIdenticalTo(...(self::adjustCase(func_get_args(), 'Lorem ipsum.')));
    }

    /**
     * @dataProvider prov__nonMatchingProperties
     */
    public function test__assertNotObjectHasPropertiesIdenticalTo__withNonMatchingProperties(array $expect, object $object) {
        self::assertNotObjectHasPropertiesIdenticalTo(...(self::adjustCase(func_get_args())));
    }

    /**
     * @dataProvider prov__matchingProperties
     */
    public function test__assertNotObjectHasPropertiesIdenticalTo__withMatchingProperties(array $expect, object $object) {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that object class@.+ fails to be an object '.
            'with selected properties identical to given ones./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertNotObjectHasPropertiesIdenticalTo(...(self::adjustCase(func_get_args(), 'Lorem ipsum.')));
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
