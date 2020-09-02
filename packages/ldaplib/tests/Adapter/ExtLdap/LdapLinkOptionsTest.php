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
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkOptions;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkOptions
 */
final class LdapLinkOptionsTest extends TestCase
{
    //
    //
    // TESTS
    //
    //

    public static function prov__getConstantName() : array
    {
        $cases = [
            ['deref', 'LDAP_OPT_DEREF'],
            ['sizelimit', 'LDAP_OPT_SIZELIMIT'],
            ['timelimit', 'LDAP_OPT_TIMELIMIT'],
            ['network_timeout', 'LDAP_OPT_NETWORK_TIMEOUT'],
            ['protocol_version', 'LDAP_OPT_PROTOCOL_VERSION'],
            ['error_number', 'LDAP_OPT_ERROR_NUMBER'],
            ['referrals', 'LDAP_OPT_REFERRALS'],
            ['restart', 'LDAP_OPT_RESTART'],
            ['host_name', 'LDAP_OPT_HOST_NAME'],
            ['error_string', 'LDAP_OPT_ERROR_STRING'],
            ['diagnostic_message', 'LDAP_OPT_DIAGNOSTIC_MESSAGE'],
            ['matched_dn', 'LDAP_OPT_MATCHED_DN'],
            ['server_controls', 'LDAP_OPT_SERVER_CONTROLS'],
            ['client_controls', 'LDAP_OPT_CLIENT_CONTROLS'],
        ];

        if (PHP_VERSION_ID >= 70100) {
            // Session
            $cases = array_merge($cases, [
                ['keepalive_idle', 'LDAP_OPT_X_KEEPALIVE_IDLE'],
                ['keepalive_probes', 'LDAP_OPT_X_KEEPALIVE_PROBES'],
                ['keepalive_interval', 'LDAP_OPT_X_KEEPALIVE_INTERVAL'],
            ]);
        }

        // SASL
        $cases = array_merge($cases, [
            ['sasl_mech', 'LDAP_OPT_X_SASL_MECH'],
            ['sasl_realm', 'LDAP_OPT_X_SASL_REALM'],
            ['sasl_authcid', 'LDAP_OPT_X_SASL_AUTHCID'],
            ['sasl_authzid', 'LDAP_OPT_X_SASL_AUTHZID'],
        ]);

        if (PHP_VERSION_ID >= 70100) {
            // TLS (API_VERSION > 2000)
            $cases = array_merge($cases, [
                ['tls_cacertdir', 'LDAP_OPT_X_TLS_CACERTDIR'],
                ['tls_cacertfile', 'LDAP_OPT_X_TLS_CACERTFILE'],
                ['tls_certfile', 'LDAP_OPT_X_TLS_CERTFILE'],
                ['tls_cipher_suite', 'LDAP_OPT_X_TLS_CIPHER_SUITE'],
                ['tls_crlcheck', 'LDAP_OPT_X_TLS_CRLCHECK'],
                ['tls_crlfile', 'LDAP_OPT_X_TLS_CRLFILE'],
                ['tls_dhfile', 'LDAP_OPT_X_TLS_DHFILE'],
                ['tls_keyfile', 'LDAP_OPT_X_TLS_KEYFILE'],
                ['tls_protocol_min', 'LDAP_OPT_X_TLS_PROTOCOL_MIN'],
                ['tls_random_file', 'LDAP_OPT_X_TLS_RANDOM_FILE'],
            ]);
        }

        if (PHP_VERSION_ID >= 70050) {
            $cases = array_merge($cases, [
                ['tls_require_cert', 'LDAP_OPT_X_TLS_REQUIRE_CERT'],
            ]);
        }

        return $cases;
    }

    /**
     * @dataProvider prov__getConstantName
     */
    public function test__getConstantName(string $name, $expect) : void
    {
        $this->assertSame($expect, LdapLinkOptions::getConstantName($name));
    }

    public function test__getConstantName__intexistent()
    {
        $this->assertNull(LdapLinkOptions::getConstantName('inexistent'));
    }

    public static function prov__getOptionId() : array
    {
        return array_map(function (array $case) {
            return [$case[0], constant($case[1])];
        }, static::prov__getConstantName());
    }

    /**
     * @dataProvider prov__getOptionId
     */
    public function test__getOptionId(string $arg, $expect)
    {
        $this->assertSame($expect, LdapLinkOptions::getOptionId($arg));
    }

    public function test__getOptionId__intexistent()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Unknown option 'inexistent'");
        LdapLinkOptions::getOptionId('inexistent');
    }

    public function test__getDeclarations()
    {
        $options =  LdapLinkOptions::getDeclarations();

        $v1 = ['never' => LDAP_DEREF_NEVER,
               'searching' => LDAP_DEREF_SEARCHING,
               'finding' => LDAP_DEREF_FINDING,
               'always' => LDAP_DEREF_ALWAYS];
        $v2 = ['none' => LDAP_OPT_X_TLS_CRL_NONE,
               'peer' => LDAP_OPT_X_TLS_CRL_PEER,
               'all' => LDAP_OPT_X_TLS_CRL_ALL];
        $v3 = ['never' => LDAP_OPT_X_TLS_NEVER,
               'hard' => LDAP_OPT_X_TLS_HARD,
               'demand' => LDAP_OPT_X_TLS_DEMAND,
               'allow' => LDAP_OPT_X_TLS_ALLOW,
               'try' => LDAP_OPT_X_TLS_TRY];

        $this->assertEquals(['types' => ['string','int'], 'constant' => 'LDAP_OPT_DEREF', 'values' => $v1], $options['deref']);
        $this->assertEquals(['types' => 'int',     'constant' => 'LDAP_OPT_SIZELIMIT'], $options['sizelimit']);
        $this->assertEquals(['types' => 'int',     'constant' => 'LDAP_OPT_TIMELIMIT'], $options['timelimit']);
        $this->assertEquals(['types' => 'int',     'constant' => 'LDAP_OPT_NETWORK_TIMEOUT'], $options['network_timeout']);
        $this->assertEquals(['types' => 'int',     'constant' => 'LDAP_OPT_PROTOCOL_VERSION', 'default' => 3, 'values' => [2, 3]], $options['protocol_version']);
        $this->assertEquals(['types' => 'int',     'constant' => 'LDAP_OPT_ERROR_NUMBER'], $options['error_number']);
        $this->assertEquals(['types' => 'bool',    'constant' => 'LDAP_OPT_REFERRALS'], $options['referrals']);
        $this->assertEquals(['types' => 'bool',    'constant' => 'LDAP_OPT_RESTART'], $options['restart']);
        $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_HOST_NAME'], $options['host_name']);
        $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_ERROR_STRING'], $options['error_string']);
        $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_DIAGNOSTIC_MESSAGE'], $options['diagnostic_message']);
        $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_MATCHED_DN'], $options['matched_dn']);
        $this->assertEquals(['types' => 'array',   'constant' => 'LDAP_OPT_SERVER_CONTROLS'], $options['server_controls']);
        $this->assertEquals(['types' => 'array',   'constant' => 'LDAP_OPT_CLIENT_CONTROLS'], $options['client_controls']);

        if (PHP_VERSION_ID >= 70100) {
            // Session
            $this->assertEquals(['types' => 'int', 'constant' => 'LDAP_OPT_X_KEEPALIVE_IDLE'], $options['keepalive_idle']);
            $this->assertEquals(['types' => 'int', 'constant' => 'LDAP_OPT_X_KEEPALIVE_PROBES'], $options['keepalive_probes']);
            $this->assertEquals(['types' => 'int', 'constant' => 'LDAP_OPT_X_KEEPALIVE_INTERVAL'], $options['keepalive_interval']);
        }

        // SASL
        $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_X_SASL_MECH'], $options['sasl_mech']);
        $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_X_SASL_REALM'], $options['sasl_realm']);
        $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_X_SASL_AUTHCID'], $options['sasl_authcid']);
        $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_X_SASL_AUTHZID'], $options['sasl_authzid']);

        if (PHP_VERSION_ID >= 70100) {
            // TLS (API_VERSION > 2000)
            $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_X_TLS_CACERTDIR'], $options['tls_cacertdir']);
            $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_X_TLS_CACERTFILE'], $options['tls_cacertfile']);
            $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_X_TLS_CERTFILE'], $options['tls_certfile']);
            $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_X_TLS_CIPHER_SUITE'], $options['tls_cipher_suite']);
            $this->assertEquals(['types' => ['string', 'int'], 'constant' => 'LDAP_OPT_X_TLS_CRLCHECK', 'values' => $v2], $options['tls_crlcheck']);
            $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_X_TLS_CRLFILE'], $options['tls_crlfile']);
            $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_X_TLS_DHFILE'], $options['tls_dhfile']);
            $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_X_TLS_KEYFILE'], $options['tls_keyfile']);
            $this->assertEquals(['types' => 'int',     'constant' => 'LDAP_OPT_X_TLS_PROTOCOL_MIN'], $options['tls_protocol_min']);
            $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_X_TLS_RANDOM_FILE'], $options['tls_random_file']);
        }

        if (PHP_VERSION_ID >= 70050) {
            $this->assertEquals(['types' => ['string', 'int'], 'constant' => 'LDAP_OPT_X_TLS_REQUIRE_CERT', 'values' => $v3], $options['tls_require_cert']);
        }
    }

    public function test__configureOptionsResolver()
    {
        LdapLinkOptions::configureOptionsResolver($resolver = new OptionsResolver);

        $this->assertTrue($resolver->isDefined('deref'));
        $this->assertTrue($resolver->isDefined('sizelimit'));
        $this->assertTrue($resolver->isDefined('timelimit'));
        $this->assertTrue($resolver->isDefined('network_timeout'));
        $this->assertTrue($resolver->isDefined('protocol_version'));
        $this->assertTrue($resolver->isDefined('error_number'));
        $this->assertTrue($resolver->isDefined('referrals'));
        $this->assertTrue($resolver->isDefined('restart'));
        $this->assertTrue($resolver->isDefined('host_name'));
        $this->assertTrue($resolver->isDefined('error_string'));
        $this->assertTrue($resolver->isDefined('diagnostic_message'));
        $this->assertTrue($resolver->isDefined('matched_dn'));
        $this->assertTrue($resolver->isDefined('server_controls'));
        $this->assertTrue($resolver->isDefined('client_controls'));

        if (PHP_VERSION_ID >= 70100) {
            // Session
            $this->assertTrue($resolver->isDefined('keepalive_idle'));
            $this->assertTrue($resolver->isDefined('keepalive_probes'));
            $this->assertTrue($resolver->isDefined('keepalive_interval'));
        }

        // SASL
        $this->assertTrue($resolver->isDefined('sasl_mech'));
        $this->assertTrue($resolver->isDefined('sasl_realm'));
        $this->assertTrue($resolver->isDefined('sasl_authcid'));
        $this->assertTrue($resolver->isDefined('sasl_authzid'));

        if (PHP_VERSION_ID >= 70100) {
            // TLS (API_VERSION > 2000)
            $this->assertTrue($resolver->isDefined('tls_cacertdir'));
            $this->assertTrue($resolver->isDefined('tls_cacertfile'));
            $this->assertTrue($resolver->isDefined('tls_certfile'));
            $this->assertTrue($resolver->isDefined('tls_cipher_suite'));
            $this->assertTrue($resolver->isDefined('tls_crlcheck'));
            $this->assertTrue($resolver->isDefined('tls_crlfile'));
            $this->assertTrue($resolver->isDefined('tls_dhfile'));
            $this->assertTrue($resolver->isDefined('tls_keyfile'));
            $this->assertTrue($resolver->isDefined('tls_protocol_min'));
            $this->assertTrue($resolver->isDefined('tls_random_file'));
        }

        if (PHP_VERSION_ID >= 70050) {
            $this->assertTrue($resolver->isDefined('tls_require_cert'));
        }
    }

    public function test__configureOptionsResolver__defaults()
    {
        LdapLinkOptions::configureOptionsResolver($resolver = new OptionsResolver);
        $this->assertSame(['protocol_version' => 3], $resolver->resolve([]));
    }

    public function test__configureOption__withNonArrayValues()
    {
        $decl = [
            'types' => 'string',
            'values' => function ($s) {
                return substr($s, 0, 3) === 'foo';
            }
        ];
        $resolver = new OptionsResolver;
        LdapLinkOptions::configureOption($resolver, 'foo', $decl);

        $this->assertEquals(['foo' => 'foomatic'], $resolver->resolve(['foo' => 'foomatic']));

        $this->expectException(InvalidOptionsException::class);
        $resolver->resolve(['foo' => 'barmatic']);
    }

    public function test__option__deref()
    {
        LdapLinkOptions::configureOptionsResolver($resolver = new OptionsResolver);

        $this->assertNull(($resolver->resolve([]))['deref'] ?? null);

        // never
        $this->assertEquals(LDAP_DEREF_NEVER, $resolver->resolve(['deref' => LDAP_DEREF_NEVER])['deref']);
        $this->assertEquals(LDAP_DEREF_NEVER, $resolver->resolve(['deref' => 'never'])['deref']);
        // searching
        $this->assertEquals(LDAP_DEREF_SEARCHING, $resolver->resolve(['deref' => LDAP_DEREF_SEARCHING])['deref']);
        $this->assertEquals(LDAP_DEREF_SEARCHING, $resolver->resolve(['deref' => 'searching'])['deref']);
        // finding
        $this->assertEquals(LDAP_DEREF_FINDING, $resolver->resolve(['deref' => LDAP_DEREF_FINDING])['deref']);
        $this->assertEquals(LDAP_DEREF_FINDING, $resolver->resolve(['deref' => 'finding'])['deref']);
        // always
        $this->assertEquals(LDAP_DEREF_ALWAYS, $resolver->resolve(['deref' => LDAP_DEREF_ALWAYS])['deref']);
        $this->assertEquals(LDAP_DEREF_ALWAYS, $resolver->resolve(['deref' => 'always'])['deref']);
    }

    public function test__option__tls_crlcheck()
    {
        LdapLinkOptions::configureOptionsResolver($resolver = new OptionsResolver);

        $this->assertNull(($resolver->resolve([]))['tls_crlcheck'] ?? null);

        if (PHP_VERSION_ID >= 70100) {
            // none
            $this->assertEquals(LDAP_OPT_X_TLS_CRL_NONE, $resolver->resolve(['tls_crlcheck' => LDAP_OPT_X_TLS_CRL_NONE])['tls_crlcheck']);
            $this->assertEquals(LDAP_OPT_X_TLS_CRL_NONE, $resolver->resolve(['tls_crlcheck' => 'none'])['tls_crlcheck']);
            // peer
            $this->assertEquals(LDAP_OPT_X_TLS_CRL_PEER, $resolver->resolve(['tls_crlcheck' => LDAP_OPT_X_TLS_CRL_PEER])['tls_crlcheck']);
            $this->assertEquals(LDAP_OPT_X_TLS_CRL_PEER, $resolver->resolve(['tls_crlcheck' => 'peer'])['tls_crlcheck']);
            // all
            $this->assertEquals(LDAP_OPT_X_TLS_CRL_ALL, $resolver->resolve(['tls_crlcheck' => LDAP_OPT_X_TLS_CRL_ALL])['tls_crlcheck']);
            $this->assertEquals(LDAP_OPT_X_TLS_CRL_ALL, $resolver->resolve(['tls_crlcheck' => 'all'])['tls_crlcheck']);
        }
    }

    public function test__option__tls_require_cert()
    {
        LdapLinkOptions::configureOptionsResolver($resolver = new OptionsResolver);

        $this->assertNull(($resolver->resolve([]))['tls_require_cert'] ?? null);

        if (PHP_VERSION_ID >= 70050) {
            // never
            $this->assertEquals(LDAP_OPT_X_TLS_NEVER, $resolver->resolve(['tls_require_cert' => LDAP_OPT_X_TLS_NEVER])['tls_require_cert']);
            $this->assertEquals(LDAP_OPT_X_TLS_NEVER, $resolver->resolve(['tls_require_cert' => 'never'])['tls_require_cert']);
            // hard
            $this->assertEquals(LDAP_OPT_X_TLS_HARD, $resolver->resolve(['tls_require_cert' => LDAP_OPT_X_TLS_HARD])['tls_require_cert']);
            $this->assertEquals(LDAP_OPT_X_TLS_HARD, $resolver->resolve(['tls_require_cert' => 'hard'])['tls_require_cert']);
            // demand
            $this->assertEquals(LDAP_OPT_X_TLS_DEMAND, $resolver->resolve(['tls_require_cert' => LDAP_OPT_X_TLS_DEMAND])['tls_require_cert']);
            $this->assertEquals(LDAP_OPT_X_TLS_DEMAND, $resolver->resolve(['tls_require_cert' => 'demand'])['tls_require_cert']);
            // allow
            $this->assertEquals(LDAP_OPT_X_TLS_ALLOW, $resolver->resolve(['tls_require_cert' => LDAP_OPT_X_TLS_ALLOW])['tls_require_cert']);
            $this->assertEquals(LDAP_OPT_X_TLS_ALLOW, $resolver->resolve(['tls_require_cert' => 'allow'])['tls_require_cert']);
            // try
            $this->assertEquals(LDAP_OPT_X_TLS_TRY, $resolver->resolve(['tls_require_cert' => LDAP_OPT_X_TLS_TRY])['tls_require_cert']);
            $this->assertEquals(LDAP_OPT_X_TLS_TRY, $resolver->resolve(['tls_require_cert' => 'try'])['tls_require_cert']);
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
