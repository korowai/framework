<?php
/**
 * @file tests/Korowai/Lib/Ldif/Nodes/HasAttrValSpecsInterfaceTrait.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Nodes;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait HasAttrValSpecsInterfaceTrait
{
    public function getAttrValSpecs() : array
    {
        return [];
    }
}

// vim: syntax=php sw=4 ts=4 et:
