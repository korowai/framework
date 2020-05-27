<?php
/**
 * @file tests/Korowai/Lib/Ldif/Nodes/LdifChangeRecordInterfaceTrait.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Nodes;

use Korowai\Tests\Lib\Ldif\RecordInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait LdifChangeRecordInterfaceTrait
{
    use RecordInterfaceTrait;

    public function getChangeType() : string
    {
        return "";
    }

    public function getControls() : array
    {
        return [];
    }
}

// vim: syntax=php sw=4 ts=4 et:
