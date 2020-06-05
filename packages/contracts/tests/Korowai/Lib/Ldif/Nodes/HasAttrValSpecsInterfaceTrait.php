<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Nodes;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait HasAttrValSpecsInterfaceTrait
{
    public $attrValSpecs = null;

    public function getAttrValSpecs() : array
    {
        return $this->attrValSpecs;
    }
}

// vim: syntax=php sw=4 ts=4 et:
