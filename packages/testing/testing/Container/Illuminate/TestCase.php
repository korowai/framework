<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Container\Illuminate;

use Korowai\Testing\Container\ContainerFactoryInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class TestCase extends \Korowai\Testing\Container\TestCase
{
    public function getContainerFactory() : ContainerFactoryInterface
    {
        return new ContainerFactory;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
