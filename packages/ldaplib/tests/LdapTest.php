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
use Korowai\Tests\Lib\Ldap\BindingTestTrait;
use Korowai\Tests\Lib\Ldap\EntryManagerTestTrait;

use Korowai\Lib\Ldap\Ldap;
use Korowai\Lib\Ldap\LdapInterface;
use Korowai\Lib\Ldap\BindingInterface;
use Korowai\Lib\Ldap\ComparingInterface;
use Korowai\Lib\Ldap\SearchingInterface;
use Korowai\Lib\Ldap\EntryManagerInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkConstructorInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkFactoryInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkConfigResolver;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkConfigResolverInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperTrait;
use Korowai\Lib\Ldap\BindingTrait;
use Korowai\Lib\Ldap\ComparingTrait;
use Korowai\Lib\Ldap\SearchingTrait;
use Korowai\Lib\Ldap\EntryManagerTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Ldap
 */
final class LdapTest extends TestCase
{
    use BindingTestTrait;
    use EntryManagerTestTrait;
    use ComparingTestTrait;
    use SearchingTestTrait;

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

//    //
//    // createWitLdapLinkFactory()
//    //
//
//    public function test__createWithLdapLinkFactory() : void
//    {
//        $link    = $this->createMock(LdapLinkInterface::class);
//        $factory = $this->createMock(LdapLinkFactoryInterface::class);
//        $factory->expects($this->once())
//                ->method('createLdapLink')
//                ->with()
//                ->willReturn($link);
//
//        $ldap = Ldap::createWithLdapLinkFactory($factory);
//        $this->assertSame($link, $ldap->getLdapLink());
//        $this->assertFalse($ldap->isBound());
//    }
//
//    //
//    // createWithConfig()
//    //
//
//    public static function prov__createWithConfig() : array
//    {
//        return [
//            // #0
//            ['mock', 'mock'],
//
//            // #1
//            ['mock'],
//
//            // #2
//            [],
//
//            // #3
//            ['mock', null],
//
//            // #4
//            [null, 'mock'],
//
//            // #5
//            [null, null],
//        ];
//    }
//
//    /**
//     * @dataProvider prov__createWithConfig
//     */
//    public function test__createWithConfig($param0 = false, $param1 = false) : void
//    {
//        $config =  ['uri' => 'ldap://example.org'];
//
//        if ($param0 === 'mock') {
//            $resolved = ['uri' => 'ldap://example.org', 'tls' => false];
//        } else {
//            $resolved = (new LdapLinkConfigResolver)->resolve($config);
//        }
//
//        $args = [$config];
//
//        if ($param0 === 'mock') {
//            $resolver    = $this->createMock(LdapLinkConfigResolverInterface::class);
//            $resolver   ->expects($this->once())
//                        ->method('resolve')
//                        ->with($config)
//                        ->willReturn($resolved);
//            $args[] = $resolver;
//        } elseif ($param0 === null) {
//            $args[] = null;
//        }
//
//        if ($param1 === 'mock') {
//            $link        = $this->createMock(LdapLinkInterface::class);
//            $constructor = $this->createMock(LdapLinkConstructorInterface::class);
//            $constructor->expects($this->once())
//                        ->method('connect')
//                        ->with($resolved['uri'])
//                        ->willReturn($link);
//            $args[] = $constructor;
//        } elseif ($param1 === null) {
//            $args[] = null;
//        }
//
//        $ldap = Ldap::createWithConfig(...$args);
//
//        if (isset($link)) {
//            $this->assertSame($link, $ldap->getLdapLink());
//        } else {
//            $this->assertInstanceOf(LdapLinkInterface::class, $ldap->getLdapLink());
//        }
//        $this->assertFalse($ldap->isBound());
//    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
