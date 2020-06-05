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

/**
 * Interface for objects representing LDIF records.
 *
 * According to [RFC2849](https://tools.ietf.org/html/rfc2849), an LDIF file
 * consists of series of records (either ldif-attrval-records or
 * ldif-change-records). Korowai's approach to parsing LDIF file shall result
 * with a collection of record objects, each one implementing this
 * [RecordInterface](RecordInterface.html). The interface also depends on
 * [SnippetInterface](SnippetInterface.html), so it identifies the piece of
 * LDIF source code that makes up the record. The
 * [RecordInterface](RecordInterface.html) may be used together with
 * [RecordVisitorInterface](RecordVisitorInterface.html) to implement record
 * processing algorithms according to the visitor design pattern.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface RecordInterface
{
    /**
     * Returns the DN of this record.
     *
     * @return string
     */
    public function getDn() : string;

    /**
     * Accept record visitor.
     *
     * @param  RecordVisitorInterface $visitor
     */
    public function acceptRecordVisitor(RecordVisitorInterface $visitor);
}

// vim: syntax=php sw=4 ts=4 et:
