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
use Korowai\Testing\Assertions\ObjectPropertiesAssertionsTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Assertions\ObjectPropertiesAssertionsTrait
 */
final class ObjectPropertiesAssertionsTraitTest extends TestCase
{
    use ObjectPropertiesAssertionsTrait;

    public function staticMethodsThatMustAppear()
    {
        return [
            ['assertObjectHasPropertiesSameAs'],
            ['assertNotObjectHasPropertiesSameAs'],
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

    public static function prov__propertiesSameAs()
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
            [
                'expect'  => ['name' => 'John', 'last' => 'Smith', 'age' => 21, 'wife' => $esmith],
                'object'  => $jsmith
            ],
            [
                'expect'  => [
                    'name' => 'John',
                    'last' => 'Smith',
                    'age' => 21,
                    'wife' => $esmith,
                ],
                'object'  => $jsmith
            ],
            [
                'expect'  => ['name' => 'John', 'last' => 'Smith', 'age' => 21],
                'object'  => $jsmith
            ],
            [
                'expect'  => ['name' => 'John', 'last' => 'Smith'],
                'object'  => $jsmith
            ],
            [
                'expect'  => ['age' => 21],
                'object'  => $jsmith
            ],
            [
                'expect'  => ['age' => 21, 'getSalary()' => 123, 'getDebit()' => -123],
                'object'  => $jsmith
            ],
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
            [
                'expect' => [
                    'family' => [ $esmith ],
                ],
                'object' => $jsmith
            ],
            [
                'expect' => [
                    'family' => [
                        self::objectHasPropertiesIdenticalTo(['name' => 'Emily', 'last' => 'Smith']),
                    ],
                ],
                'object' => $jsmith
            ],
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

    public static function prov__propertiesNotSameAs()
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
            [
                'expect' => ['name' => 'John', 'last' => 'Brown', 'age' => 21],
                'object' => $jsmith
            ],
            [
                'expect' => ['name' => 'John', 'last' => 'Smith', 'wife' => null],
                'object' => $jsmith
            ],
            [
                'expect' => ['name' => 'John', 'last' => 'Smith', 'wife' => 'Emily'],
                'object' => $jsmith
            ],
            [
                'expect' => ['name' => 'John', 'last' => 'Smith', 'wife' => $hbrown],
                'object' => $jsmith
            ],
            [
                'expect' => ['name' => 'John', 'last' => 'Brown'],
                'object' => $jsmith
            ],
            [
                'expect' => ['age' => 19],
                'object' => $jsmith
            ],
            [
                'expect' => ['age' => 21, 'getSalary()' => 1230],
                'object' => $jsmith
            ],
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
            [
                'expect' => [
                    'family' => [
                        ['name' => 'Emily', 'last' => 'Smith'],
                    ],
                ],
                'object' => $jsmith
            ],
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
            ]
        ];
    }

    /**
     * @dataProvider prov__propertiesSameAs
     */
    public function test__objectHasPropertiesIdenticalTo__withMatchingProperties(array $expect, object $object) {
        self::assertTrue(self::objectHasPropertiesIdenticalTo($expect)->matches($object));
    }

    /**
     * @dataProvider prov__propertiesNotSameAs
     */
    public function test__objectHasPropertiesIdenticalTo__withNonMatchingProperties(array $expected, object $object) {
        self::assertFalse(self::objectHasPropertiesIdenticalTo($expected)->matches($object));
    }

    public function test__objectHasPropertiesIdenticalTo__withNonObject() : void
    {
        $matcher = self::objectHasPropertiesIdenticalTo(['a' => 'A']);
        self::assertFalse($matcher->matches(123));

        $regexp = '/^123 has required properties with prescribed values$/';
        if (method_exists(self::class, 'assertMatchesRegularExpression')) {
            self::assertMatchesRegularExpression($regexp, $matcher->failureDescription(123));
        } else {
            self::assertRegExp($regexp, $matcher->failureDescription(123));
        }
    }

    public function test__objectHasPropertiesIdenticalTo__withInvalidArray() : void
    {
        self::expectException(\PHPUnit\Framework\Exception::class);
        self::expectExceptionMessage('The array of expected properties contains 3 invalid key(s)');

        self::objectHasPropertiesIdenticalTo(['a' => 'A', 0 => 'B', 2 => 'C', 7 => 'D', 'e' => 'E']);
    }

    public function test__objectHasPropertiesIdenticalTo__withInvalidGetter() : void
    {
        $object = new class {
            protected $a;
        };

        self::expectException(\PHPUnit\Framework\Exception::class);
        self::expectExceptionMessage('$object->xxx() is not callable');

        self::objectHasPropertiesIdenticalTo(['xxx()' => 'A'])->matches($object);
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
     * @dataProvider prov__propertiesSameAs
     */
    public function test__assertObjectHasPropertiesSameAs__withMatchingProperties(array $expected, object $object) {
        self::assertObjectHasPropertiesSameAs(...(self::adjustCase(func_get_args())));
    }

    /**
     * @dataProvider prov__propertiesNotSameAs
     */
    public function test__assertObjectHasPropertiesSameAs__withNonMatchingProperties(array $expected, object $object) {
        $regexp = '/^Lorem ipsum.\n'.
                    'Failed asserting that object class\@.+ has required properties with prescribed values/';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertObjectHasPropertiesSameAs(...(self::adjustCase(func_get_args(), 'Lorem ipsum.')));
    }

    /**
     * @dataProvider prov__propertiesNotSameAs
     */
    public function test__assertNotObjectHasPropertiesSameAs__withNonMatchingProperties(array $expected, object $object) {
        self::assertNotObjectHasPropertiesSameAs(...(self::adjustCase(func_get_args())));
    }

    /**
     * @dataProvider prov__propertiesSameAs
     */
    public function test__assertNotObjectHasPropertiesSameAs__whithMatchingProperties(array $expected, object $object) {
        $regexp = '/^Lorem ipsum.\n'.
                    'Failed asserting that object class@.+ does not have required properties with prescribed values/';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertNotObjectHasPropertiesSameAs(...(self::adjustCase(func_get_args(), 'Lorem ipsum.')));
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
