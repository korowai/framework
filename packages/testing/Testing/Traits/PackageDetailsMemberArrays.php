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

use Korowai\Testing\Graph;

/**
 * Facilitates implementation of
 * [PackageDetailsInterface](\.\./PackageDetailsInterface.html) using member
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
     * @var Graph
     */
    protected $interfaceInheritanceGraph = null;

    /**
     * @var Graph
     */
    protected $traitInheritanceGraph = null;

    /**
     * @var Graph
     */
    protected $classInheritanceGraph = null;

    /**
     * @todo Write documentation.
     */
    abstract public function classesDetails() : array;

    /**
     * Returns a key ``=>`` value array mapping class (or interface) names onto
     * arrays describing properties of these classes. The term "property" is
     * understood here as an attribute that is publicly accessible via its
     * getter method. For each class, the array of properties is an array
     * mapping property names onto their getter method names. The method
     * returns only the properties that are defined directly in the subject
     * class. Inherited properties must not be included in the returned array.
     *
     * **Example**:
     *
     * ```
     * namespace Korowai\Lib\Ldif;
     *
     * interface ParserErrorInterface extends SourceLocationInterface, \Throwable {
     *      public function getMultilineMessage() : string;
     * }
     *
     * // ...
     *
     * return [
     *      // ...
     *      Korowai\Lib\Ldif\ParserErrorInterface::class => [
     *          'multilineMessage' => 'getMultilineMessage',
     *      ],
     *      // ...
     * ];
     * ```
     *
     * @return array
     */
    public function objectPropertiesMap() : array
    {
        return $this->getClassesDetail('objectPropertiesMap', 'properties', []);
    }

    /**
     * Returns a graph representing interface inheritance.
     *
     * @return Graph
     */
    public function interfaceInheritanceGraph() : Graph
    {
        return $this->getClassesGraph('interfaceInheritanceGraph', 'interfaces');
    }

    /**
     * Returns a graph representing class inheritance.
     *
     * @return Graph
     */
    public function classInheritanceGraph() : Graph
    {
        return $this->getClassesGraph('classInheritanceGraph', 'parent');
    }

    /**
     * Returns a graph representing trait inheritance by classes.
     *
     * @return Graph
     */
    public function traitInheritanceGraph() : Graph
    {
        return $this->getClassesGraph('traitInheritanceGraph', 'traits');
    }

    /**
     * @todo Write documentation.
     */
    protected function getClassesDetail(string $attrname, string $detail, $default)
    {
        if (!isset($this->{$attrname})) {
            $this->{$attrname} = $this->extractClassesDetail($detail, $default);
        }
        return $this->{$attrname};
    }

    /**
     * @todo Write documentation.
     */
    protected function getClassesGraph(string $attrname, string $detail) : Graph
    {
        if (!isset($this->{$attrname})) {
            $adjacency = $this->extractClassesDetail($detail, []);
            $this->{$attrname} = Graph::createWithAdjacency($adjacency);
        }
        return $this->{$attrname};
    }

    /**
     * @todo Write documentation.
     */
    protected function extractClassesDetail(string $detail, $default) : array
    {
        return array_map(function ($details) use ($detail, $default) {
            return $details[$detail] ?? $default;
        }, $this->classesDetails());
    }
}

// vim: syntax=php sw=4 ts=4 et:
