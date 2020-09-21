<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Container\Symfony;

use Korowai\Testing\Container\ContainerFactoryInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class TestCase extends \Korowai\Testing\Container\TestCase
{
    public function getContainerFactory() : ContainerFactoryInterface
    {
        return (new ContainerFactory)->setServicesVisibility($this->getServicesVisibility());
    }

    /**
     * Override this method in subclass to specify which services should be made publicly visible in the container.
     *
     * @return array
     */
    public function getServicesVisibility() : array
    {
        return [];
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
