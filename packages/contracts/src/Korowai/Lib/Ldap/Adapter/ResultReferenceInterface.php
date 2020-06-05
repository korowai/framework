<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface ResultReferenceInterface extends ResultRecordInterface
{
    /**
     * Returns referrals
     * @return array An array of referrals
     */
    public function getReferrals() : array;
}

// vim: syntax=php sw=4 ts=4 et:
