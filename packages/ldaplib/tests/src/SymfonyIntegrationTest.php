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

use Korowai\Testing\Ldaplib\Symfony\TestCase;
use Korowai\Lib\Ldap\LdapFactoryInterface;
use Korowai\Lib\Ldap\Core\LdapLinkFactoryInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @coversNothing
 */
final class SymfonyIntegrationTest extends TestCase
{
    public function test__symfony_configures_container_correctly() : void
    {
        $this->setServicesVisibility([
            LdapFactoryInterface::class => true,
            LdapLinkFactoryInterface::class => true,
        ]);
        $this->examineLdaplibContainer($this->getContainer());
    }

    public static function prov__symfony_removed_private_services() : array
    {
        return [
            [LdapFactoryInterface::class],
            [LdapLinkFactoryInterface::class],
        ];
    }

    /**
     * @dataProvider prov__symfony_removed_private_services
     */
    public function test__symfony_removed_private_services(string $id) : void
    {
        $this->expectServiceHasBeenRemoved($this->getContainer(), $id);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
