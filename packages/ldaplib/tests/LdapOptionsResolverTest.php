<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap;

use Korowai\Testing\Ldaplib\TestCase;
use Korowai\Lib\Ldap\LdapOptionsResolver;
use Korowai\Lib\Ldap\LdapOptionsResolverInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkOptionsTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapOptionsResolverTest extends TestCase
{
    public static function getDefaultConfig()
    {
        return [
            'host' => 'localhost',
            'port' => 389,
            'encryption' => 'none',
            'uri'  => 'ldap://localhost',
            'options' => [
                'protocol_version' => 3,
            ]
        ];
    }

    //
    //
    // TESTS
    //
    //

    public function test__implements__LdapOptionsResolverInterface() : void
    {
        $this->assertImplementsInterface(LdapOptionsResolverInterface::class, LdapOptionsResolver::class);
    }

    public function test__uses__LdapLinkOptionsTrait() : void
    {
        $this->assertUsesTrait(LdapLinkOptionsTrait::class, LdapOptionsResolver::class);
    }

    //
    // __construct()
    //

    public function test__construct__withoutCustomResolver() : void
    {
        $ldapOptionsResolver = new LdapOptionsResolver();
        $optionsResolver= $ldapOptionsResolver->getOptionsResolver();
        $this->assertInstanceOf(OptionsResolver::class, $optionsResolver);
    }

    public function test__construct__withCustomResolver() : void
    {
        $optionsResolver = new OptionsResolver;
        $ldapOptionsResolver = new LdapOptionsResolver($optionsResolver);
        $this->assertSame($optionsResolver, $ldapOptionsResolver->getOptionsResolver());
    }

    //
    // resolve()
    //

    public static function prov__resolve() : array
    {
        $defaults = static::getDefaultConfig();

        return [
            // #0
            [
                'config' => [ ],
                'expect' => $defaults,
            ],

            // #1
            [
                'config' => [
                    'host' => 'korowai.org',
                ],
                'expect' => array_merge($defaults, [
                    'host' => 'korowai.org',
                    'uri' => 'ldap://korowai.org',
                ]),
            ],

            // #2
            [
                'config' => [
                    'host' => 'korowai.org',
                    'encryption' => 'ssl',
                ],
                'expect' => array_merge($defaults, [
                    'host' => 'korowai.org',
                    'encryption' => 'ssl',
                    'uri' => 'ldaps://korowai.org',
                    'port' => 636,
                ]),
            ],

            // #3
            [
                'config' => [
                    'host' => 'korowai.org',
                    'encryption' => 'ssl',
                    'port' => 123,
                ],
                'expect' => array_merge($defaults, [
                    'host' => 'korowai.org',
                    'encryption' => 'ssl',
                    'uri' => 'ldaps://korowai.org:123',
                    'port' => 123,
                ]),
            ],

            // #4
            [
                'config' => [
                    'options' => [
                        'protocol_version' => 2,
                    ],
                ],
                'expect' => array_merge($defaults, [
                    'options' => [
                        'protocol_version' => 2,
                    ]
                ]),
            ],
        ];
    }

    /**
     * @dataProvider prov__resolve
     */
    public function test__resolve(array $config, array $expect) : void
    {
        $resolver = new LdapOptionsResolver;

        $resolved = $resolver->resolve($config);

        foreach ([&$resolved, &$expect] as &$options) {
            ksort($options);
            if (is_array($options['options'] ?? null)) {
                ksort($options['options']);
            }
        }

        $this->assertSame($expect, $resolved);
    }
}

// vim: syntax=php sw=4 ts=4 et:
