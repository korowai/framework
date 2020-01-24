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

/**
 * Describes expected details of a package.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface PackageDetailsInterface
{
    /**
     * Returns a key ``=>`` value array mapping class (or interface) names onto
     * arrays describing properties of these classes. The term "property" is
     * understood here as an attribute that is publicly accessible via its
     * getter method. For each class, the array of properties is an array
     * mapping property names onto their getter method names. The method
     * returns only the properties that are defined directly in the subject
     * class. Inherited properties should not be listed for the given class (or
     * interface).
     *
     * **Example**:
     *
     * ```
     * interface ParserErrorInterface extends SourceLocationInterface, \Throwable {
     *      public function getMultilineMessage() : string;
     * }
     *
     * // ...
     *
     * return [
     *      // ...
     *      Korowai\Lib\Ldif\ParserErrorInterface::class => [
     *          'multilineMessage' => 'getMultilineMessage'
     *      ],
     *      // ...
     * ];
     * ```
     *
     * @return array
     */
    public static function objectProperties() : array;

    /**
     * Returns a key ``=>`` value array mapping class (or interface) names onto
     * arrays of interface names that the given class implements. The nested
     * array of implemented interfaces contain only interfaces that are
     * inherited directly by the given class (or interface).
     *
     * **Example**:
     * ```
     * interface ParserErrorInterface extends SourceLocationInterface, \Throwable {
     *   // ...
     * }
     *
     * // ...
     *
     * return [
     *      // ...
     *      Korowai\Lib\Ldif\ParserErrorInterface::class => [SourceLocationInterface::class, \Throwable::class],
     *      // ...
     * ];
     * ```
     *
     * @return array
     */
    public static function interfaceInheritance() : array;
}

// vim: syntax=php sw=4 ts=4 et:
