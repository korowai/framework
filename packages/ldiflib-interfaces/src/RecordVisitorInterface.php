<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif;

use Korowai\Lib\Ldif\Nodes\LdifAttrValRecordInterface;
use Korowai\Lib\Ldif\Nodes\LdifAddRecordInterface;
use Korowai\Lib\Ldif\Nodes\LdifDeleteRecordInterface;
use Korowai\Lib\Ldif\Nodes\LdifModDnRecordInterface;
use Korowai\Lib\Ldif\Nodes\LdifModifyRecordInterface;

/**
 * Interface for LDIF record visitor objects.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface RecordVisitorInterface
{
    /**
     * Visits instance of LdifAttrValRecordInterface.
     *
     * @param LdifAttrValRecordInterface $record
     */
    public function visitLdifAttrValRecord(LdifAttrValRecordInterface $record);

    /**
     * Visits instance of LdifAddRecordInterface.
     *
     * @param LdifAddRecordInterface $record
     */
    public function visitLdifAddRecord(LdifAddRecordInterface $record);

    /**
     * Visits instance of LdifDeleteRecordInterface.
     *
     * @param LdifDeleteRecordInterface $record
     */
    public function visitLdifDeleteRecord(LdifDeleteRecordInterface $record);

    /**
     * Visits instance of LdifModDnRecordInterface.
     *
     * @param LdifModDnRecordInterface $record
     */
    public function visitLdifModDnRecord(LdifModDnRecordInterface $record);

    /**
     * Visits instance of LdifModifyRecordInterface.
     *
     * @param LdifModifyRecordInterface $record
     */
    public function visitLdifModifyRecord(LdifModifyRecordInterface $record);
}

// vim: syntax=php sw=4 ts=4 et:
