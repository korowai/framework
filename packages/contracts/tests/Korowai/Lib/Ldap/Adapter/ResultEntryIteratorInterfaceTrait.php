<?php
/**
 * @file tests/Korowai/Lib/Ldap/Adapter/ResultEntryIteratorInterfaceTrait.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Adapter;

use Korowai\Tests\PhpIteratorTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ResultEntryIteratorInterfaceTrait
{
    use PhpIteratorTrait;
}

// vim: syntax=php sw=4 ts=4 et:
