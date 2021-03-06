<?php
/**
 * @file Tests/Exception/LdapExceptionTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldaplib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Korowai\Lib\Ldap\Exception\LdapException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LdapExceptionTest extends TestCase
{
    public function testBaseClass()
    {
        $this->assertInstanceOf(\ErrorException::class, new LdapException());
    }

    public function test_getMessage()
    {
        $e = new LdapException("Custom message");
        $this->assertEquals("Custom message", $e->getMessage());
    }
}

// vim: syntax=php sw=4 ts=4 et:
