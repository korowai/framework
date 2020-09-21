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

use Psr\Container\ContainerInterface;
use function DI\get;

return [
    Container\ContainerFactoryInterface::class => get(Container\PhpDi\ContainerFactory::class),

    Container\PhpDi\ContainerFactory::class =>
    function (ContainerInterface $container) : Container\PhpDi\ContainerFactory {
        return new Container\PhpDi\ContainerFactory;
    },
];
