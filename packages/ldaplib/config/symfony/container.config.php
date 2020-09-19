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
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator) : void {
    $services = $configurator->services();

    //
    // public services
    //

    $services->alias(LdapFactoryInterface::class, LdapFactory::class);
    $services->alias(LdapLinkFactoryInterface::class, LdapLinkFactory::class);

    //
    // dependencies
    //

    $services->alias(LdapLinkConfigResolverInterface::class, LdapLinkConfigResolver::class);
    $services->alias(LdapLinkConstructorInterface::class, LdapLinkConstructor::class);
    $services->alias(LdapLinkOptionsMapperInterface::class, LdapLinkOptionsMapper::class);
    $services->alias(LdapLinkOptionsSpecificationInterface::class, LdapLinkOptionsSpecification::class);

    $services->set(LdapFactory::class)
             ->args([
                 service(LdapLinkFactoryInterface::class),
                 service(LdapLinkConfigResolverInterface::class)
             ]);

    $services->set(LdapLinkConstructor::class);
    $services->set(LdapLinkConfigResolver::class)
             ->args([
                 service(LdapLinkOptionsSpecificationInterface::class)
             ]);
    $services->set(LdapLinkFactory::class)
             ->args([
                 service(LdapLinkConstructorInterface::class)
             ]);
    $services->set(LdapLinkOptionsMapper::class);

    $services->set(LdapLinkOptionsSpecification::class)
             ->args([
                 service(LdapLinkOptionsMapperInterface::class)
             ]);
};
