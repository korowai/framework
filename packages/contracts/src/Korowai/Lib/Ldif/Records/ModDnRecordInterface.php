<?php
/**
 * @file src/Korowai/Lib/Ldif/Records/ModDnRecordInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Records;

/**
 * Interface for a record object representing
 * [RFC2849](https://tools.ietf.org/html/rfc2849)
 * *ldif-change-record* of type *change-moddn*.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface ModDnRecordInterface extends ChangeRecordInterface
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
