<?php
/**
 * @file Testing/Traits/PackageDetailsFromStaticArrays.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\testing
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Traits;

/**
 * Traits for classes that implement PackageDetailsInterface using static
 * arrays to describe package properties.
 *
 * The class that includes this trait must define following static arrays:
 *
 * ```
 *  // Keys 'interfaces', 'properties', 'parent' are optional
 *  static $classesDetails = [
 *      Foo::class => [
 *          'parent'        => Parent::class,
 *          'interfaces'    => [FooInterface::class, BarInterface::class],
 *          'traits'        => [GeezTrait::class],
 *          'properties'    => [
 *              'x'         => 'getX',
 *              'y'         => 'getY'
 *          ],
 *      ]
 *  ];
 * ```
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait PackageDetailsFromStaticArrays
{
    /**
     * @var array
     */
    protected static $objectProperties = [];

    /**
     * @var array
     */
    protected static $interfaceInheritance = [];

    /**
     * @var array
     */
    protected static $traitInheritance = [];

    /**
     * @var array
     */
    protected static $classInheritance = [];

    /**
     * @var bool
     */
    protected static $objectPropertiesExtracted = false;

    /**
     * @var bool
     */
    protected static $interfaceInheritanceExtracted = false;

    /**
     * @var bool
     */
    protected static $traitInheritanceExtracted = false;

    /**
     * @var bool
     */
    protected static $classInheritanceExtracted = false;

    /**
     * {@inheritdoc}
     */
    public static function objectProperties() : array
    {
        if (!static::$objectPropertiesExtracted) {
            static::extractClassesDetail(static::$objectProperties, 'properties', []);
            static::$objectPropertiesExtracted = true;
        }
        return static::$objectProperties;
    }

    /**
     * {@inheritdoc}
     */
    public static function interfaceInheritance() : array
    {
        if (!static::$interfaceInheritanceExtracted) {
            static::extractClassesDetail(static::$interfaceInheritance, 'interfaces', []);
            static::$interfaceInheritanceExtracted = true;
        }
        return static::$interfaceInheritance;
    }

    /**
     * {@inheritdoc}
     */
    public static function classInheritance() : array
    {
        if (!static::$classInheritanceExtracted) {
            static::extractClassesDetail(static::$classInheritance, 'parent', null);
            static::$classInheritanceExtracted = true;
        }
        return static::$classInheritance;
    }

    /**
     * {@inheritdoc}
     */
    public static function traitInheritance() : array
    {
        if (!static::$traitInheritanceExtracted) {
            static::extractClassesDetail(static::$traitInheritance, 'traits', []);
            static::$traitInheritanceExtracted = true;
        }
        return static::$traitInheritance;
    }

    /**
     * Initializes given array from ``static::$classesDetails``.
     */
    protected static function extractClassesDetail(array &$array, string $detail, $default)
    {
        foreach (static::$classesDetails as $class => $details) {
            $array[$class] = $details[$detail] ?? $default;
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
