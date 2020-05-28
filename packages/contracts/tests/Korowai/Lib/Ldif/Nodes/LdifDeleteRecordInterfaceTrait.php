<?php
/**
 * @file tests/Korowai/Lib/Ldif/Nodes/LdifDeleteRecordInterfaceTrait.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Nodes;

use Korowai\Tests\Lib\Ldif\NodeInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait LdifDeleteRecordInterfaceTrait
{
    use LdifChangeRecordInterfaceTrait;
    use NodeInterfaceTrait;
}

// vim: syntax=php sw=4 ts=4 et:
