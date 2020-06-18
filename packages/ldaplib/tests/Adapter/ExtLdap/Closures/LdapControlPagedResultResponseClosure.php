<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Adapter\ExtLdap\Closures;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapControlPagedResultResponseClosure extends AbstractClosure
{
    public function __invoke($ldap, $result, &...$tail)
    {
        $values = $this->getReturnArguments();
        for ($offset = 0; $offset < count($tail); $offset++) {
            $tail[$offset] = $values[$offset] ?? null;
        }
        return $this->getReturnValue();
    }
}

// vim: syntax=php sw=4 ts=4 et:
