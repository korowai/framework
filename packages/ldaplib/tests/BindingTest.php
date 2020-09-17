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

use Korowai\Lib\Ldap\Binding;
use Korowai\Lib\Ldap\BindingTrait;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperTrait;
use Korowai\Lib\Ldap\BindingInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Binding
 * @covers \Korowai\Tests\Lib\Ldap\BindingTestTrait
 * @covers \Korowai\Testing\Ldaplib\ExamineLdapLinkErrorHandlerTrait
 */
final class BindingTest extends TestCase
{
    use BindingTestTrait;
    use ExamineLdapLinkErrorHandlerTrait;

    // required by BindingTestTrait
    public function createBindingInstance(LdapLinkInterface $ldapLink, bool $bound = false) : BindingInterface
    {
        $args = func_get_args();
        return new Binding(...$args);
    }

    //
    //
    // TESTS
    //
    //

    public function test__implements__BindingInterface() : void
    {
        $this->assertImplementsInterface(BindingInterface::class, Binding::class);
    }

    public function test__implements__LdapLinkWrapperInterface() : void
    {
        $this->assertImplementsInterface(LdapLinkWrapperInterface::class, Binding::class);
    }

    public function test__uses__BindingTrait() : void
    {
        $this->assertUsesTrait(BindingTrait::class, Binding::class);
    }

    public function test__uses__LdapLinkWrapperTrait() : void
    {
        $this->assertUsesTrait(LdapLinkWrapperTrait::class, Binding::class);
    }

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
        $bind = new Binding(...$args);
        $this->assertHasPropertiesSameAs($expect, $bind);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
