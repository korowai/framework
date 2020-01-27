<?php
/**
 * @file Tests/Contracts/PackageDetailsTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Contracts;

use PHPUnit\Framework\TestCase;
use Korowai\Testing\Contracts\PackageDetails;
use Korowai\Testing\Assertions\ClassAssertions;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class PackageDetailsTest extends TestCase
{
    use ClassAssertions;

    protected static $unmockable = [
        \Korowai\Lib\Ldif\ParserErrorInterface::class => true,
    ];

    public static function excludeUnmockable(array $interfaces)
    {
        return array_filter($interfaces, function ($value, $class) {
            return !(static::$unmockable[$class] ?? false);
        }, ARRAY_FILTER_USE_BOTH);
    }

    public function test__classesDetails()
    {
        $this->markTestIncomplete('This test has not been implemented yet');
    }

    public function test__objectProperties()
    {
        $packageDetails = PackageDetails::getInstance();
        $objectPropertiesMap = self::excludeUnmockable($packageDetails->objectPropertiesMap());
        foreach ($objectPropertiesMap as $class => $properties) {
            $mock = $this->getMockBuilder($class)
                         ->getMockForAbstractClass();
            foreach ($properties as $property => $getter) {
                $msg = "Failed asserting that ($class object)->$getter() is callable";
                $this->assertIsCallable([$mock, $getter], $msg);
            }
        }
    }

    public function test__interfaceInheritanceMap()
    {
        $packageDetails = PackageDetails::getInstance();
        $interfaceInheritanceMap = $packageDetails->interfaceInheritanceMap();
        foreach ($interfaceInheritanceMap as $class => $expectedInterfaces) {
            foreach ($expectedInterfaces as $expectedInterface) {
                $this->assertImplementsInterface($expectedInterface, $class);
            }
        }
    }

    public function test__classInheritanceMap()
    {
        $this->markTestIncomplete('This test has not been implemented yet');
    }

    public function test__traitInheritanceMap()
    {
        $this->markTestIncomplete('This test has not been implemented yet');
    }

    public function test__getParentClasses()
    {
        $this->markTestIncomplete('This test has not been implemented yet');
    }
}

// vim: syntax=php sw=4 ts=4 et:
