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

use Korowai\Lib\Ldap\BindingInterface;
use Korowai\Lib\Ldap\BindingTrait;
use Korowai\Lib\Ldap\ComparingInterface;
use Korowai\Lib\Ldap\ComparingTrait;
use Korowai\Lib\Ldap\Core\LdapLinkInterface;
use Korowai\Lib\Ldap\Core\LdapLinkWrapperInterface;
use Korowai\Lib\Ldap\Core\LdapLinkWrapperTrait;
use Korowai\Lib\Ldap\EntryManagerInterface;
use Korowai\Lib\Ldap\EntryManagerTrait;
use Korowai\Lib\Ldap\Ldap;
use Korowai\Lib\Ldap\LdapInterface;
use Korowai\Lib\Ldap\SearchingInterface;
use Korowai\Lib\Ldap\SearchingTrait;
use Korowai\Testing\Ldaplib\ExamineLdapLinkErrorHandlerTrait;
use Korowai\Testing\Ldaplib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Ldap
 * @covers \Korowai\Testing\Ldaplib\ExamineLdapLinkErrorHandlerTrait
 * @covers \Korowai\Tests\Lib\Ldap\BindingTestTrait
 * @covers \Korowai\Tests\Lib\Ldap\ComparingTestTrait
 * @covers \Korowai\Tests\Lib\Ldap\EntryManagerTestTrait
 * @covers \Korowai\Tests\Lib\Ldap\SearchingTestTrait
 *
 * @internal
 */
final class LdapTest extends TestCase
{
    use BindingTestTrait;
    use EntryManagerTestTrait;
    use ComparingTestTrait;
    use SearchingTestTrait;
    use ExamineLdapLinkErrorHandlerTrait;

    // required by BindingTestTrait
    public function createBindingInstance(LdapLinkInterface $ldapLink, bool $bound = false): BindingInterface
    {
        return new Ldap(...func_get_args());
    }

    // required by EntryManagerTrait
    public function createEntryManagerInstance(LdapLinkInterface $ldapLink): EntryManagerInterface
    {
        return new Ldap(...func_get_args());
    }

    // required by ComparingTestTrait
    public function createComparingInstance(LdapLinkInterface $ldapLink): ComparingInterface
    {
        return new Ldap(...func_get_args());
    }

    // required by SearchingTestTrait
    public function createSearchingInstance(LdapLinkInterface $ldapLink): SearchingInterface
    {
        return new Ldap(...func_get_args());
    }

    //
    //
    // TESTS
    //
    //

    public function testImplementsLdapInterface(): void
    {
        $this->assertImplementsInterface(LdapInterface::class, Ldap::class);
    }

    public function testImplementsLdapLinkWrapperInterface(): void
    {
        $this->assertImplementsInterface(LdapLinkWrapperInterface::class, Ldap::class);
    }

    public function testUsesLdapLinkWrapperTrait(): void
    {
        $this->assertUsesTrait(LdapLinkWrapperTrait::class, Ldap::class);
    }

    public function testUsesBindingTrait(): void
    {
        $this->assertUsesTrait(BindingTrait::class, Ldap::class);
    }

    public function testUsesComparingTrait(): void
    {
        $this->assertUsesTrait(ComparingTrait::class, Ldap::class);
    }

    public function testUsesSearchingTrait(): void
    {
        $this->assertUsesTrait(SearchingTrait::class, Ldap::class);
    }

    public function testUsesEntryManagerTrait(): void
    {
        $this->assertUsesTrait(EntryManagerTrait::class, Ldap::class);
    }

    //
    // __construct()
    //

    public function provConstruct(): array
    {
        $link = $this->createMock(LdapLinkInterface::class);

        return [
            // #0
            [
                'args' => [$link],
                'expect' => [
                    'getLdapLink()' => $link,
                    'isBound()' => false,
                ],
            ],
            // #1
            [
                'args' => [$link, false],
                'expect' => [
                    'getLdapLink()' => $link,
                    'isBound()' => false,
                ],
            ],
            // #2
            [
                'args' => [$link, true],
                'expect' => [
                    'getLdapLink()' => $link,
                    'isBound()' => true,
                ],
            ],
        ];
    }

    /**
     * @dataProvider provConstruct
     */
    public function testConstruct(array $args, array $expect): void
    {
        $bind = new Ldap(...$args);
        $this->assertObjectPropertiesIdenticalTo($expect, $bind);
    }
}

// vim: syntax=php sw=4 ts=4 et:
