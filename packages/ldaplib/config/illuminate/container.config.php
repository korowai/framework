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
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkConfigResolver;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkConfigResolverInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkConstructor;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkConstructorInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkFactory;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkFactoryInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkOptionsMapper;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkOptionsMapperInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkOptionsSpecification;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkOptionsSpecificationInterface;
use Illuminate\Container\Container;

return function (Container $container) : void {
    //
    // public services
    //

    $container->bind(LdapFactoryInterface::class, LdapFactory::class);
    $container->bind(LdapLinkFactoryInterface::class, LdapLinkFactory::class);

    //
    // dependencies
    //

    $container->bind(LdapLinkConfigResolverInterface::class, LdapLinkConfigResolver::class);
    $container->bind(LdapLinkConstructorInterface::class, LdapLinkConstructor::class);
    $container->bind(LdapLinkOptionsMapperInterface::class, LdapLinkOptionsMapper::class);
    $container->bind(LdapLinkOptionsSpecificationInterface::class, LdapLinkOptionsSpecification::class);

    $container->singleton(LdapFactory::class, function (ContainerInterface $container) : LdapFactory {
        return new LdapFactory(
            $container->get(LdapLinkFactoryInterface::class),
            $container->get(LdapLinkConfigResolverInterface::class),
        );
    });

    $container->singleton(
        LdapLinkConfigResolver::class,
        function (ContainerInterface $container) : LdapLinkConfigResolver {
            return new LdapLinkConfigResolver($container->get(LdapLinkOptionsSpecificationInterface::class));
        }
    );

    $container->singleton(
        LdapLinkConstructor::class,
        function (ContainerInterface $container) : LdapLinkConstructor {
            return new LdapLinkConstructor();
        }
    );

    $container->singleton(
        LdapLinkFactory::class,
        function (ContainerInterface $container) : LdapLinkFactory {
            return new LdapLinkFactory($container->get(LdapLinkConstructorInterface::class));
        }
    );

    $container->singleton(
        LdapLinkOptionsMapper::class,
        function (ContainerInterface $container) : LdapLinkOptionsMapper {
            return new LdapLinkOptionsMapper();
        }
    );

    $container->singleton(
        LdapLinkOptionsSpecification::class,
        function (ContainerInterface $container) : LdapLinkOptionsSpecification {
            return new LdapLinkOptionsSpecification($container->get(LdapLinkOptionsMapperInterface::class));
        }
    );
};
