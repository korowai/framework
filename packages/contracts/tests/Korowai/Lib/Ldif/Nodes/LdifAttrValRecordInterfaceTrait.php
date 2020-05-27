<?php
/**
 * @file tests/Korowai/Lib/Ldif/Nodes/LdifAttrValRecordInterfaceTrait.php
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
use Korowai\Tests\Lib\Ldif\RecordInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait LdifAttrValRecordInterfaceTrait
{
    use RecordInterfaceTrait;
    use NodeInterfaceTrait;
    use HasAttrValSpecsInterfaceTrait;
}

// vim: syntax=php sw=4 ts=4 et:
