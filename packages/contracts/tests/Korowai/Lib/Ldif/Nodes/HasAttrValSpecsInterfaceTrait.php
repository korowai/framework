<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
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
