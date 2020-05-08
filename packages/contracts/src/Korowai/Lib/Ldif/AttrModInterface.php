<?php
/**
 * @file src/Korowai/Lib/Ldif/AttrModInterface.php
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
 * Interface for semantic value of the
 * [RFC2849](https://tools.ietf.org/html/rfc2849)
 * *mod-spec* rule.
 */
interface AttrModInterface
{
    /**
     * Returns [RFC2849](https://tools.ietf.org/html/rfc2849#page-3)
     * AttributeDescription string consisting of attribute type and
     * options.
     *
     * @return string
     */
    public function getAttribute() : string;

    /**
     * Returns the object representing attribute value.
     *
     * @return ValueInterface
     */
    public function getValueObject() : ValueInterface;
}

// vim: syntax=php sw=4 ts=4 et:
