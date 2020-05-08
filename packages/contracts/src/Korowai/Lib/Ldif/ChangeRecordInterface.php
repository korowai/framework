<?php
/**
 * @file src/Korowai/Lib/Ldif/ChangeRecordInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif;

/**
 * Interface for record objects representing
 * [RFC2849](https://tools.ietf.org/html/rfc2849) ldif-change-records.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface ChangeRecordInterface extends RecordInterface
{
    /**
     * Returns the change type as string.
     *
     * The returned value is one of
     *
     * - ``"add"``,
     * - ``"delete"``,
     * - ``"modrdn"`` or ``"moddn"``,
     * - ``"modify"``.
     *
     * @return string
     */
    public function getChangeType() : string;
}

// vim: syntax=php sw=4 ts=4 et:
