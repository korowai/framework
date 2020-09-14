<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface ResultReferenceInterface extends \IteratorAggregate
{
    /**
     * Returns referrals
     * @return array An array of referrals
     */
    public function getReferrals() : array;

    /**
     * Returns iterator over referrals
     * @return ResultReferralIteratorInterface
     */
    public function getReferralIterator() : ResultReferralIteratorInterface;
}

// vim: syntax=php sw=4 ts=4 et tw=120:
