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

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator) : void {
    $services = $configurator->services();

    $services->alias(LdapFactoryInterface::class, LdapFactory::class);
    $services->alias(Core\LdapLinkFactoryInterface::class, Core\LdapLinkFactory::class);
    $services->alias(Core\LdapLinkConfigResolverInterface::class, Core\LdapLinkConfigResolver::class);
    $services->alias(Core\LdapLinkConstructorInterface::class, Core\LdapLinkConstructor::class);
    $services->alias(Core\LdapLinkOptionsMapperInterface::class, Core\LdapLinkOptionsMapper::class);
    $services->alias(Core\LdapLinkOptionsSpecificationInterface::class, Core\LdapLinkOptionsSpecification::class);

    $services->set(LdapFactory::class)
             ->args([
                 service(Core\LdapLinkFactoryInterface::class),
                 service(Core\LdapLinkConfigResolverInterface::class)
             ]);

    $services->set(Core\LdapLinkConstructor::class);
    $services->set(Core\LdapLinkConfigResolver::class)
             ->args([
                 service(Core\LdapLinkOptionsSpecificationInterface::class)
             ]);
    $services->set(Core\LdapLinkFactory::class)
             ->args([
                 service(Core\LdapLinkConstructorInterface::class)
             ]);
    $services->set(Core\LdapLinkOptionsMapper::class);

    $services->set(Core\LdapLinkOptionsSpecification::class)
             ->args([
                 service(Core\LdapLinkOptionsMapperInterface::class)
             ]);
};
