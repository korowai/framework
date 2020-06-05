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

use Korowai\Lib\Ldif\RecordInterface;

/**
 * Interface for record objects representing
 * [RFC2849](https://tools.ietf.org/html/rfc2849) *ldif-change-record*.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface LdifChangeRecordInterface extends RecordInterface
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

    /**
     * Returns controls assigned to the record.
     *
     * @return array Array of [ControlInterface](\.\./ControlInterface.html) objects.
     */
    public function getControls() : array;
}

// vim: syntax=php sw=4 ts=4 et:
