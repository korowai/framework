<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Container\PhpDi;

use DI\ContainerBuilder;
use Korowai\Testing\Container\ContainerFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ContainerFactory implements ContainerFactoryInterface
{
    /**
     * @var array|string
     */
    private $config = [];

    /**
     * {@inheritdoc}
     */
    public function setConfig(string $config): self
    {
        $this->config = $config;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function createContainer(): ContainerInterface
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions($this->config);

        return $builder->build();
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
