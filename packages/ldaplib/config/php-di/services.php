<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap;

use function DI\get;
use Psr\Container\ContainerInterface;

return [
    LdapFactoryInterface::class => get(LdapFactory::class),
    Core\LdapLinkConfigResolverInterface::class => get(Core\LdapLinkConfigResolver::class),
    Core\LdapLinkConstructorInterface::class => get(Core\LdapLinkConstructor::class),
    Core\LdapLinkFactoryInterface::class => get(Core\LdapLinkFactory::class),
    Core\LdapLinkOptionsMapperInterface::class => get(Core\LdapLinkOptionsMapper::class),
    Core\LdapLinkOptionsSpecificationInterface::class => get(Core\LdapLinkOptionsSpecification::class),

    LdapFactory::class => function (ContainerInterface $container): LdapFactory {
        return new LdapFactory(
            $container->get(Core\LdapLinkFactoryInterface::class),
            $container->get(Core\LdapLinkConfigResolverInterface::class),
        );
    },

    Core\LdapLinkConfigResolver::class => function (ContainerInterface $container): Core\LdapLinkConfigResolver {
        return new Core\LdapLinkConfigResolver($container->get(Core\LdapLinkOptionsSpecificationInterface::class));
    },

    Core\LdapLinkConstructor::class => function (ContainerInterface $container): Core\LdapLinkConstructor {
        return new Core\LdapLinkConstructor();
    },

    Core\LdapLinkFactory::class => function (ContainerInterface $container): Core\LdapLinkFactory {
        return new Core\LdapLinkFactory($container->get(Core\LdapLinkConstructorInterface::class));
    },

    Core\LdapLinkOptionsMapper::class => function (ContainerInterface $container): Core\LdapLinkOptionsMapper {
        return new Core\LdapLinkOptionsMapper();
    },

    Core\LdapLinkOptionsSpecification::class => function (
        ContainerInterface $container
    ): Core\LdapLinkOptionsSpecification {
        return new Core\LdapLinkOptionsSpecification($container->get(Core\LdapLinkOptionsMapperInterface::class));
    },
];
