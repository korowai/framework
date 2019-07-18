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

class AttrvalSpec extends NonTerminal
{
    /**
     * Returns this node's type as a string.
     *
     * @return string
     */
    public function getAstType() : string
    {
        return 'attrval-spec';
    }

    public function setChildren(AttributeDescription $attrDesc,
                                ValueSpec $valueSpec,
                                Sep $sep=null)
    {
        $this->children = [$attrDesc, $valueSpec, $sep ?? new Sep()];
        return $this;
    }

    public function getValue()
    {
        return null;
    }
}

// vim: syntax=php sw=4 ts=4 et:
