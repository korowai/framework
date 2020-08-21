<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap;

/**
 * Parameters for LDAP connection.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface ConnectionParametersInterface
{
    /**
     * The constant representing unencrypted connection.
     */
    public const ENC_NONE   = 'none';

    /**
     * The constant represeting SSL-encrypted connection.
     */
    public const ENC_SSL    = 'ssl';

    /**
     * The constant representing TLS-encrypted connection.
     */
    public const ENC_TLS    = 'tls';

    /**
     * Returns the configured LDAP host.
     *
     * For non-IP protocols (such as filesystem socket) the function shall
     * return an empty string.
     *
     * @return string
     *
     * @psalm-mutation-free
     */
    public function host() : string;

    /**
     * Returns the configured LDAP port.
     *
     * For non-IP protocols (such as filesystem socket) the function shall
     * return 0. Otherwise, the returned value shall be between 1 and 65535,
     * inclusive.
     *
     * @return int
     *
     * @psalm-mutation-free
     */
    public function port() : int;

    /**
     * Returns the configured encryption.
     *
     * @return string one of the ``ENC_NONE``, ``ENC_SSL`` or ``ENC_TLS``.
     *
     * @psalm-mutation-free
     */
    public function encryption() : string;

    /**
     * Return the configured LDAP URI.
     *
     * The returned URI shall be consistent with the ``host()``, ``port()`` and
     * ``encryption()``.
     *
     * @return string
     *
     * @psalm-mutation-free
     */
    public function uri() : string;

    /**
     * Returns provider-specific options to be set to the connection.
     *
     * @return array
     *
     * @psalm-mutation-free
     */
    public function options() : array;
}

// vim: syntax=php sw=4 ts=4 et:
