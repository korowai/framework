<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter;

/**
 * Creates instances of AdapterInterface
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface AdapterFactoryInterface
{
    /**
     * Set configuration for later use by createAdapter().
     *
     * @param  array $config Configuration options used to configure every new
     *                      adapter instance created by createAdapter().
     */
    public function configure(array $config);

    /**
     * Creates and returns an LDAP adapter
     *
     * The returned adapter is configured with config provided to configure().
     * Several instances of AdapterInterface may be created with same config.
     *
     * @return AdapterInterface
     */
    public function createAdapter() : AdapterInterface;
}

// vim: syntax=php sw=4 ts=4 et:
