<?php
/**
 * @file Testing/ObjectPropertiesInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing;

/**
 * Specifies properties of an object.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface ObjectPropertiesInterface
{
    /**
     * Returns array of values suitable for comparisons performed by
     * constraints. All values that are instances of
     * [ObjectPropertiesInterface](ObjectPropertiesInterface.html)
     * get converted to arrays recursively.
     *
     * @return array
     */
    public function getArrayForComparison() : array;
}

// vim: syntax=php sw=4 ts=4 et:
