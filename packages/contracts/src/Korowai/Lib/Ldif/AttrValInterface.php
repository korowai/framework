<?php
/**
 * @file src/Korowai/Lib/Ldif/AttrValInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif;

/**
 * Interface for semantic value of RFC2849 attrval-spec rule.
 */
interface AttrValInterface
{
    /**
     * Returns attribute-description string consisting of attribute type and
     * options.
     *
     * @return string
     */
    public function getAttributeDescription() : string;

    /**
     * Returns the object representing attribute value.
     *
     * @return ValueInterface
     */
    public function getValue() : ValueInterface;
}

// vim: syntax=php sw=4 ts=4 et:
