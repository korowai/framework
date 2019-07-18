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

class LdifContent extends LdifFile
{
    /**
     * Returns this node's type as a string.
     *
     * @return string
     */
    public function getAstType() : string
    {
        return 'ldif-content';
    }
}

// vim: syntax=php sw=4 ts=4 et:
