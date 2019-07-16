<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\LdapService
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Service\Ldap\Tests;

use PHPUnit\Framework\TestCase;
use Korowai\Service\Ldap\LdapServiceProvider;
use Korowai\Service\Ldap\LdapService;

use \Laravel\Lumen\Application;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LdapServiceProviderTest extends TestCase
{
    public function testBaseClass()
    {
        $klass = LdapServiceProvider::class;
        $base = \Illuminate\Support\ServiceProvider::class;
        $msg = "Failed asserting that ${klass} is an ${base}";
        $this->assertTrue(is_a($klass, $base, TRUE), $msg);
    }

    public function test_register()
    {
        $app = new Application();

        $app->register(LdapServiceProvider::class);

        $ldap = $app['korowai.ldap'];
        $this->assertInstanceOf(LdapService::class, $ldap);

        $cfg = $ldap->getConfig();

        $this->assertCount(1, $cfg);
        $this->assertArrayHasKey('databases', $cfg);


        $this->assertCount(1, $cfg['databases']);

        $id = 1;
        $this->assertEquals($cfg['databases'][$id], [
              'id' => $id,
              'ldap' => [
                'uri' => 'ldap://ldap-service',
                'options' => [
                  'protocol_version' => 3
                ]
              ],
              'meta' => [
                'name' => 'Test LDAP Database',
                'description' => 'Description for the LDAP Service',
                'base' => 'dc=example,dc=org',
              ],
        ]);
    }
}

// vim: syntax=php sw=4 ts=4 et:
