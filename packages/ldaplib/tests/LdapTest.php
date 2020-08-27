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
use Korowai\Tests\Lib\Ldap\Adapter\ExtLdap\BindingTestTrait;
use Korowai\Tests\Lib\Ldap\Adapter\ExtLdap\EntryManagerTestTrait;

use Korowai\Lib\Ldap\Ldap;
use Korowai\Lib\Ldap\LdapInterface;
use Korowai\Lib\Ldap\BindingInterface;
use Korowai\Lib\Ldap\EntryManagerInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkConstructorInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkFactoryInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkConfigResolverInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperTrait;
use Korowai\Lib\Ldap\Adapter\ExtLdap\BindingTrait;
use Korowai\Lib\Ldap\Adapter\ExtLdap\EntryManagerTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapTest extends TestCase
{
    use BindingTestTrait;
    use EntryManagerTestTrait;

    // required by BindingTestTrait
    public function createBindingInstance(LdapLinkInterface $ldapLink, bool $bound = false) : BindingInterface
    {
        return new Ldap(...func_get_args());
    }

    // required by EntryManagerTrait
    public function createEntryManagerInstance(LdapLinkinterface $ldapLink) : EntryManagerInterface
    {
        return new Ldap(...func_get_args());
    }

    //
    //
    // TESTS
    //
    //

    public function test__implements__LdapInterface() : void
    {
        $this->assertImplementsInterface(LdapInterface::class, Ldap::class);
    }

    public function test__implements__LdapLinkWrapperInterface() : void
    {
        $this->assertImplementsInterface(LdapLinkWrapperInterface::class, Ldap::class);
    }

    public function test__uses__LdapLinkWrapperTrait() : void
    {
        $this->assertUsesTrait(LdapLinkWrapperTrait::class, Ldap::class);
    }

    public function test__uses__BindingTrait() : void
    {
        $this->assertUsesTrait(BindingTrait::class, Ldap::class);
    }

    public function test__uses__EntryManagerTrait() : void
    {
        $this->assertUsesTrait(EntryManagerTrait::class, Ldap::class);
    }

    //
    // __construct()
    //

    public function prov__construct() : array
    {
        $link = $this->createMock(LdapLinkInterface::class);
        return [
            // #0
            [
                'args' => [$link],
                'expect' => [
                    'ldapLink' => $link,
                    'isBound' => false
                ],
            ],
            // #1
            [
                'args' => [$link, false],
                'expect' => [
                    'ldapLink' => $link,
                    'isBound' => false
                ],
            ],
            // #2
            [
                'args' => [$link, true],
                'expect' => [
                    'ldapLink' => $link,
                    'isBound' => true
                ],
            ],
        ];
    }

    /**
     * @dataProvider prov__construct
     */
    public function test__construct(array $args, array $expect) : void
    {
        $bind = new Ldap(...$args);
        $this->assertHasPropertiesSameAs($expect, $bind);
    }

    //
    // createWitLdapLinkFactory()
    //

    public function test__createWithLdapLinkFactory() : void
    {
        $link = $this->createMock(LdapLinkInterface::class);
        $factory = $this->getMockBuilder(LdapLinkFactoryInterface::class)
                        ->setMethods(['createLdapLink'])
                        ->getMock();
        $factory->expects($this->once())
                ->method('createLdapLink')
                ->with()
                ->willReturn($link);

        $ldap = Ldap::createWithLdapLinkFactory($factory);
        $this->assertSame($link, $ldap->getLdapLink());
        $this->assertFalse($ldap->isBound());
    }

    //
    // createWithConfig()
    //

    public function test__createWithConfig() : void
    {
        $this->markTestIncomplete('Test not implemented yet!');
    }
}

// vim: syntax=php sw=4 ts=4 et:
