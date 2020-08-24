<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif;

use Korowai\Lib\Ldif\Nodes\LdifAttrValRecordInterface;
use Korowai\Lib\Ldif\Nodes\LdifAddRecordInterface;
use Korowai\Lib\Ldif\Nodes\LdifDeleteRecordInterface;
use Korowai\Lib\Ldif\Nodes\LdifModDnRecordInterface;
use Korowai\Lib\Ldif\Nodes\LdifModifyRecordInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait RecordVisitorInterfaceTrait
{
    public function visitLdifAttrValRecord(LdifAttrValRecordInterface $record)
    {
    }

    public function visitLdifAddRecord(LdifAddRecordInterface $record)
    {
    }

    public function visitLdifDeleteRecord(LdifDeleteRecordInterface $record)
    {
    }

    public function visitLdifModDnRecord(LdifModDnRecordInterface $record)
    {
    }

    public function visitLdifModifyRecord(LdifModifyRecordInterface $record)
    {
    }
}

// vim: syntax=php sw=4 ts=4 et:
