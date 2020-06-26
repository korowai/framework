<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter\ExtLdap;

use Korowai\Lib\Ldap\Adapter\ResultReferralIteratorInterface;
use Korowai\Lib\Ldap\Adapter\ResultReferenceInterface;

/**
 * Iterates through referrals of a result reference entry.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultReferralIterator extends \ArrayIterator implements ResultReferralIteratorInterface
{
    /**
     * Initializes the iterator object
     *
     * @param ResultReferenceInterface $reference An ldap reference containing the referrals
     */
    public function __construct(ResultReferenceInterface $reference)
    {
        parent::__construct($reference->getReferrals());
    }
}

// vim: syntax=php sw=4 ts=4 et:
