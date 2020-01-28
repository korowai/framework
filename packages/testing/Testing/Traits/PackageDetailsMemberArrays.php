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
     * @var array
     */
    protected $interfaceInheritanceGraph = null;

    /**
     * @var array
     */
    protected $traitInheritanceGraph = null;

    /**
     * @var array
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
        if (!isset($this->objectPropertiesMap)) {
            $this->objectPropertiesMap = $this->extractClassesDetail('properties', []);
        }
        return $this->objectPropertiesMap;
    }

    /**
     * Returns a key ``=>`` value array mapping class (or interface) names onto
     * their parent class names.
     *
     * **Example**:
     *
     * ```
     * namespace Korowai\Lib\Ldap;
     *
     * class Ldap extends AbstractLdap {
     *   // ...
     * }
     *
     * // ...
     *
     * return [
     *      // ...
     *      Korowai\Lib\Ldap\Ldap::class => Korowai\Lib\Ldap\AbstractLdap::class,
     *      // ...
     * ];
     * ```
     *
     * @return array
     */
    public function interfaceInheritanceGraph() : array
    {
        if (!isset($this->interfaceInheritanceGraph)) {
            $this->interfaceInheritanceGraph = $this->extractClassesDetail('interfaces', []);
        }
        return $this->interfaceInheritanceGraph;
    }

    /**
     * Returns a key ``=>`` value array mapping class (or interface) names onto
     * arrays of interface names that the given class implements. The nested
     * array of implemented interfaces contains only interfaces that are
     * inherited directly by the given class (or interface).
     *
     * **Example**:
     *
     * ```
     * namespace Korowai\Lib\Ldif;
     *
     * interface ParserErrorInterface extends SourceLocationInterface, \Throwable {
     *   // ...
     * }
     *
     * // ...
     *
     * return [
     *      // ...
     *      Korowai\Lib\Ldif\ParserErrorInterface::class => [
     *          SourceLocationInterface::class,
     *          \Throwable::class
     *      ],
     *      // ...
     * ];
     * ```
     *
     * @return array
     */
    public function classInheritanceGraph() : array
    {
        if (!isset($this->classInheritanceGraph)) {
            $this->classInheritanceGraph = $this->extractClassesDetail('parent', null);
        }
        return $this->classInheritanceGraph;
    }

    /**
     * Returns a key ``=>`` value array mapping class names onto arrays of
     * trait names that the given class uses. The nested array of traits
     * contains only traits that are included directly by the given class.
     * Traits inherited from parent classes must not be included in the
     * returned array.
     *
     * **Example**:
     *
     * ```
     * namespace Korowai\Lib\Ldif;
     *
     * class Parser implements ParserInterface {
     *      use MatchesPatterns;
     *      use SkipsWhitespaces;
     *      use ParsesVersionSpec;
     * }
     *
     * // ...
     *
     * return [
     *      // ...
     *      Korowai\Lib\Ldif\Parser::class => [
     *          Korowai\Lib\Ldif\MatchesPatterns::class,
     *          Korowai\Lib\Ldif\SkipsWhitespaces::class,
     *          Korowai\Lib\Ldif\ParsesVersionSpec::class,
     *      ]
     *      // ...
     * ];
     * ```
     *
     * @return array
     */
    public function traitInheritanceGraph() : array
    {
        if (!isset($this->traitInheritanceGraph)) {
            $this->traitInheritanceGraph = $this->extractClassesDetail('traits', []);
        }
        return $this->traitInheritanceGraph;
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
