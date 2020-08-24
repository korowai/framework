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
 * Interface for LDIF record visitor objects.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface NodeVisitorInterface
{
    /**
     * Visits instance of AttrValSpecInterface.
     * @param AttrValSpecInterface $record
     */
    public function visitAttrValSpec(AttrValSpecInterface $record);

    /**
     * Visits instance of ControlInterface.
     * @param ControlInterface $record
     */
    public function visitControl(ControlInterface $record);

    /**
     * Visits instance of DnSpecInterface.
     * @param DnSpecInterface $record
     */
    public function visitDnSpec(DnSpecInterface $record);

    /**
     * Visits instance of HasAttrValSpecsInterface.
     * @param HasAttrValSpecsInterface $record
     */
    public function visitHasAttrValSpecs(HasAttrValSpecsInterface $record);

    /**
     * Visits instance of LdifAddRecordInterface.
     * @param LdifAddRecordInterface $record
     */
    public function visitLdifAddRecord(LdifAddRecordInterface $record);

    /**
     * Visits instance of LdifAttrValRecordInterface.
     * @param LdifAttrValRecordInterface $record
     */
    public function visitLdifAttrValRecord(LdifAttrValRecordInterface $record);

    /**
     * Visits instance of LdifChangeRecordInterface.
     * @param LdifChangeRecordInterface $record
     */
    public function visitLdifChangeRecord(LdifChangeRecordInterface $record);

    /**
     * Visits instance of LdifDeleteRecordInterface.
     * @param LdifDeleteRecordInterface $record
     */
    public function visitLdifDeleteRecord(LdifDeleteRecordInterface $record);

    /**
     * Visits instance of LdifModDnRecordInterface.
     * @param LdifModDnRecordInterface $record
     */
    public function visitLdifModDnRecord(LdifModDnRecordInterface $record);

    /**
     * Visits instance of LdifModifyRecordInterface.
     * @param LdifModifyRecordInterface $record
     */
    public function visitLdifModifyRecord(LdifModifyRecordInterface $record);

    /**
     * Visits instance of ModSpecInterface.
     * @param ModSpecInterface $record
     */
    public function visitModSpec(ModSpecInterface $record);

    /**
     * Visits instance of ValueSpecInterface.
     * @param ValueSpecInterface $record
     */
    public function visitValueSpec(ValueSpecInterface $record);

    /**
     * Visits instance of VersionSpecInterface.
     * @param VersionSpecInterface $record
     */
    public function visitVersionSpec(VersionSpecInterface $record);
}

// vim: syntax=php sw=4 ts=4 et:
