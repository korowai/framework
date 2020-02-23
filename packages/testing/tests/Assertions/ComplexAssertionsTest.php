<?php
/**
 * @file tests/Assertions/ComplexAssertionsTest.php
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
use Korowai\Testing\Assertions\ComplexAssertions;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ComplexAssertionsTest extends TestCase
{
    use ComplexAssertions;

    // Required by the trait.
    public static function getObjectProperty(object $object, string $key, array $getters = null)
    {
        $getter = substr($key, -2) === '()' ? substr($key, 0, -2) : $getters[$key] ?? null;
        if ($getter !== null) {
            return call_user_func([$object, $getter]);
        } else {
            return $object->{$key};
        }
    }

    public function staticMethodsThatMustAppear()
    {
        return [
            ['assertObjectEachProperty'],
            ['assertObjectEachPropertyArrayValue'],
            ['assertArrayEachValue'],
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

    public function test__assertObjectEachProperty()
    {
        $this->markTestIncomplete('The test is not implemented yet!');
    }

    public function test__assertObjectEachPropertyArrayValue()
    {
        $this->markTestIncomplete('The test is not implemented yet!');
    }

    public function test__assertArrayEachValue()
    {
        $this->markTestIncomplete('The test is not implemented yet!');
    }
}

// vim: syntax=php sw=4 ts=4 et:
