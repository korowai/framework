<?php
/**
 * @file tests/Assertions/ObjectPropertiesAssertionsTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/testing
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\Assertions;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\ExpectationFailedException;
use Korowai\Testing\Assertions\ObjectPropertiesAssertions;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ObjectPropertiesAssertionsTest extends TestCase
{
    use ObjectPropertiesAssertions;

    public function staticMethodsThatMustAppear()
    {
        return [
            ['assertHasPropertiesSameAs'],
            ['assertHasPropertiesNotSameAs'],
            ['hasPropertiesIdenticalTo']
        ];
    }

    /**
     * @dataProvider staticMethodsThatMustAppear
     */
    public function test__staticMethodExists(string $name)
    {
        $classAndMethod = [self::class, $name];
        self::assertTrue(method_exists(...$classAndMethod));
        $method = new \ReflectionMethod(...$classAndMethod);
        self::assertTrue($method->isStatic());
    }

    public static function propertiesSameAs__cases()
    {
        $jsmith = new class {
            public $name = 'John';
            public $last = 'Smith';
            public $age = 21;
            private $salary = 123;
            public function getSalary() { return $this->salary; }
        };

        return [
            [['name' => 'John', 'last' => 'Smith', 'age' => 21], $jsmith],
            [['name' => 'John', 'last' => 'Smith'],              $jsmith],
            [['age' => 21],                                      $jsmith],
            [['age' => 21, 'salary' => 123],                     $jsmith, ['salary' => 'getSalary']],
            [['age' => 21, 'getSalary()' => 123],                $jsmith],
        ];
    }

    public static function propertiesNotSameAs__cases()
    {
        $jsmith = new class {
            public $name = 'John';
            public $last = 'Smith';
            public $age = 21;
            private $salary = 123;
            public function getSalary() { return $this->salary; }
        };

        return [
            [['name' => 'John', 'last' => 'Brown', 'age' => 21], $jsmith],
            [['name' => 'John', 'last' => 'Brown'],              $jsmith],
            [['age' => 19],                                      $jsmith],
            [['age' => 21, 'salary' => 1230],                    $jsmith, ['salary' => 'getSalary'] ],
            [['age' => 21, 'getSalary()' => 1230],               $jsmith],
        ];
    }

    /**
     * @dataProvider propertiesSameAs__cases
     */
    public function test__hasPropertiesIdenticalTo__withMatchingProperties(
        array $expected,
        object $object,
        array $getters = []
    ) {
        self::assertTrue(self::hasPropertiesIdenticalTo($expected, $getters)->matches($object));
    }

    /**
     * @dataProvider propertiesNotSameAs__cases
     */
    public function test__hasPropertiesIdenticalTo__withNonMatchingProperties(
        array $expected,
        object $object,
        array $getters = []
    ) {
        self::assertFalse(self::hasPropertiesIdenticalTo($expected, $getters)->matches($object));
    }

    public function test__hasPropertiesIdenticalTo__withNonObject()
    {
        $matcher = self::hasPropertiesIdenticalTo(['a' => 'A']);
        self::assertFalse($matcher->matches(123));
        self::assertRegexp('/^123 has required properties with prescribed values$/', $matcher->failureDescription(123));
    }

    public function test__hasPropertiesIdenticalTo__withInvalidArray()
    {
        self::expectException(\PHPUnit\Framework\Exception::class);
        self::expectExceptionMessage('The array of expected properties contains 3 invalid key(s)');

        self::hasPropertiesIdenticalTo(['a' => 'A', 0 => 'B', 2 => 'C', 7 => 'D', 'e' => 'E']);
    }

    public function test__hasPropertiesIdenticalTo__withInvalidGetter()
    {
        $object = new class { protected $a; };

        self::expectException(\PHPUnit\Framework\Exception::class);
        self::expectExceptionMessage('$object->xxx() is not callable');

        self::hasPropertiesIdenticalTo(['xxx()' => 'A'])->matches($object);
    }

    public function test__hasPropertiesIdenticalTo__withInvalidGetterOption()
    {
        $object = new class { protected $a; };

        self::expectException(\PHPUnit\Framework\Exception::class);
        self::expectExceptionMessage('$object->xxx() is not callable');

        self::hasPropertiesIdenticalTo(['a' => 'A'], ['a' => 'xxx'])->matches($object);
    }

    protected static function adjustCaseForAssert(array $case)
    {
        if (is_array($case[2] ?? null)) {
            $case[2] = ['getters' => $case[2]];
        }
        return $case;
    }

    /**
     * @dataProvider propertiesSameAs__cases
     */
    public function test__assertHasPropertiesSameAs__withMatchingProperties(
        array $expected,
        object $object,
        array $getters = null
    ) {
        self::assertHasPropertiesSameAs(...(self::adjustCaseForAssert(func_get_args())));
    }

    /**
     * @dataProvider propertiesNotSameAs__cases
     */
    public function test__assertHasPropertiesSameAs__withNonMatchingProperties(
        array $expected,
        object $object,
        array $options = null
    ) {
        $regexp = '/^Failed asserting that object class\@.+ has required properties with prescribed values/';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertHasPropertiesSameAs(...(self::adjustCaseForAssert(func_get_args())));
    }

    /**
     * @dataProvider propertiesNotSameAs__cases
     */
    public function test__assertHasPropertiesNotSameAs__withNonMatchingProperties(
        array $expected,
        object $object,
        array $options = null
    ) {
        self::assertHasPropertiesNotSameAs(...(self::adjustCaseForAssert(func_get_args())));
    }

    /**
     * @dataProvider propertiesSameAs__cases
     */
    public function test__assertHasPropertiesNotSameAs__whithMatchingProperties(
        array $expected,
        object $object,
        array $options = null
    ) {
        $regexp = '/^Failed asserting that object class@.+ does not have required properties with prescribed values/';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertHasPropertiesNotSameAs(...(self::adjustCaseForAssert(func_get_args())));
    }
}

// vim: syntax=php sw=4 ts=4 et:
