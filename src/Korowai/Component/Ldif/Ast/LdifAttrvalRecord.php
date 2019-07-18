<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Component\Ldif\Ast;

/**
 * Represents the ldif-attrval-record (see RFC2849).
 */
class LdifAttrvalRecord extends LdifRecord
{
    /**
     * Returns this node's type as a string.
     *
     * @return string
     */
    public function getAstType() : string
    {
        return 'ldif-attrval-record';
    }
}

// vim: syntax=php sw=4 ts=4 et:
