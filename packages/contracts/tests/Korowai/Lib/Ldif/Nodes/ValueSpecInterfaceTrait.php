<?php
/**
 * @file tests/Korowai/Lib/Ldif/Nodes/ValueSpecInterfaceTrait.php
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

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ValueSpecInterfaceTrait
{
    use NodeInterfaceTrait;

    public function getType() : int
    {
        return 0;
    }

    public function getSpec()
    {
        return null;
    }

    public function getContent() : string
    {
        return "";
    }
}

// vim: syntax=php sw=4 ts=4 et:
