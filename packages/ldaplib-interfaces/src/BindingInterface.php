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
 * Represents and changes bind state of an ldap link.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface BindingInterface
{
    /**
     * Check whether the connection was already bound or not.
     *
     * @return bool
     */
    public function isBound() : bool;

    /**
     * Binds the connection against a DN and password
     *
     * @param  string $dn        The user's DN
     * @param  string $password  The associated password
     */
    public function bind(string $dn = null, string $password = null) : bool;

    /**
     * Unbinds the connection
     */
    public function unbind() : void;
}

// vim: syntax=php sw=4 ts=4 et tw=119:
