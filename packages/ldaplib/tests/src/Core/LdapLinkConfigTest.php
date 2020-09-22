<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Core;

use Korowai\Testing\Ldaplib\TestCase;

use Korowai\Lib\Ldap\Core\LdapLinkConfig;
use Korowai\Lib\Ldap\Core\LdapLinkConfigInterface;
use Korowai\Lib\Ldap\Core\LdapLinkConfigResolver;
use Korowai\Lib\Ldap\Core\LdapLinkConfigResolverInterface;
use Korowai\Lib\Ldap\Core\LdapLinkInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Core\LdapLinkConfig
 */
final class LdapLinkConfigTest extends TestCase
{

    //
    //
    // TESTS
    //
    //

    public function test__implements__LdapLinkConfigInterface() : void
    {
        $this->assertImplementsInterface(LdapLinkConfigInterface::class, LdapLinkConfig::class);
    }

    //
    // fromArray()
    //
    public function test__fromArray() : void
    {
        $resolver = $this->createMock(LdapLinkConfigResolverInterface::class);

        $config = [];
        $resolved = [
            'uri'     => 'URI',
            'tls'     => false,
            'options' => [0 => 1]
        ];
        $resolver->expects($this->once())
                 ->method('resolve')
                 ->with($config)
                 ->willReturn($resolved);

        $factory = LdapLinkConfig::fromArray($resolver, $config);

        $this->assertObjectHasPropertiesSameAs([
            'uri()'     => $resolved['uri'],
            'tls()'     => $resolved['tls'],
            'options()' => $resolved['options'],
        ], $factory);
    }

    //
    // __construct()
    //

    public function test__construct() : void
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessageMatches('/private .*::__construct/');

        new LdapLinkConfig('ldap:///', false, []);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
