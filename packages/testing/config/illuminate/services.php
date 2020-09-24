<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing;

use Illuminate\Container\Container as IlluminateContainer;
use Psr\Container\ContainerInterface;

return function (IlluminateContainer $container): void {
    $container->bind(Container\ContainerFactoryInterface::class, Container\Illuminate\ContainerFactory::class);

    $container->singleton(
        Container\Illuminate\ContainerFactory::class,
        function (ContainerInterface $container): Container\Illuminate\ContainerFactory {
            return new Container\Illuminate\ContainerFactory();
        }
    );
};
