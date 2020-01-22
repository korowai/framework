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

use Korowai\Lib\Ldif\Records\VersionSpecInterface;
use Korowai\Lib\Ldif\Records\AddEntryInterface;
use Korowai\Lib\Ldif\Records\DeleteEntryInterface;
use Korowai\Lib\Ldif\Records\ModDnInterface;
use Korowai\Lib\Ldif\Records\ModifyEntryInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface RecordVisitorInterface
{
    public function visitVersionSpec(VersionSpecInterface $record);
    public function visitAddEntry(AddEntryInterface $record);
    public function visitDeleteEntry(DeleteEntryInterface $record);
    public function visitModDn(ModDnInterface $record);
    public function visitModifyEntry(ModifyEntryInterface $record);
}

// vim: syntax=php sw=4 ts=4 et:
