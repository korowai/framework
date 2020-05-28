<?php
/**
 * @file tests/Korowai/Lib/Ldap/Adapter/AdapterFactoryInterfaceTrait.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Adapter;

use Korowai\Lib\Ldap\Adapter\AdapterInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait AdapterFactoryInterfaceTrait
{
    public $configure = null;
    public $createAdapter = null;

    public function configure(array $config)
    {
        return $this->configure;
    }

    public function createAdapter() : AdapterInterface
    {
        return $this->createAdapter;
    }
}

// vim: syntax=php sw=4 ts=4 et:
