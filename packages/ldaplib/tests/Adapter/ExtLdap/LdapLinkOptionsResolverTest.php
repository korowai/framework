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
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkOptionsResolver;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkOptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkOptionsResolver
 */
final class LdapLinkOptionsResolverTest extends TestCase
{
    // FIXME: this is duplicated in LdapFactoryTest
    public static function getDefaultOptions()
    {
        return [ LDAP_OPT_PROTOCOL_VERSION => 3 ];
    }

    public static function getDefinedOptions()
    {
        return [
            "client_controls",
            "deref",
            "diagnostic_message",
            "error_number",
            "error_string",
            "host_name",
            "keepalive_idle",
            "keepalive_interval",
            "keepalive_probes",
            "matched_dn",
            "network_timeout",
            "protocol_version",
            "referrals",
            "restart",
            "sasl_authcid",
            "sasl_authzid",
            "sasl_mech",
            "sasl_realm",
            "server_controls",
            "sizelimit",
            "timelimit",
            "tls_cacertdir",
            "tls_cacertfile",
            "tls_certfile",
            "tls_cipher_suite",
            "tls_crlcheck",
            "tls_crlfile",
            "tls_dhfile",
            "tls_keyfile",
            "tls_protocol_min",
            "tls_random_file",
            "tls_require_cert",
        ];
    }

    //
    //
    // TESTS
    //
    //

    public function test__implements__LdapLinkOptionsResolverInterface() : void
    {
        $this->assertImplementsInterface(LdapLinkOptionsResolverInterface::class, LdapLinkOptionsResolver::class);
    }

    //
    // __construct()
    //

    public function test__construct__withDefaultResolver() : void
    {
        $ldapOptionsResolver = new LdapLinkOptionsResolver();
        $optionsResolver= $ldapOptionsResolver->getOptionsResolver();
        $this->assertInstanceOf(OptionsResolver::class, $optionsResolver);
    }

    public function test__construct__withCustomResolver() : void
    {
        $optionsResolver = new OptionsResolver;
        $ldapOptionsResolver = new LdapLinkOptionsResolver($optionsResolver);
        $this->assertSame($optionsResolver, $ldapOptionsResolver->getOptionsResolver());
    }

    //
    // resolve()
    //

    public static function prov__resolve() : array
    {
        $defaults = static::getDefaultOptions();

        return [
            [
                'options' => [],
                'expect' => $defaults,
            ],
            [
                'options' => [
                    'sizelimit' => 123
                ],
                'expect' => [
                    LDAP_OPT_SIZELIMIT => 123,
                ] + $defaults,
            ]
        ];
    }

    /**
     * @dataProvider prov__resolve
     */
    public function test__resolve(array $options, array $expect) : void
    {
        $resolver = new LdapLinkOptionsResolver;

        $resolved = $resolver->resolve($options);

        // FIXME: replace with assertEqualsKsorted() once it's implemented
        ksort($resolved);
        ksort($expect);

        $this->assertSame($expect, $resolved);
    }

    public static function prov__resolve__withInvalidOptions() : array
    {
        return [
            // #0
            [
                'options' => [
                    'foo' => 'BAR',
                ],
                'expect' => [
                    'exception' => UndefinedOptionsException::class,
                    'message'   => 'The option "foo" does not exist. Defined options are: ' . implode(
                        ', ',
                        array_map(
                            function (string $s) : string {
                                return '"' . $s .'"';
                            },
                            self::getDefinedOptions()
                        )
                    ),
                ],
            ],

            // #1
            [
                'options' => [
                    'deref' => 123,
                ],
                'expect' => [
                    'exception' => InvalidOptionsException::class,
                    'message'   => 'The option "deref" with value 123 is invalid.',
                ],
            ],
        ];
    }

    /**
     * @dataProvider prov__resolve__withInvalidOptions
     */
    public function test__resolve__withInvalidOptions(array $options, array $expect) : void
    {
        $resolver = new LdapLinkOptionsResolver;

        $this->expectException($expect['exception']);
        $this->expectExceptionMessage($expect['message']);

        $resolver->resolve($options);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
