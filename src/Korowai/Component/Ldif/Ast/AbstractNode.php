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

abstract class AbstractNode implements NodeInterface
{
    /**
     * Same as getCode()
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getCode();
    }
}

// vim: syntax=php sw=4 ts=4 et:
