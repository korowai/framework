<?php
/**
 * @file tests/Korowai/Lib/Ldif/Nodes/ControlInterfaceTrait.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Nodes;

use Korowai\Tests\Lib\Ldif\NodeInterfaceTrait;
use Korowai\Lib\Ldif\Nodes\ValueSpecInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ControlInterfaceTrait
{
    use NodeInterfaceTrait;

    public function getOid() : string
    {
        return "";
    }

    public function getCriticality() : ?bool
    {
        return null;
    }

    public function getValueSpec() : ?ValueSpecInterface
    {
        return null;
    }
}

// vim: syntax=php sw=4 ts=4 et:
