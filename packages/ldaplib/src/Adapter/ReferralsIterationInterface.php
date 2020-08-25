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

use Korowai\Lib\Ldap\ResultReferenceInterface;
use Korowai\Lib\Ldap\ResultReferralIteratorInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface ReferralsIterationInterface
{
    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

    /**
     * Returns the result of ``current($this->referrals)``.
     *
     * @return mixed
     */
    public function referrals_current();

    /**
     * Returns the result of ``key($this->referrals)``.
     *
     * @return mixed
     */
    public function referrals_key();

    /**
     * Returns the result of ``next($this->referrals)``.
     *
     * @return mixed
     */
    public function referrals_next();

    /**
     * Returs the result of ``reset($this->referrals)``.
     *
     * @return mixed
     */
    public function referrals_reset();

    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd
}

// vim: syntax=php sw=4 ts=4 et:
