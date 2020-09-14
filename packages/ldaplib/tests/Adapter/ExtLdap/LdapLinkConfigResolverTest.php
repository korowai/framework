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
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkConfigResolver;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkConfigResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkConfigResolver
 */
final class LdapLinkConfigResolverTest extends TestCase
{
    // FIXME: this is duplicated in LdapFactoryTest
    public static function getDefaultConfig()
    {
        return [
            'uri'  => 'ldap://localhost',
            'tls'  => false,
            'options' => [
                LDAP_OPT_PROTOCOL_VERSION => 3,
            ]
        ];
    }

    //
    //
    // TESTS
    //
    //

    public function test__implements__LdapLinkConfigResolverInterface() : void
    {
        $this->assertImplementsInterface(LdapLinkConfigResolverInterface::class, LdapLinkConfigResolver::class);
    }

    //
    // __construct()
    //

    public function test__construct__withoutCustomResolver() : void
    {
        $ldapOptionsResolver = new LdapLinkConfigResolver();
        $optionsResolver= $ldapOptionsResolver->getOptionsResolver();
        $this->assertInstanceOf(OptionsResolver::class, $optionsResolver);
    }

    public function test__construct__withCustomResolver() : void
    {
        $optionsResolver = new OptionsResolver;
        $ldapOptionsResolver = new LdapLinkConfigResolver($optionsResolver);
        $this->assertSame($optionsResolver, $ldapOptionsResolver->getOptionsResolver());
    }

    //
    // resolve()
    //

    public static function prov__resolve() : array
    {
        $defaults = static::getDefaultConfig();

        $validUris =  [
            'ldap://',
            'ldap:///',
            'ldap://localhost',
            'ldap://localhost/',
            'ldap://example.org/',
            'ldap://example.org:1/',
            'ldap://example.org:65535/',
            'ldap://192.168.0.10/',
            'ldap://example.org/dc=example,dc=org',
            'ldap://example.org/dc=example,dc=org?uid',
            'ldap://example.org/dc=example,dc=org?uid?one',
            'ldap://example.org/dc=example,dc=org??one',
            'ldap://example.org/dc=example,dc=org?uid?one?objectclass=*',
            'ldap://example.org/dc=example,dc=org?uid??objectclass=*',
            'ldap://example.org/dc=example,dc=org??one?objectclass=*',
            'ldap://example.org/dc=example,dc=org???objectclass=*',
            'ldap://example.org/dc=example,dc=org?uid?one?objectclass=*?1.2.3',
            'ldap://example.org/dc=example,dc=org?uid?one?objectclass=*?1.2.3=10,4.5.6',
            'ldap://example.org/dc=example,dc=org??one?objectclass=*?1.2.3=10,4.5.6',
            'ldap://example.org/dc=example,dc=org?uid??objectclass=*?1.2.3=10,4.5.6',
            'ldap://example.org/dc=example,dc=org?uid?one??1.2.3=10,4.5.6',
            'ldap://example.org/dc=example,dc=org???objectclass=*?1.2.3=10,4.5.6',
            'ldap://example.org/dc=example,dc=org?uid???1.2.3=10,4.5.6',
            'ldap://example.org/dc=example,dc=org????1.2.3=10,4.5.6',
        ];

        $validUriCases = array_map(function (string $uri) use ($defaults) : array {
            return [
                'config' => [
                    'uri' => $uri,
                ],
                'expect' => [
                    'uri' => $uri,
                ] + $defaults,
            ];
        }, $validUris);

        return array_merge([
            [
                'config' => [ ],
                'expect' => $defaults,
            ],
            [
                'config' => [
                    'tls' => true,
                ],
                'expect' => [
                    'tls' => true,
                ] + $defaults,
            ],
            [
                'config' => [
                    'options' => [
                        'sizelimit' => 123
                    ],
                ],
                'expect' => [
                    'options' => [
                        LDAP_OPT_PROTOCOL_VERSION => 3,
                        LDAP_OPT_SIZELIMIT => 123,
                    ],
                ] + $defaults
            ]
        ], $validUriCases);
    }

    /**
     * @dataProvider prov__resolve
     */
    public function test__resolve(array $config, array $expect) : void
    {
        $resolver = new LdapLinkConfigResolver;

        $resolved = $resolver->resolve($config);

        foreach ([&$resolved, &$expect] as &$options) {
            // FIXME: replace with assertEqualsKsorted() once it's implemented
            ksort($options);
            if (is_array($options['options'] ?? null)) {
                ksort($options['options']);
            }
        }

        $this->assertSame($expect, $resolved);
    }

    public static function prov__resolve__withInvalidConfig() : array
    {
        $invalidUris = [
            "",
            "ldap",
            "ldap:",
            "ldap:/",
            "ldap://localhost:",
            "ldap://localhost:0",
            "ldap://localhost:65536",
        ];

        $cases = array_map(function (string $uri) : array {
            return [
                'config' => [
                    'uri' => $uri,
                ],
                'expect' => [
                    'exception' => InvalidOptionsException::class,
                    'message' => 'The option "uri" with value "'.$uri.'" is invalid',
                ]
            ];
        }, $invalidUris);

        $cases[] = [
            'config' => [
                'tls' => 123,
            ],
            'expect' => [
                'exception' => InvalidOptionsException::class,
                'message' => 'The option "tls" with value 123 is expected to be of type "bool", but is of type "int"'
            ],
        ];

        return $cases;
    }

    /**
     * @dataProvider prov__resolve__withInvalidConfig
     */
    public function test__resolve__withInvalidConfig(array $config, array $expect) : void
    {
        $resolver = new LdapLinkConfigResolver;

        $this->expectException($expect['exception']);
        $this->expectExceptionMessage($expect['message']);
        $resolver->resolve($config);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=120:
