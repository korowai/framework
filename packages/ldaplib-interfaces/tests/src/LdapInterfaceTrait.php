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

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait LdapInterfaceTrait
{
    use BindingInterfaceTrait;
    use SearchingInterfaceTrait;
    use ComparingInterfaceTrait;
    use EntryManagerInterfaceTrait;
}

// vim: syntax=php sw=4 ts=4 et tw=119:
