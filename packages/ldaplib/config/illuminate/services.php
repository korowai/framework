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

use Illuminate\Container\Container;
use Psr\Container\ContainerInterface;

return function (Container $container): void {
    $container->bind(LdapFactoryInterface::class, LdapFactory::class);
    $container->bind(Core\LdapLinkFactoryInterface::class, Core\LdapLinkFactory::class);
    $container->bind(Core\LdapLinkConfigResolverInterface::class, Core\LdapLinkConfigResolver::class);
    $container->bind(Core\LdapLinkConstructorInterface::class, Core\LdapLinkConstructor::class);
    $container->bind(Core\LdapLinkOptionsMapperInterface::class, Core\LdapLinkOptionsMapper::class);
    $container->bind(Core\LdapLinkOptionsSpecificationInterface::class, Core\LdapLinkOptionsSpecification::class);

    $container->singleton(LdapFactory::class, function (ContainerInterface $container): LdapFactory {
        return new LdapFactory(
            $container->get(Core\LdapLinkFactoryInterface::class),
            $container->get(Core\LdapLinkConfigResolverInterface::class),
        );
    });

    $container->singleton(
        Core\LdapLinkConfigResolver::class,
        function (ContainerInterface $container): Core\LdapLinkConfigResolver {
            return new Core\LdapLinkConfigResolver($container->get(Core\LdapLinkOptionsSpecificationInterface::class));
        }
    );

    $container->singleton(
        Core\LdapLinkConstructor::class,
        function (ContainerInterface $container): Core\LdapLinkConstructor {
            return new Core\LdapLinkConstructor();
        }
    );

    $container->singleton(
        Core\LdapLinkFactory::class,
        function (ContainerInterface $container): Core\LdapLinkFactory {
            return new Core\LdapLinkFactory($container->get(Core\LdapLinkConstructorInterface::class));
        }
    );

    $container->singleton(
        Core\LdapLinkOptionsMapper::class,
        function (ContainerInterface $container): Core\LdapLinkOptionsMapper {
            return new Core\LdapLinkOptionsMapper();
        }
    );

    $container->singleton(
        Core\LdapLinkOptionsSpecification::class,
        function (ContainerInterface $container): Core\LdapLinkOptionsSpecification {
            return new Core\LdapLinkOptionsSpecification($container->get(Core\LdapLinkOptionsMapperInterface::class));
        }
    );
};
