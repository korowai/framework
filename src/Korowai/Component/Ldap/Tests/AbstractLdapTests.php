<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldap
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Component\Ldap\Tests;

use PHPUnit\Framework\TestCase;
use Korowai\Component\Ldap\AbstractLdap;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class AbstractLdapTest extends TestCase
{
    public function test__implements__LdapInterface()
    {
        $interfaces = class_implements(AbstractLdap::class);
        $this->assertContains(LdapInterface::class, $interfaces);
    }

    public function test__query()
    {
        $query = new class {
            public function getResult() { return 'ok'; }
        };

        $ldap = $this->createMockBuilder(AbstractLdap::class)
                     ->setMethods(['createQuery'])
                     ->getMockForAbstractClass();

        $args = [ 'dc=example,dc=org', '(objectClass=*)', ['foo'] ];
        $ldap->expects($this->once())
             ->method('createQuery')
             ->with(...$args)
             ->willReturn($query);

        $this->assertEquals('ok', $ldap->query(...$args));
    }
}

// vim: syntax=php sw=4 ts=4 et:
