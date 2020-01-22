<?php
/**
 * @file src/Korowai/Lib/Ldif/RecordVisitorInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface RecordVisitorInterface
{
    public function visitVersionSpec(RecordInterface $record);
    public function visitAddEntry(RecordInterface $record);
    public function visitDeleteEntry(RecordInterface $record);
    public function visitModDn(RecordInterface $record);
    public function visitModifyEntry(RecordInterface $record);
}

// vim: syntax=php sw=4 ts=4 et:
