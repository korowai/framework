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

use Korowai\Testing\Ldaplib\TestCase;
use Korowai\Lib\Ldap\LdapFactory;
use Korowai\Lib\Ldap\LdapFactoryInterface;
use Korowai\Lib\Ldap\Core\LdapLinkFactoryInterface;
use Psr\Container\ContainerInterface;

use Korowai\Lib\Ldap\Core\LdapLinkOptionsMapper;
use Korowai\Lib\Ldap\Core\LdapLinkOptionsMapperInterface;
use function Korowai\Lib\Ldap\config_path;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @coversNothing
 */
final class ConfigFilesTest extends TestCase
{
    private function examineConfiguredContainer(ContainerInterface $container) : void
    {
        $ldapFactory = $container->get(LdapFactoryInterface::class);
        $this->assertInstanceOf(LdapFactory::class, $ldapFactory);
        $this->assertSame($ldapFactory, $container->get(LdapFactoryInterface::class));
        $this->assertSame($ldapFactory->getLdapLinkFactory(), $container->get(LdapLinkFactoryInterface::class));
    }

    public function test__php_di_container_config() : void
    {
        $configFile = config_path('php-di/container.config.php');
        $containerBuilder = new \DI\ContainerBuilder;
        $containerBuilder->addDefinitions($configFile);
        $container = $containerBuilder->build();

        $this->examineConfiguredContainer($container);
    }

    public function test__symfony_container_config() : void
    {
        $configDir = config_path('symfony');

        $container = new \Symfony\Component\DependencyInjection\ContainerBuilder;
        $fileLocator = new \Symfony\Component\Config\FileLocator($configDir);
        $fileLoader = new \Symfony\Component\DependencyInjection\Loader\PhpFileLoader($container, $fileLocator);
        $fileLoader->load('container.config.php');

        // patch some defininitions (required for examineConfiguredContainer()).
        $container->getAlias(LdapFactoryInterface::class)->setPublic(true);
        $container->getAlias(LdapLinkFactoryInterface::class)->setPublic(true);

        $container->compile();

        $this->examineConfiguredContainer($container);
    }

    public function test__illuminate_container_config() : void
    {
        $configFile = config_path('illuminate/container.config.php');

        $container = new \Illuminate\Container\Container;
        $configure = require $configFile;
        $configure($container);

        $this->examineConfiguredContainer($container);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
