<?php
/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

//
// Configuration file for php-di/php-di.
//
// Usage:
//
// $containerBuilder = new DI\ContainerBuilder
// /* ... */
// $containerBuilder->addDefinitions('config/php-di/definitions.php');
// /* ... */
// $container = $builder->build();
//

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


