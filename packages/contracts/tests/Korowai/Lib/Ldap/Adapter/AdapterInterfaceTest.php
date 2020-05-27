<?php
/**
 * @file tests/Korowai/Lib/Ldap/Adapter/AdapterInterfaceTest.php
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

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class AdapterInterfaceTest extends TestCase
{
    public function test__notImplemented()
    {
        $this->markTestIncomplete("test not implemented yet");
    }
}

// vim: syntax=php sw=4 ts=4 et:
