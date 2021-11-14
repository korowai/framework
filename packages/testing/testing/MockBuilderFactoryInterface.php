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

/**
 * Cretes instances of MockBuilderInterface.
 *
 * @no-named-arguments Parameter names ore not coverted by the backward compatibility promise
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface MockBuilderFactoryInterface
{
    /**
     * Creates an instance of MockBuilderInterface preconfigured with $config.
     */
    public function getMockBuilder(MockBuilderConfigInterface $config): MockBuilderInterface;
}

// vim: syntax=php sw=4 ts=4 et:
