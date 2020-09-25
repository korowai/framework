<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap;

use Korowai\Lib\Ldap\ResultReferralIteratorInterface;
use Korowai\Testing\Dummies\PhpIteratorAggregateTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ResultReferenceInterfaceTrait
{
    use PhpIteratorAggregateTrait;

    public $referrals;

    public $referralIterator;

    public function getReferrals(): array
    {
        return $this->referrals;
    }

    public function getReferralIterator(): ResultReferralIteratorInterface
    {
        return $this->referralIterator;
    }
}

// vim: syntax=php sw=4 ts=4 et:
