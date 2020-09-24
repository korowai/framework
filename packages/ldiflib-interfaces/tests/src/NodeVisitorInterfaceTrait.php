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

use Korowai\Lib\Ldif\Nodes\AttrValSpecInterface;
use Korowai\Lib\Ldif\Nodes\ControlInterface;
use Korowai\Lib\Ldif\Nodes\DnSpecInterface;
use Korowai\Lib\Ldif\Nodes\HasAttrValSpecsInterface;
use Korowai\Lib\Ldif\Nodes\LdifAddRecordInterface;
use Korowai\Lib\Ldif\Nodes\LdifAttrValRecordInterface;
use Korowai\Lib\Ldif\Nodes\LdifChangeRecordInterface;
use Korowai\Lib\Ldif\Nodes\LdifDeleteRecordInterface;
use Korowai\Lib\Ldif\Nodes\LdifModDnRecordInterface;
use Korowai\Lib\Ldif\Nodes\LdifModifyRecordInterface;
use Korowai\Lib\Ldif\Nodes\ModSpecInterface;
use Korowai\Lib\Ldif\Nodes\ValueSpecInterface;
use Korowai\Lib\Ldif\Nodes\VersionSpecInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait NodeVisitorInterfaceTrait
{
    /**
     * Visits instance of AttrValSpecInterface.
     */
    public function visitAttrValSpec(AttrValSpecInterface $record)
    {
    }

    /**
     * Visits instance of ControlInterface.
     */
    public function visitControl(ControlInterface $record)
    {
    }

    /**
     * Visits instance of DnSpecInterface.
     */
    public function visitDnSpec(DnSpecInterface $record)
    {
    }

    /**
     * Visits instance of HasAttrValSpecsInterface.
     */
    public function visitHasAttrValSpecs(HasAttrValSpecsInterface $record)
    {
    }

    /**
     * Visits instance of LdifAddRecordInterface.
     */
    public function visitLdifAddRecord(LdifAddRecordInterface $record)
    {
    }

    /**
     * Visits instance of LdifAttrValRecordInterface.
     */
    public function visitLdifAttrValRecord(LdifAttrValRecordInterface $record)
    {
    }

    /**
     * Visits instance of LdifChangeRecordInterface.
     */
    public function visitLdifChangeRecord(LdifChangeRecordInterface $record)
    {
    }

    /**
     * Visits instance of LdifDeleteRecordInterface.
     */
    public function visitLdifDeleteRecord(LdifDeleteRecordInterface $record)
    {
    }

    /**
     * Visits instance of LdifModDnRecordInterface.
     */
    public function visitLdifModDnRecord(LdifModDnRecordInterface $record)
    {
    }

    /**
     * Visits instance of LdifModifyRecordInterface.
     */
    public function visitLdifModifyRecord(LdifModifyRecordInterface $record)
    {
    }

    /**
     * Visits instance of ModSpecInterface.
     */
    public function visitModSpec(ModSpecInterface $record)
    {
    }

    /**
     * Visits instance of ValueSpecInterface.
     */
    public function visitValueSpec(ValueSpecInterface $record)
    {
    }

    /**
     * Visits instance of VersionSpecInterface.
     */
    public function visitVersionSpec(VersionSpecInterface $record)
    {
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
