<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Adapter\ExtLdap;

use Korowai\Testing\Ldaplib\TestCase;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkOptionsMapper;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkOptionsMapperInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkOptionsMapper
 */
final class LdapLinkOptionsMapperTest extends TestCase
{

    public const DEFAULT_KEY_MAP = [
        'deref'              => LDAP_OPT_DEREF,
        'sizelimit'          => LDAP_OPT_SIZELIMIT,
        'timelimit'          => LDAP_OPT_TIMELIMIT,
        'network_timeout'    => LDAP_OPT_NETWORK_TIMEOUT,
        'protocol_version'   => LDAP_OPT_PROTOCOL_VERSION,
        'error_number'       => LDAP_OPT_ERROR_NUMBER,
        'referrals'          => LDAP_OPT_REFERRALS,
        'restart'            => LDAP_OPT_RESTART,
        'host_name'          => LDAP_OPT_HOST_NAME,
        'error_string'       => LDAP_OPT_ERROR_STRING,
        'diagnostic_message' => LDAP_OPT_DIAGNOSTIC_MESSAGE,
        'matched_dn'         => LDAP_OPT_MATCHED_DN,
        'server_controls'    => LDAP_OPT_SERVER_CONTROLS,
        'client_controls'    => LDAP_OPT_CLIENT_CONTROLS,
        'keepalive_idle'     => LDAP_OPT_X_KEEPALIVE_IDLE,
        'keepalive_probes'   => LDAP_OPT_X_KEEPALIVE_PROBES,
        'keepalive_interval' => LDAP_OPT_X_KEEPALIVE_INTERVAL,
        'sasl_mech'          => LDAP_OPT_X_SASL_MECH,
        'sasl_realm'         => LDAP_OPT_X_SASL_REALM,
        'sasl_authcid'       => LDAP_OPT_X_SASL_AUTHCID,
        'sasl_authzid'       => LDAP_OPT_X_SASL_AUTHZID,
        'tls_cacertdir'      => LDAP_OPT_X_TLS_CACERTDIR,
        'tls_cacertfile'     => LDAP_OPT_X_TLS_CACERTFILE,
        'tls_certfile'       => LDAP_OPT_X_TLS_CERTFILE,
        'tls_cipher_suite'   => LDAP_OPT_X_TLS_CIPHER_SUITE,
        'tls_crlcheck'       => LDAP_OPT_X_TLS_CRLCHECK,
        'tls_crlfile'        => LDAP_OPT_X_TLS_CRLFILE,
        'tls_dhfile'         => LDAP_OPT_X_TLS_DHFILE,
        'tls_keyfile'        => LDAP_OPT_X_TLS_KEYFILE,
        'tls_protocol_min'   => LDAP_OPT_X_TLS_PROTOCOL_MIN,
        'tls_random_file'    => LDAP_OPT_X_TLS_RANDOM_FILE,
        'tls_require_cert'   => LDAP_OPT_X_TLS_REQUIRE_CERT,
    ];

    //
    //
    // TESTS
    //
    //

    public function test__implements__LdapLinkOptionsMapperInterface() : void
    {
        $this->assertImplementsInterface(LdapLinkOptionsMapperInterface::class, LdapLinkOptionsMapper::class);
    }

    //
    // map()
    //

    public function test__map() : void
    {
        $names = array_keys(self::DEFAULT_KEY_MAP);
        $options = array_combine($names, $names);
        $expect  = array_flip(self::DEFAULT_KEY_MAP);

        $mapper = new LdapLinkOptionsMapper;

        $this->assertSame($expect, $mapper->map($options));
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
