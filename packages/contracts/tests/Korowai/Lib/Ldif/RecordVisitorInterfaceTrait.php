<?php
/**
 * @file tests/Korowai/Lib/Ldif/RecordVisitorInterfaceTrait.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
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
