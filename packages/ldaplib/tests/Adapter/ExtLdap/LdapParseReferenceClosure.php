<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Adapter\ExtLdap;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapParseReferenceClosure extends AbstractClosure
{
    public function __invoke($ldap, $reference, &$referrals)
    {
        $values = $this->getReturnArguments();
        $referrals = $values[0] ?? null;
        return $this->getReturnValue();
    }
}

// vim: syntax=php sw=4 ts=4 et tw=120:
