<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Nodes;

use Korowai\Lib\Ldif\NodeInterface;

/**
 * Interface for a record object representing
 * [RFC2849](https://tools.ietf.org/html/rfc2849)
 * *ldif-change-record* of type *change-moddn*.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface LdifModDnRecordInterface extends LdifChangeRecordInterface, NodeInterface
{
    /**
     * Returns the string value of new RDN.
     *
     * @return string
     */
    public function getNewRdn() : string;

    /**
     * Returns boolean flag determining whether to delete old RDN or not.
     *
     * @return bool
     */
    public function getDeleteOldRdn() : bool;

    /**
     * Returns the  distinguished name of new superior or null if not
     * specified.
     *
     * @return string|null
     */
    public function getNewSuperior() : ?string;
}

// vim: syntax=php sw=4 ts=4 et:
