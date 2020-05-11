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

use Korowai\Lib\Ldif\Records\AttrValRecordInterface;
use Korowai\Lib\Ldif\Records\AddRecordInterface;
use Korowai\Lib\Ldif\Records\DeleteRecordInterface;
use Korowai\Lib\Ldif\Records\ModDnRecordInterface;
use Korowai\Lib\Ldif\Records\ModifyRecordInterface;

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
     * Visits instance of AddRecordInterface.
     *
     * @param AddRecordInterface $record
     */
    public function visitAddRecord(AddRecordInterface $record);

    /**
     * Visits instance of DeleteRecordInterface.
     *
     * @param DeleteRecordInterface $record
     */
    public function visitDeleteRecord(DeleteRecordInterface $record);

    /**
     * Visits instance of ModDnRecordInterface.
     *
     * @param ModDnRecordInterface $record
     */
    public function visitModDnRecord(ModDnRecordInterface $record);

    /**
     * Visits instance of ModifyRecordInterface.
     *
     * @param ModifyRecordInterface $record
     */
    public function visitModifyRecord(ModifyRecordInterface $record);
}

// vim: syntax=php sw=4 ts=4 et:
