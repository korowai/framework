<?php
/**
 * @file src/Korowai/Lib/Ldif/RecordVisitorInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif;

/**
 * Interface for LDIF record visitor objects.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface RecordVisitorInterface
{
    /**
     * Visits instance of AttrValRecordInterface.
     *
     * @param AttrValRecordInterface $record
     */
    public function visitAttrValRecord(AttrValRecordInterface $record);

    /**
     * Visits instance of ChangeRecordInterface.
     *
     * @param ChangeRecordInterface $record
     */
    public function visitChangeRecord(ChangeRecordInterface $record);
}

// vim: syntax=php sw=4 ts=4 et:
