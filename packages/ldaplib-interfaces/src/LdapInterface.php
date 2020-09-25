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
 * LDAP interface.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface LdapInterface extends BindingInterface, SearchingInterface, ComparingInterface, EntryManagerInterface
{
}

// vim: syntax=php sw=4 ts=4 et:
