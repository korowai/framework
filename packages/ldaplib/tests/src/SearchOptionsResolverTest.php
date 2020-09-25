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

use Korowai\Lib\Ldap\SearchOptionsResolver;
use Korowai\Testing\Ldaplib\TestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\SearchOptionsResolver
 *
 * @internal
 */
final class SearchOptionsResolverTest extends TestCase
{
    public static function getDefaultOptions(): array
    {
        return [
            'attributes' => ['*'],
            'attrsOnly' => 0,
            'deref' => LDAP_DEREF_NEVER,
            'scope' => 'sub',
            'sizeLimit' => 0,
            'timeLimit' => 0,
        ];
    }

    //
    //
    // TESTS
    //
    //

    //
    // __construct()
    //

    public function testConstructWithoutCustomResolver(): void
    {
        $ldapOptionsResolver = new SearchOptionsResolver();
        $optionsResolver = $ldapOptionsResolver->getOptionsResolver();
        $this->assertInstanceOf(OptionsResolver::class, $optionsResolver);
    }

    public function testConstructWithCustomResolver(): void
    {
        $optionsResolver = new OptionsResolver();
        $ldapOptionsResolver = new SearchOptionsResolver($optionsResolver);
        $this->assertSame($optionsResolver, $ldapOptionsResolver->getOptionsResolver());
    }

    //
    // resolve()
    //

    public static function provResolve(): array
    {
        $defaults = self::getDefaultOptions();

        $cases = [
            // #1
            [
                'options' => [],
                'expect' => [],
            ],

            // #2
            [
                'options' => ['attributes' => []],
                'expect' => ['attributes' => []],
            ],

            // #3
            [
                'options' => ['attributes' => ['foo']],
                'expect' => ['attributes' => ['foo']],
            ],

            // #3
            [
                'options' => ['attributes' => ['foo', 'bar']],
                'expect' => ['attributes' => ['foo', 'bar']],
            ],

            // #4
            [
                'options' => ['attributes' => 'foo'],
                'expect' => ['attributes' => ['foo']],
            ],

            // #5
            [
                'options' => ['attrsOnly' => false],
                'expect' => ['attrsOnly' => 0],
            ],

            // #6
            [
                'options' => ['attrsOnly' => true],
                'expect' => ['attrsOnly' => 1],
            ],

            // #7
            [
                'options' => ['attrsOnly' => 0],
                'expect' => ['attrsOnly' => 0],
            ],

            // #8
            [
                'options' => ['attrsOnly' => 1],
                'expect' => ['attrsOnly' => 1],
            ],

            // #9
            [
                'options' => ['attrsOnly' => 123],
                'expect' => ['attrsOnly' => 1],
            ],

            // #10
            [
                'options' => ['deref' => 'always'],
                'expect' => ['deref' => LDAP_DEREF_ALWAYS],
            ],

            // #11
            [
                'options' => ['deref' => 'never'],
                'expect' => ['deref' => LDAP_DEREF_NEVER],
            ],

            // #12
            [
                'options' => ['deref' => 'finding'],
                'expect' => ['deref' => LDAP_DEREF_FINDING],
            ],

            // #13
            [
                'options' => ['deref' => 'searching'],
                'expect' => ['deref' => LDAP_DEREF_SEARCHING],
            ],

            // #14
            [
                'options' => ['scope' => 'base'],
                'expect' => ['scope' => 'base'],
            ],

            // #15
            [
                'options' => ['scope' => 'one'],
                'expect' => ['scope' => 'one'],
            ],

            // #16
            [
                'options' => ['scope' => 'sub'],
                'expect' => ['scope' => 'sub'],
            ],

            // #17
            [
                'options' => ['sizeLimit' => 123],
                'expect' => ['sizeLimit' => 123],
            ],

            // #18
            [
                'options' => ['sizeLimit' => 0],
                'expect' => ['sizeLimit' => 0],
            ],

            // #19
            [
                'options' => ['sizeLimit' => -1],
                'expect' => ['sizeLimit' => -1],
            ],

            // #20
            [
                'options' => ['timeLimit' => 123],
                'expect' => ['timeLimit' => 123],
            ],

            // #21
            [
                'options' => ['timeLimit' => 0],
                'expect' => ['timeLimit' => 0],
            ],

            // #22
            [
                'options' => ['timeLimit' => -1],
                'expect' => ['timeLimit' => -1],
            ],
        ];

        return array_map(function (array $case) use ($defaults): array {
            $case['expect'] = array_merge($defaults, $case['expect']);

            return $case;
        }, $cases);
    }

    /**
     * @dataProvider provResolve
     */
    public function testResolve(array $options, array $expect): void
    {
        $resolver = new SearchOptionsResolver();

        // FIXME: use assertEqualsKsorted() once it's implemented (see GH issue #3).
        $actual = $resolver->resolve($options);
        ksort($expect);
        ksort($actual);
        $this->assertSame($expect, $actual);
    }

    public static function provResolveWithInvalidOptions(): array
    {
        return [
            // #0
            [
                'options' => ['foo' => 'bar'],
                'expect' => [
                    'exception' => UndefinedOptionsException::class,
                    'message' => '/option "foo" does not exist/',
                ],
            ],

            // #1
            [
                'options' => ['foo' => false, 'bar' => true],
                'expect' => [
                    'exception' => UndefinedOptionsException::class,
                    'message' => '/options "bar", "foo" do not exist/',
                ],
            ],

            // #2
            [
                'options' => ['attributes' => 123],
                'expect' => [
                    'exception' => InvalidOptionsException::class,
                    'message' => '/option "attributes" with value 123 is expected to be of type "string" or '.
                                   '"array", but is of type "int"/',
                ],
            ],

            // #3
            [
                'options' => ['attrsOnly' => 'foo'],
                'expect' => [
                    'exception' => InvalidOptionsException::class,
                    'message' => '/option "attrsOnly" with value "foo" is expected to be of type "bool" or '.
                                   '"int", but is of type "string"/',
                ],
            ],

            // #4
            [
                'options' => ['deref' => 'foo'],
                'expect' => [
                    'exception' => InvalidOptionsException::class,
                    'message' => '/option "deref" with value "foo" is invalid. Accepted values are: '.
                                   '"always", "never", "finding", "searching", '.LDAP_DEREF_ALWAYS.', '.
                                   LDAP_DEREF_NEVER.', '.LDAP_DEREF_FINDING.', '.LDAP_DEREF_SEARCHING.'/',
                ],
            ],

            // #5
            [
                'options' => ['scope' => 'foo'],
                'expect' => [
                    'exception' => InvalidOptionsException::class,
                    'message' => '/option "scope" with value "foo" is invalid. Accepted values are: "base", '.
                                   '"one", "sub"/',
                ],
            ],

            // #6
            [
                'options' => ['sizeLimit' => 'foo'],
                'expect' => [
                    'exception' => InvalidOptionsException::class,
                    'message' => '/option "sizeLimit" with value "foo" is expected to be of type "int", but '.
                                   'is of type "string"/',
                ],
            ],

            // #7
            [
                'options' => ['timeLimit' => 'foo'],
                'expect' => [
                    'exception' => InvalidOptionsException::class,
                    'message' => '/option "timeLimit" with value "foo" is expected to be of type "int", but '.
                                   'is of type "string"/',
                ],
            ],
        ];
    }

    /**
     * @dataProvider provResolveWithInvalidOptions
     */
    public function testResolveWithInvalidOptions(array $options, array $expect): void
    {
        $resolver = new SearchOptionsResolver();

        $this->expectException($expect['exception']);
        $this->expectExceptionMessageMatches($expect['message']);

        $resolver->resolve($options);
    }
}

// vim: syntax=php sw=4 ts=4 et:
