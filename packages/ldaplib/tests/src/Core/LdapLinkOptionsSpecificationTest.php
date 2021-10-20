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

use Korowai\Lib\Ldap\Core\LdapLinkOptionsMapper;
use Korowai\Lib\Ldap\Core\LdapLinkOptionsMapperInterface;
use Korowai\Lib\Ldap\Core\LdapLinkOptionsSpecification;
use Korowai\Lib\Ldap\Core\LdapLinkOptionsSpecificationInterface;
use Korowai\Testing\Ldaplib\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Core\LdapLinkOptionsSpecification
 *
 * @internal
 */
final class LdapLinkOptionsSpecificationTest extends TestCase
{
    use ImplementsInterfaceTrait;

    //
    //
    // TESTS
    //
    //

    public function testImplementsLdapLinkOptionsSpecificationInterface(): void
    {
        $this->assertImplementsInterface(
            LdapLinkOptionsSpecificationInterface::class,
            LdapLinkOptionsSpecification::class
        );
    }

    //
    // __construct()
    //

    public function testConstruct(): void
    {
        $mapper = $this->createMock(LdapLinkOptionsMapperInterface::class);
        $mapper->expects($this->once())
            ->method('getMappings')
            ->willReturn(['sizelimit' => null])
        ;
        $mapper->expects($this->never())
            ->method('mapOptions')
        ;

        $specs = new LdapLinkOptionsSpecification($mapper);
        $this->assertSame($mapper, $specs->getOptionsMapper());
        $this->assertSame(['sizelimit' => ['types' => 'int']], $specs->getOptions());
    }

    public static function provConfigureOptionsResolverThenResolve(): array
    {
        $defaults = ['protocol_version' => 3];

        $specs = [];

        // deref

        if (defined('LDAP_OPT_DEREF')) {
            $specs['deref'] = [
                'aliases' => [
                    'never' => LDAP_DEREF_NEVER,
                    'searching' => LDAP_DEREF_SEARCHING,
                    'finding' => LDAP_DEREF_FINDING,
                    'always' => LDAP_DEREF_ALWAYS,
                ],
            ];
        }

        // sizelimit

        if (defined('LDAP_OPT_SIZELIMIT')) {
            $specs['sizelimit'] = [
                'values' => [0, 123],
            ];
        }

        // timelimit

        if (defined('LDAP_OPT_TIMELIMIT')) {
            $specs['timelimit'] = [
                'values' => [0, 123],
            ];
        }

        // network_timeout

        if (defined('LDAP_OPT_NETWORK_TIMEOUT')) {
            $specs['network_timeout'] = [
                'values' => [0, 123],
            ];
        }

        // timeout

        if (defined('LDAP_OPT_TIMEOUT')) {
            $specs['timeout'] = [
                'values' => [0, 123],
            ];
        }

        // protocol_version

        if (defined('LDAP_OPT_PROTOCOL_VERSION')) {
            $specs['protocol_version'] = [
                'values' => [2, 3],
            ];
        }

        // error_number

        if (defined('LDAP_OPT_ERROR_NUMBER')) {
            $specs['error_number'] = [
                'values' => [123],
            ];
        }

        // referrals

        if (defined('LDAP_OPT_REFERRALS')) {
            $specs['referrals'] = [
                'values' => [true, false],
            ];
        }

        // restart

        if (defined('LDAP_OPT_RESTART')) {
            $specs['restart'] = [
                'values' => [true, false],
            ];
        }

        // host_name

        if (defined('LDAP_OPT_HOST_NAME')) {
            $specs['host_name'] = [
                'values' => ['example.org'],
            ];
        }

        // error_string

        if (defined('LDAP_OPT_ERROR_STRING')) {
            $specs['error_string'] = [
                'values' => ['error string'],
            ];
        }

        // matched_dn

        if (defined('LDAP_OPT_MATCHED_DN')) {
            $specs['matched_dn'] = [
                'values' => ['dc=example,dc=org'],
            ];
        }

        // server_controls

        if (defined('LDAP_OPT_SERVER_CONTROLS')) {
            $specs['server_controls'] = [
                'values' => [['server', 'controls']],
            ];
        }

        // client_controls

        if (defined('LDAP_OPT_CLIENT_CONTROLS')) {
            $specs['client_controls'] = [
                'values' => [['client', 'controls']],
            ];
        }

        // debug_level

        if (defined('LDAP_OPT_DEBUG_LEVEL')) {
            $specs['debug_level'] = [
                'values' => [123, -1],
            ];
        }

        // diagnostic_message

        if (defined('LDAP_OPT_DIAGNOSTIC_MESSAGE')) {
            $specs['diagnostic_message'] = [
                'values' => ['diagnostic message'],
            ];
        }

        // sasl_mech

        if (defined('LDAP_OPT_X_SASL_MECH')) {
            $specs['sasl_mech'] = [
                'values' => ['sasl mech'],
            ];
        }

        // sasl_realm

        if (defined('LDAP_OPT_X_SASL_REALM')) {
            $specs['sasl_realm'] = [
                'values' => ['sasl realm'],
            ];
        }

        // sasl_authcid

        if (defined('LDAP_OPT_X_SASL_AUTHCID')) {
            $specs['sasl_authcid'] = [
                'values' => ['sasl authcid'],
            ];
        }

        // sasl_authzid

        if (defined('LDAP_OPT_X_SASL_AUTHZID')) {
            $specs['sasl_authzid'] = [
                'values' => ['sasl authzid'],
            ];
        }

        // sasl_nocanon

        if (defined('LDAP_OPT_X_SASL_NOCANON')) {
            $specs['sasl_nocanon'] = [
                'values' => [true, false],
            ];
        }

        // sasl_username

        if (defined('LDAP_OPT_X_SASL_USERNAME')) {
            $specs['sasl_username'] = [
                'values' => ['sasl username'],
            ];
        }

        // tls_require_cert

        if (defined('LDAP_OPT_X_TLS_REQUIRE_CERT')) {
            $specs['tls_require_cert'] = [
                'aliases' => [
                    'never' => LDAP_OPT_X_TLS_NEVER,
                    'hard' => LDAP_OPT_X_TLS_HARD,
                    'demand' => LDAP_OPT_X_TLS_DEMAND,
                    'allow' => LDAP_OPT_X_TLS_ALLOW,
                    'try' => LDAP_OPT_X_TLS_TRY,
                ],
            ];
        }

        // tls_cacertdir

        if (defined('LDAP_OPT_X_TLS_CACERTDIR')) {
            $specs['tls_cacertdir'] = [
                'values' => ['tls cacertdir'],
            ];
        }

        // tls_cacertfile

        if (defined('LDAP_OPT_X_TLS_CACERTFILE')) {
            $specs['tls_cacertfile'] = [
                'values' => ['tls cacertfile'],
            ];
        }

        // tls_certfile

        if (defined('LDAP_OPT_X_TLS_CERTFILE')) {
            $specs['tls_certfile'] = [
                'values' => ['tls certificate'],
            ];
        }

        // tls_cipher_suite

        if (defined('LDAP_OPT_X_TLS_CIPHER_SUITE')) {
            $specs['tls_cipher_suite'] = [
                'values' => ['tls cipher suite'],
            ];
        }

        // tls_keyfile

        if (defined('LDAP_OPT_X_TLS_KEYFILE')) {
            $specs['tls_keyfile'] = [
                'values' => ['tls keyfile'],
            ];
        }

        // tls_random_file

        if (defined('LDAP_OPT_X_TLS_RANDOM_FILE')) {
            $specs['tls_random_file'] = [
                'values' => ['tls random file'],
            ];
        }

        // tls_crlcheck

        if (defined('LDAP_OPT_X_TLS_CRLCHECK')) {
            $specs['tls_crlcheck'] = [
                'aliases' => [
                    'none' => LDAP_OPT_X_TLS_CRL_NONE,
                    'peer' => LDAP_OPT_X_TLS_CRL_PEER,
                    'all' => LDAP_OPT_X_TLS_CRL_ALL,
                ],
            ];
        }

        // tls_dhfile

        if (defined('LDAP_OPT_X_TLS_DHFILE')) {
            $specs['tls_dhfile'] = [
                'values' => ['tls dhfile'],
            ];
        }

        // tls_crlfile

        if (defined('LDAP_OPT_X_TLS_CRLFILE')) {
            $specs['tls_crlfile'] = [
                'values' => ['tls crlfile'],
            ];
        }

        // tls_protocol_min

        if (defined('LDAP_OPT_X_TLS_PROTOCOL_MIN')) {
            $specs['tls_protocol_min'] = [
                'aliases' => [
                    'ssl2' => LDAP_OPT_X_TLS_PROTOCOL_SSL2,
                    'ssl3' => LDAP_OPT_X_TLS_PROTOCOL_SSL3,
                    'tls1.0' => LDAP_OPT_X_TLS_PROTOCOL_TLS1_0,
                    'tls1.1' => LDAP_OPT_X_TLS_PROTOCOL_TLS1_1,
                    'tls1.2' => LDAP_OPT_X_TLS_PROTOCOL_TLS1_2,
                ],
                'values' => [
                    (3 << 8) + 4,
                ],
            ];
        }

        // tls_package

        if (defined('LDAP_OPT_X_TLS_PACKAGE')) {
            $specs['tls_package'] = [
                'values' => ['tls package'],
            ];
        }

        // keepalive_idle

        if (defined('LDAP_OPT_X_KEEPALIVE_IDLE')) {
            $specs['keepalive_idle'] = [
                'values' => [123],
            ];
        }

        // keepalive_probes

        if (defined('LDAP_OPT_X_KEEPALIVE_PROBES')) {
            $specs['keepalive_probes'] = [
                'values' => [123],
            ];
        }

        // keepalive_interval

        if (defined('LDAP_OPT_X_KEEPALIVE_INTERVAL')) {
            $specs['keepalive_interval'] = [
                'values' => [123],
            ];
        }

        // generate cases

        $cases = [
            [
                'options' => [],
                'expect' => [],
            ],
        ];

        foreach ($specs as $name => $spec) {
            if (array_key_exists('aliases', $spec)) {
                foreach ($spec['aliases'] as $alias => $value) {
                    $cases[] = [
                        'options' => [$name => $alias],
                        'expect' => [$name => $value],
                    ];
                    $cases[] = [
                        'options' => [$name => $value],
                        'expect' => [$name => $value],
                    ];
                }
            }

            if (array_key_exists('values', $spec)) {
                foreach ($spec['values'] as $value) {
                    $cases[] = [
                        'options' => [$name => $value],
                        'expect' => [$name => $value],
                    ];
                }
            }
        }

        return array_map(function (array $case) use ($defaults): array {
            $case['expect'] += $defaults;

            return $case;
        }, $cases);
    }

    /**
     * @dataProvider provConfigureOptionsResolverThenResolve
     */
    public function testConfigureOptionsResolverThenResolve(array $options, array $expect): void
    {
        $specs = new LdapLinkOptionsSpecification(new LdapLinkOptionsMapper());
        $specs->configureOptionsResolver($resolver = new OptionsResolver());

        $resolved = $resolver->resolve($options);

        // FIXME: replace with self::assertEqualsKsorted() once it's implemented (see GH issue #3)
        ksort($resolved);
        ksort($expect);

        $this->assertSame($expect, $resolved);
    }
}

// vim: syntax=php sw=4 ts=4 et:
