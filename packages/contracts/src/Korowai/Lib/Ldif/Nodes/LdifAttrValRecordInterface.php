<?php
/**
 * @file src/Korowai/Lib/Ldif/Nodes/LdifAttrValRecordInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Nodes;

use Korowai\Lib\Ldif\RecordInterface;
use Korowai\Lib\Ldif\NodeInterface;

/**
 * Interface for record objects representing
 * [RFC2849](https://tools.ietf.org/html/rfc2849) ldif-attrval-records.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface LdifAttrValRecordInterface extends RecordInterface, NodeInterface, HasAttrValSpecsInterface
{
}

// vim: syntax=php sw=4 ts=4 et:
