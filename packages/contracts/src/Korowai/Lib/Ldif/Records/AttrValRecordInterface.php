<?php
/**
 * @file src/Korowai/Lib/Ldif/Records/AttrValRecordInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Records;

use Korowai\Lib\Ldif\RecordInterface;

/**
 * Interface for record objects representing
 * [RFC2849](https://tools.ietf.org/html/rfc2849) ldif-attrval-records.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface AttrValRecordInterface extends RecordInterface, AttrValSpecsInterface
{
}

// vim: syntax=php sw=4 ts=4 et:
