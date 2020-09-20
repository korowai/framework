<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Ldaplib;

use Korowai\Lib\Ldap\LdapFactory;
use Korowai\Lib\Ldap\LdapFactoryInterface;
use Korowai\Lib\Ldap\Core\LdapLinkFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ExamineLdaplibContainerTrait
{
    abstract static public function assertInstanceOf(string $class, $value, string $message = '');
    abstract static public function assertSame($expected, $value, string $message = '');

    public static function examineLdaplibContainer(ContainerInterface $container) : void
    {
        $ldapFactory = $container->get(LdapFactoryInterface::class);
        self::assertInstanceOf(LdapFactory::class, $ldapFactory);
        self::assertSame($ldapFactory, $container->get(LdapFactoryInterface::class));
        self::assertSame($ldapFactory->getLdapLinkFactory(), $container->get(LdapLinkFactoryInterface::class));
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
