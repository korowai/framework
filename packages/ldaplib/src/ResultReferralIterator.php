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
 * Iterates through referrals of a result reference entry.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultReferralIterator extends \ArrayIterator implements ResultReferralIteratorInterface
{
}

// vim: syntax=php sw=4 ts=4 et tw=120:
