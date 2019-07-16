<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\LdapService
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Service\Ldap\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Korowai\Service\Ldap\Exception\LdapServiceException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LdapServiceExceptionTest extends TestCase
{
    public function testBaseClass()
    {
        $this->assertInstanceOf(\RuntimeException::class, new LdapServiceException());
    }

    public function test_getMessage()
    {
        $e = new LdapServiceException("Custom message");
        $this->assertEquals("Custom message", $e->getMessage());
    }
}

// vim: syntax=php sw=4 ts=4 et:
