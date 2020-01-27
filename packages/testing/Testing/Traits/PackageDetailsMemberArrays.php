<?php
/**
 * @file Testing/Traits/PackageDetailsMemberArrays.php
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
 * Traits for classes that implement PackageDetailsInterface using member
 * arrays to describe package properties.
 *
 * The class that includes this trait must define following member arrays:
 *
 * ```
 *  // Keys 'interfaces', 'properties', 'parent' are optional
 *  protected $classesDetails = [
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
trait PackageDetailsMemberArrays
{
    /**
     * @var array
     */
    protected $objectPropertiesMap = null;

    /**
     * @var array
     */
    protected $interfaceInheritanceMap = null;

    /**
     * @var array
     */
    protected $traitInheritanceMap = null;

    /**
     * @var array
     */
    protected $classInheritanceMap = null;

    /**
     * @todo Write documentation.
     */
    abstract public function classesDetails() : array;

    /**
     * {@inheritdoc}
     */
    public function objectPropertiesMap() : array
    {
        if (!isset($this->objectPropertiesMap)) {
            $this->objectPropertiesMap = $this->extractClassesDetail('properties', []);
        }
        return $this->objectPropertiesMap;
    }

    /**
     * {@inheritdoc}
     */
    public function interfaceInheritanceMap() : array
    {
        if (!isset($this->interfaceInheritanceMap)) {
            $this->interfaceInheritanceMap = $this->extractClassesDetail('interfaces', []);
        }
        return $this->interfaceInheritanceMap;
    }

    /**
     * {@inheritdoc}
     */
    public function classInheritanceMap() : array
    {
        if (!isset($this->classInheritanceMap)) {
            $this->classInheritanceMap = $this->extractClassesDetail('parent', null);
        }
        return $this->classInheritanceMap;
    }

    /**
     * {@inheritdoc}
     */
    public function traitInheritanceMap() : array
    {
        if (!isset($this->traitInheritanceMap)) {
            $this->traitInheritanceMap = $this->extractClassesDetail('traits', []);
        }
        return $this->traitInheritanceMap;
    }

    /**
     * Initializes given array from ``$this->classesDetails()``.
     */
    protected function extractClassesDetail(string $detail, $default) : array
    {
        return array_map(function ($details) use ($detail, $default) {
            return $details[$detail] ?? $default;
        }, $this->classesDetails());
    }
}

// vim: syntax=php sw=4 ts=4 et:
