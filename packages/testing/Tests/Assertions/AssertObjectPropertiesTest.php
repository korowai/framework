<?php
/**
 * @file Tests/Assertions/ObjectPropertiesAssertionsTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\testing
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
        $this->assertTrue(method_exists(...$classAndMethod));
        $method = new \ReflectionMethod(...$classAndMethod);
        $this->assertTrue($method->isStatic());
    }

    public function propertiesSameAs()
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
            [['age' => 21, 'salary' => 123],                     $jsmith, ['getters' => ['salary' => 'getSalary']] ],
        ];
    }

    public function propertiesNotSameAs()
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
            [['age' => 21, 'salary' => 1230],                    $jsmith, ['getters' => ['salary' => 'getSalary']] ],
        ];
    }

    /**
     * @dataProvider propertiesSameAs
     */
    public function test__hasPropertiesIdenticalTo__withMatchingProperties(
        array $expected,
        object $object,
        array $options = []
    ) {
        $this->assertTrue($this->hasPropertiesIdenticalTo($expected, $options)->matches($object));
    }

    /**
     * @dataProvider propertiesNotSameAs
     */
    public function test__hasPropertiesIdenticalTo__withNonMatchingProperties(
        array $expected,
        object $object,
        array $options = []
    ) {
        $this->assertFalse($this->hasPropertiesIdenticalTo($expected, $options)->matches($object));
    }

    public function test__hasPropertiesIdenticalTo__withNonObject()
    {
        $matcher = $this->hasPropertiesIdenticalTo(['a' => 'A']);
        $this->assertFalse($matcher->matches(123));
        $this->assertSame('foo', $matcher->failureDescription(123));
    }

    public function test__hasPropertiesIdenticalTo__withInvalidArray()
    {
        $this->expectException(\PHPUnit\Framework\Exception::class);
        $this->expectExceptionMessage('The array of expected properties contains 3 invalid key(s)');

        $this->hasPropertiesIdenticalTo(['a' => 'A', 0 => 'B', 2 => 'C', 7 => 'D', 'e' => 'E']);
    }

    /**
     * @dataProvider propertiesSameAs
     */
    public function test__assertHasPropertiesSameAs__withMatchingProperties(
        array $expected,
        object $object,
        array $options = null
    ) {
        $this->assertHasPropertiesSameAs(...func_get_args());
    }

    /**
     * @dataProvider propertiesNotSameAs
     */
    public function test__assertHasPropertiesSameAs__withNonMatchingProperties(
        array $expected,
        object $object,
        array $options = null
    ) {
        $regexp = '/^Failed asserting that object with properties [\S\s\n]+has properties identical to/';
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageRegexp($regexp);

        $this->assertHasPropertiesSameAs(...func_get_args());
    }

    /**
     * @dataProvider propertiesNotSameAs
     */
    public function test__assertHasPropertiesNotSameAs__withNonMatchingProperties(
        array $expected,
        object $object,
        array $options = null
    ) {
        $this->assertHasPropertiesNotSameAs(...func_get_args());
    }

    /**
     * @dataProvider propertiesSameAs
     */
    public function test__assertHasPropertiesNotSameAs__whithMatchingProperties(
        array $expected,
        object $object,
        array $options = null
    ) {
        $regexp = '/^Failed asserting that object with properties [\S\s\n]+does not have properties identical to/';
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageRegexp($regexp);

        $this->assertHasPropertiesNotSameAs(...func_get_args());
    }
}

// vim: syntax=php sw=4 ts=4 et:
