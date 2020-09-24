<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Ldaplib\Container;

use Korowai\Lib\Ldap\Core\LdapLinkFactoryInterface;
use Korowai\Lib\Ldap\LdapFactory;
use Korowai\Lib\Ldap\LdapFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ExamineConfiguredContainerTrait
{
    abstract public static function assertInstanceOf(string $class, $value, string $message = '');

    abstract public static function assertSame($expected, $value, string $message = '');

    public function examineConfiguredContainer(ContainerInterface $container, $config): void
    {
        $ldapFactory = $container->get(LdapFactoryInterface::class);
        $this->assertInstanceOf(LdapFactory::class, $ldapFactory);
        $this->assertSame($ldapFactory, $container->get(LdapFactoryInterface::class));
        $this->assertSame($ldapFactory->getLdapLinkFactory(), $container->get(LdapLinkFactoryInterface::class));
    }

    public function getServicesVisibility(): array
    {
        return [
            LdapFactoryInterface::class => true,
            LdapLinkFactoryInterface::class => true,
        ];
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
