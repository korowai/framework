<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

use Psr\Container\ContainerInterface;

use Korowai\Lib\Ldap\LdapFactory;
use Korowai\Lib\Ldap\LdapFactoryInterface;
use Korowai\Lib\Ldap\Core\LdapLinkConfigResolver;
use Korowai\Lib\Ldap\Core\LdapLinkConfigResolverInterface;
use Korowai\Lib\Ldap\Core\LdapLinkConstructor;
use Korowai\Lib\Ldap\Core\LdapLinkConstructorInterface;
use Korowai\Lib\Ldap\Core\LdapLinkFactory;
use Korowai\Lib\Ldap\Core\LdapLinkFactoryInterface;
use Korowai\Lib\Ldap\Core\LdapLinkOptionsMapper;
use Korowai\Lib\Ldap\Core\LdapLinkOptionsMapperInterface;
use Korowai\Lib\Ldap\Core\LdapLinkOptionsSpecification;
use Korowai\Lib\Ldap\Core\LdapLinkOptionsSpecificationInterface;

use function DI\get;

return [
    LdapFactoryInterface::class                  => get(LdapFactory::class),
    LdapLinkConfigResolverInterface::class       => get(LdapLinkConfigResolver::class),
    LdapLinkConstructorInterface::class          => get(LdapLinkConstructor::class),
    LdapLinkFactoryInterface::class              => get(LdapLinkFactory::class),
    LdapLinkOptionsMapperInterface::class        => get(LdapLinkOptionsMapper::class),
    LdapLinkOptionsSpecificationInterface::class => get(LdapLinkOptionsSpecification::class),

    LdapFactory::class => function (ContainerInterface $container) : LdapFactory {
        return new LdapFactory(
            $container->get(LdapLinkFactoryInterface::class),
            $container->get(LdapLinkConfigResolverInterface::class),
        );
    },

    LdapLinkConfigResolver::class => function (ContainerInterface $container) : LdapLinkConfigResolver {
        return new LdapLinkConfigResolver($container->get(LdapLinkOptionsSpecificationInterface::class));
    },

    LdapLinkConstructor::class => function (ContainerInterface $container) : LdapLinkConstructor {
        return new LdapLinkConstructor();
    },

    LdapLinkFactory::class => function (ContainerInterface $container) : LdapLinkFactory {
        return new LdapLinkFactory($container->get(LdapLinkConstructorInterface::class));
    },

    LdapLinkOptionsMapper::class => function (ContainerInterface $container) : LdapLinkOptionsMapper {
        return new LdapLinkOptionsMapper();
    },

    LdapLinkOptionsSpecification::class => function (ContainerInterface $container) : LdapLinkOptionsSpecification {
        return new LdapLinkOptionsSpecification($container->get(LdapLinkOptionsMapperInterface::class));
    },
];
