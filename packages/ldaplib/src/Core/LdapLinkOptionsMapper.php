<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Core;

/**
 * Maps user-friendly option names onto integer identifiers suitable for ldap_set_option().
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapLinkOptionsMapper implements LdapLinkOptionsMapperInterface
{
    /**
     * Predefined mappings for all the options. The array maps user-friendly options names onto their corresponding PHP
     * constant names. Some of the constants may be undefined depending on PHP version and the version of LDAP API used
     * by ext-ldap, so the array will be the subject of further filtering.
     */
    public const MAPPINGS = [
        'deref'              => 'LDAP_OPT_DEREF',
        'sizelimit'          => 'LDAP_OPT_SIZELIMIT',
        'timelimit'          => 'LDAP_OPT_TIMELIMIT',
        'network_timeout'    => 'LDAP_OPT_NETWORK_TIMEOUT',
        'timeout'            => 'LDAP_OPT_TIMEOUT',
        'protocol_version'   => 'LDAP_OPT_PROTOCOL_VERSION',
        'error_number'       => 'LDAP_OPT_ERROR_NUMBER',
        'referrals'          => 'LDAP_OPT_REFERRALS',
        'restart'            => 'LDAP_OPT_RESTART',
        'host_name'          => 'LDAP_OPT_HOST_NAME',
        'error_string'       => 'LDAP_OPT_ERROR_STRING',
        'matched_dn'         => 'LDAP_OPT_MATCHED_DN',
        'server_controls'    => 'LDAP_OPT_SERVER_CONTROLS',
        'client_controls'    => 'LDAP_OPT_CLIENT_CONTROLS',
        'debug_level'        => 'LDAP_OPT_DEBUG_LEVEL',
        'diagnostic_message' => 'LDAP_OPT_DIAGNOSTIC_MESSAGE',
        'sasl_mech'          => 'LDAP_OPT_X_SASL_MECH',
        'sasl_realm'         => 'LDAP_OPT_X_SASL_REALM',
        'sasl_authcid'       => 'LDAP_OPT_X_SASL_AUTHCID',
        'sasl_authzid'       => 'LDAP_OPT_X_SASL_AUTHZID',
        'sasl_nocanon'       => 'LDAP_OPT_X_SASL_NOCANON',
        'sasl_username'      => 'LDAP_OPT_X_SASL_USERNAME',
        'tls_require_cert'   => 'LDAP_OPT_X_TLS_REQUIRE_CERT',
        'tls_cacertdir'      => 'LDAP_OPT_X_TLS_CACERTDIR',
        'tls_cacertfile'     => 'LDAP_OPT_X_TLS_CACERTFILE',
        'tls_certfile'       => 'LDAP_OPT_X_TLS_CERTFILE',
        'tls_cipher_suite'   => 'LDAP_OPT_X_TLS_CIPHER_SUITE',
        'tls_keyfile'        => 'LDAP_OPT_X_TLS_KEYFILE',
        'tls_random_file'    => 'LDAP_OPT_X_TLS_RANDOM_FILE',
        'tls_crlcheck'       => 'LDAP_OPT_X_TLS_CRLCHECK',
        'tls_dhfile'         => 'LDAP_OPT_X_TLS_DHFILE',
        'tls_crlfile'        => 'LDAP_OPT_X_TLS_CRLFILE',
        'tls_protocol_min'   => 'LDAP_OPT_X_TLS_PROTOCOL_MIN',
        'tls_package'        => 'LDAP_OPT_X_TLS_PACKAGE',
        'keepalive_idle'     => 'LDAP_OPT_X_KEEPALIVE_IDLE',
        'keepalive_probes'   => 'LDAP_OPT_X_KEEPALIVE_PROBES',
        'keepalive_interval' => 'LDAP_OPT_X_KEEPALIVE_INTERVAL',
    ];

    /**
     * @var array
     *
     * @psalm-readonly
     */
    private $mappings;

    /**
     * Initializes the object
     */
    public function __construct()
    {
        $this->mappings = self::getSupportedMappings();
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-mutation-free
     */
    public function getMappings() : array
    {
        return $this->mappings;
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-mutation-free
     */
    public function mapOptions(array $options) : array
    {
        $mapped = [];
        foreach ($options as $name => $value) {
            if (($id = $this->mappings[$name] ?? null) === null) {
                // FIXME: dedicated exception?
                throw new \InvalidArgumentException('Option "'.$name.'" is not supported.');
            }
            $mapped[$id] = $value;
        }
        return $mapped;
    }

    /**
     * Returns self::MAPPING after appropriate filtering.
     *
     * @return array
     *
     * @psalm-mutation-free
     */
    private static function getSupportedMappings() : array
    {
        $defined = array_filter(self::MAPPINGS, 'defined');
        return array_map('constant', $defined);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
