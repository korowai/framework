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
 * @todo Write documentation.
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface ComparingInterface
{
    /**
     * Performs LDAP compare query and returns its result
     *
     * @param  string $dn
     * @param  string $attribute
     * @param  string $value
     *
     * @return bool Result of the comparison
     */
    public function compare(string $dn, string $attribute, string $value) : bool;
}

// vim: syntax=php sw=4 ts=4 et:
