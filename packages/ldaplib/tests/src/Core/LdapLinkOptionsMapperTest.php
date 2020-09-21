<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Core;

use Korowai\Testing\Ldaplib\TestCase;
use Korowai\Lib\Ldap\Core\LdapLinkOptionsMapper;
use Korowai\Lib\Ldap\Core\LdapLinkOptionsMapperInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Core\LdapLinkOptionsMapper
 */
final class LdapLinkOptionsMapperTest extends TestCase
{
    public const EXPECTED_MAPPINGS = [
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
     * Returns what we expect from LdapLinkOptionsMapper::getMappings().
     *
     * @return array
     */
    public static function getExpectedMappings() : array
    {
        $defined = array_filter(self::EXPECTED_MAPPINGS, 'defined');
        return array_map('constant', $defined);
    }

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
    // getMappings()
    //
    public function test__getMappings() : void
    {
        $mapper = new LdapLinkOptionsMapper;
        $expect = self::getExpectedMappings();
        $mappings = $mapper->getMappings();

        // FIXME: replace with self::assertEqualsKsorted() once it's implemented (see GH issue #3)
        ksort($expect);
        ksort($mappings);

        $this->assertSame($expect, $mappings);
    }

    //
    // mapOptions()
    //

    public function test__mapOptions() : void
    {
        $mappings = self::getExpectedMappings();

        $names = array_keys($mappings);
        $options = array_combine($names, $names);
        $expect  = array_flip($mappings);

        $mapper = new LdapLinkOptionsMapper;

        $this->assertSame($expect, $mapper->mapOptions($options));
    }

    public function test__mapOptions__throwsKeyErrorOnInvalidOption() : void
    {
        $mapper = new LdapLinkOptionsMapper;

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Option "inexistent" is not supported.');

        $mapper->mapOptions(['inexistent' => 'FOO']);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
