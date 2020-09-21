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
use Korowai\Testing\Ldaplib\ExamineLdapLinkErrorHandlerTrait;

use Korowai\Lib\Ldap\Ldap;
use Korowai\Lib\Ldap\LdapInterface;
use Korowai\Lib\Ldap\BindingInterface;
use Korowai\Lib\Ldap\ComparingInterface;
use Korowai\Lib\Ldap\SearchingInterface;
use Korowai\Lib\Ldap\EntryManagerInterface;
use Korowai\Lib\Ldap\Core\LdapLinkInterface;
use Korowai\Lib\Ldap\Core\LdapLinkConstructorInterface;
use Korowai\Lib\Ldap\Core\LdapLinkFactoryInterface;
use Korowai\Lib\Ldap\Core\LdapLinkConfigResolver;
use Korowai\Lib\Ldap\Core\LdapLinkConfigResolverInterface;
use Korowai\Lib\Ldap\Core\LdapLinkWrapperInterface;
use Korowai\Lib\Ldap\Core\LdapLinkWrapperTrait;
use Korowai\Lib\Ldap\BindingTrait;
use Korowai\Lib\Ldap\ComparingTrait;
use Korowai\Lib\Ldap\SearchingTrait;
use Korowai\Lib\Ldap\EntryManagerTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Ldap
 * @covers \Korowai\Tests\Lib\Ldap\BindingTestTrait
 * @covers \Korowai\Tests\Lib\Ldap\EntryManagerTestTrait
 * @covers \Korowai\Tests\Lib\Ldap\ComparingTestTrait
 * @covers \Korowai\Tests\Lib\Ldap\SearchingTestTrait
 * @covers \Korowai\Testing\Ldaplib\ExamineLdapLinkErrorHandlerTrait
 */
final class LdapTest extends TestCase
{
    use BindingTestTrait;
    use EntryManagerTestTrait;
    use ComparingTestTrait;
    use SearchingTestTrait;
    use ExamineLdapLinkErrorHandlerTrait;

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

    // required by ComparingTestTrait
    public function createComparingInstance(LdapLinkinterface $ldapLink) : ComparingInterface
    {
        return new Ldap(...func_get_args());
    }

    // required by SearchingTestTrait
    public function createSearchingInstance(LdapLinkinterface $ldapLink) : SearchingInterface
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

    public function test__uses__ComparingTrait() : void
    {
        $this->assertUsesTrait(ComparingTrait::class, Ldap::class);
    }

    public function test__uses__SearchingTrait() : void
    {
        $this->assertUsesTrait(SearchingTrait::class, Ldap::class);
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
}

// vim: syntax=php sw=4 ts=4 et tw=119: