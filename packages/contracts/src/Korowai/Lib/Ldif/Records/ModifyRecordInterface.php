<?php
/**
 * @file src/Korowai/Lib/Ldif/Records/ModifyRecordInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Records;

/**
 * Interface for a record object representing
 * [RFC2849](https://tools.ietf.org/html/rfc2849)
 * *ldif-change-record* of type *change-modify*.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface ModifyRecordInterface extends ChangeRecordInterface
{

}

// vim: syntax=php sw=4 ts=4 et:
