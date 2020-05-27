<?php
/**
 * @file tests/Korowai/Lib/Ldap/EntryInterfaceTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap;

use Korowai\Lib\Ldap\EntryInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class EntryInterfaceTest extends TestCase
{
    public function test__notImplemented()
    {
        $this->markTestIncomplete("test not implemented yet");
    }
}

// vim: syntax=php sw=4 ts=4 et:
