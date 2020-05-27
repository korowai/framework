<?php
/**
 * @file tests/Korowai/Lib/Ldif/Nodes/LdifModDnRecordInterfaceTrait.php
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
trait LdifModDnRecordInterfaceTrait
{
    use LdifChangeRecordInterfaceTrait;
    use NodeInterfaceTrait;

    public function getNewRdn() : string
    {
        return "";
    }

    public function getDeleteOldRdn() : bool
    {
        return false;
    }

    public function getNewSuperior() : ?string
    {
        return null;
    }
}

// vim: syntax=php sw=4 ts=4 et:
