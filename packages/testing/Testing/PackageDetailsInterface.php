<?php
/**
 * @file Testing/PackageDetailsInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing;

use Korowai\Lib\Basic\SingletonInterface;

/**
 * Describes expected contents of a package.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface PackageDetailsInterface extends SingletonInterface
{
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
    public function objectPropertiesMap() : array;

    /**
     * Returns a key ``=>`` value array mapping class (or interface) names onto
     * their parent class names. Used to represent the class inheritance graph
     * (adjacency list).
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
    public function classInheritanceGraph() : array;

    /**
     * Returns a key ``=>`` value array mapping class (or interface) names onto
     * arrays of interface names that the given class implements. The nested
     * array of implemented interfaces contains only interfaces that are
     * inherited directly by the given class (or interface). Used to represent
     * interface inheritance graph (adjacency list).
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
    public function interfaceInheritanceGraph() : array;

    /**
     * Returns a key ``=>`` value array mapping class names onto arrays of
     * trait names that the given class uses. The nested array of traits
     * contains only traits that are included directly by the given class.
     * Traits inherited from parent classes must not be included in the
     * returned array. Used to represent trait inheritance graph (adjacency
     * list).
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
    public function traitInheritanceGraph() : array;
}

// vim: syntax=php sw=4 ts=4 et:
