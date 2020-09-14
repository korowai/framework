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
final class LdapParseResultClosure extends AbstractClosure
{
    public function __invoke($ldap, $result, &$errcode, &...$tail)
    {
        $values = $this->getReturnArguments();
        $errcode = $values[0];
        for ($i = 0; $i < 3; $i++) {
            if (count($tail) > $i) {
                $tail[$i] = $values[$i+1] ?? null;
            }
        }
        return $this->getReturnValue();
    }
}

// vim: syntax=php sw=4 ts=4 et tw=120:
