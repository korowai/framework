<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Container;

use Psr\Container\ContainerInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface ContainerFactoryInterface
{
    /**
     * Configure factory to use the $config file for a newly created container.
     */
    public function setConfig(string $config): ContainerFactoryInterface;

    /**
     * Creates an instance of ContainerInterface.
     *
     * @param array $configs an array of configurations
     */
    public function createContainer(): ContainerInterface;
}

// vim: syntax=php sw=4 ts=4 et:
