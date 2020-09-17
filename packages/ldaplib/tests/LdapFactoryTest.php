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

use Korowai\Testing\Ldaplib\TestCase;
use Korowai\Lib\Ldap\Ldap;
use Korowai\Lib\Ldap\LdapFactory;
use Korowai\Lib\Ldap\LdapFactoryInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkFactoryInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkConfigInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkConfigResolverInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\LdapFactory
 */
final class LdapFactoryTest extends TestCase
{
    //
    //
    // TESTS
    //
    //

    public function test__implements__LdapFactoryInterface() : void
    {
        $this->assertImplementsInterface(LdapFactoryInterface::class, LdapFactory::class);
    }

    //
    // __construct()
    //

    public function test__construct() : void
    {
        $linkFactory = $this->createMock(LdapLinkFactoryInterface::class);
        $resolver = $this->createMock(LdapLinkConfigResolverInterface::class);
        $factory = new LdapFactory($linkFactory, $resolver);
        $this->assertSame($linkFactory, $factory->getLdapLinkFactory());
        $this->assertSame($resolver, $factory->getLdapLinkConfigResolver());
    }

    //
    // creteLdapInterface()
    //

    public function test__createLdapInterface() : void
    {
        $link        = $this->createMock(LdapLinkInterface::class);
        $linkFactory = $this->createMock(LdapLinkFactoryInterface::class);
        $resolver    = $this->createMock(LdapLinkConfigResolverInterface::class);
        $config      = ['foo' => 'bar'];
        $resolved    = ['uri' => 'ldap:///', 'tls' => true, 'options' => []];

        $linkFactory->expects($this->once())
                    ->method('createLdapLink')
                    ->with(
                        $this->logicalAnd(
                            $this->isInstanceOf(LdapLinkConfigInterface::class),
                            $this->hasPropertiesIdenticalTo([
                                'uri()'     => $resolved['uri'],
                                'tls()'     => $resolved['tls'],
                                'options()' => $resolved['options'],
                            ])
                        )
                    )
                    ->willReturn($link);

        $resolver->expects($this->once())
                 ->method('resolve')
                 ->with($config)
                 ->willReturn($resolved);

        $factory = new LdapFactory($linkFactory, $resolver);

        $ldap = $factory->createLdapInterface($config);
        $this->assertInstanceOf(Ldap::class, $ldap);
        $this->assertSame($link, $ldap->getLdapLink());
    }
}
// vim: syntax=php sw=4 ts=4 et tw=119:
