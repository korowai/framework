<?php
/**
 * @file tests/Korowai/Lib/Ldap/LdapInterfaceTrait.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap;

use Korowai\Tests\Lib\Ldap\Adapter\BindingInterfaceTrait;
use Korowai\Tests\Lib\Ldap\Adapter\EntryManagerInterfaceTrait;
use Korowai\Tests\Lib\Ldap\Adapter\AdapterInterfaceTrait;
use Korowai\Lib\Ldap\Adapter\AdapterInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait LdapInterfaceTrait
{
    use BindingInterfaceTrait;
    use EntryManagerInterfaceTrait;
    use AdapterInterfaceTrait;

    public $adapter = null;

    public function getAdapter() : AdapterInterface
    {
        return $this->adapter;
    }
}

// vim: syntax=php sw=4 ts=4 et:
