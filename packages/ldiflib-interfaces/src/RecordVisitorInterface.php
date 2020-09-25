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

use Korowai\Lib\Ldif\Nodes\LdifAddRecordInterface;
use Korowai\Lib\Ldif\Nodes\LdifAttrValRecordInterface;
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
     */
    public function visitLdifAttrValRecord(LdifAttrValRecordInterface $record);

    /**
     * Visits instance of LdifAddRecordInterface.
     */
    public function visitLdifAddRecord(LdifAddRecordInterface $record);

    /**
     * Visits instance of LdifDeleteRecordInterface.
     */
    public function visitLdifDeleteRecord(LdifDeleteRecordInterface $record);

    /**
     * Visits instance of LdifModDnRecordInterface.
     */
    public function visitLdifModDnRecord(LdifModDnRecordInterface $record);

    /**
     * Visits instance of LdifModifyRecordInterface.
     */
    public function visitLdifModifyRecord(LdifModifyRecordInterface $record);
}

// vim: syntax=php sw=4 ts=4 et:
