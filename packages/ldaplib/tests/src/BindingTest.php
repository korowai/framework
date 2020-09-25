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

use Korowai\Lib\Ldap\Binding;
use Korowai\Lib\Ldap\BindingInterface;
use Korowai\Lib\Ldap\BindingTrait;
use Korowai\Lib\Ldap\Core\LdapLinkInterface;
use Korowai\Lib\Ldap\Core\LdapLinkWrapperInterface;
use Korowai\Lib\Ldap\Core\LdapLinkWrapperTrait;
use Korowai\Testing\Ldaplib\ExamineLdapLinkErrorHandlerTrait;
use Korowai\Testing\Ldaplib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Binding
 * @covers \Korowai\Testing\Ldaplib\ExamineLdapLinkErrorHandlerTrait
 * @covers \Korowai\Tests\Lib\Ldap\BindingTestTrait
 *
 * @internal
 */
final class BindingTest extends TestCase
{
    use BindingTestTrait;
    use ExamineLdapLinkErrorHandlerTrait;

    // required by BindingTestTrait
    public function createBindingInstance(LdapLinkInterface $ldapLink, bool $bound = false): BindingInterface
    {
        $args = func_get_args();

        return new Binding(...$args);
    }

    //
    //
    // TESTS
    //
    //

    public function testImplementsBindingInterface(): void
    {
        $this->assertImplementsInterface(BindingInterface::class, Binding::class);
    }

    public function testImplementsLdapLinkWrapperInterface(): void
    {
        $this->assertImplementsInterface(LdapLinkWrapperInterface::class, Binding::class);
    }

    public function testUsesBindingTrait(): void
    {
        $this->assertUsesTrait(BindingTrait::class, Binding::class);
    }

    public function testUsesLdapLinkWrapperTrait(): void
    {
        $this->assertUsesTrait(LdapLinkWrapperTrait::class, Binding::class);
    }

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
        $bind = new Binding(...$args);
        $this->assertObjectPropertiesIdenticalTo($expect, $bind);
    }
}

// vim: syntax=php sw=4 ts=4 et:
