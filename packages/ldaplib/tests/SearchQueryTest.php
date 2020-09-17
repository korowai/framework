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
use Korowai\Testing\Ldaplib\ExamineWithLdapTriggerErrorTrait;

use Korowai\Lib\Ldap\SearchQuery;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperTrait;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkErrorHandler;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultInterface;
use Korowai\Lib\Ldap\ResultInterface;
use Korowai\Lib\Ldap\Exception\ErrorException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\SearchQuery
 */
final class SearchQueryTest extends TestCase
{
    use ExamineWithLdapTriggerErrorTrait;

    public const SCOPES_METHODS = [
        'base' => 'read',
        'one'  => 'list',
        'sub'  => 'search'
    ];

    //
    //
    // TESTS
    //
    //

    public function test__implements__LdapLinkWrapperInterface() : void
    {
        $this->assertImplementsInterface(LdapLinkWrapperInterface::class, SearchQuery::class);
    }

    public function test__implements__SearchQueryInterface() : void
    {
        $this->assertImplementsInterface(LdapLinkWrapperInterface::class, SearchQuery::class);
    }

    public function test__uses__LdapLinkWrapperTrait() : void
    {
        $this->assertUsesTrait(LdapLinkWrapperTrait::class, SearchQuery::class);
    }

    public static function prov__construct() : array
    {
        $cases = [
            // #0
            [
                'args' => ['dc=korowai,dc=org', 'objectClass=*'],
                'expect' => [
                    'properties' => [
                        'getBaseDn()' => 'dc=korowai,dc=org',
                        'getFilter()' => 'objectClass=*',
                    ],
                    'options' => SearchOptionsResolverTest::getDefaultOptions(),
                ],
            ],
        ];

        foreach(SearchOptionsResolverTest::prov__resolve() as $case) {
            $cases[] = [
                'args' => ['', '', $case['options']],
                'expect' => [
                    'options' => $case['expect'],
                ]
            ];
        }

        return $cases;
    }

    /**
     * @dataProvider prov__construct
     */
    public function test__construct(array $args, array $expect) : void
    {
        $link = $this->createMock(LdapLinkInterface::class);
        $query = new SearchQuery($link, ...$args);
        $this->assertSame($link, $query->getLdapLink());

        if (array_key_exists('properties', $expect)) {
            $this->assertHasPropertiesSameAs($expect['properties'], $query);
        }

        if (array_key_exists('options', $expect)) {
            // FIXME: use assertEqualsKsorted() once it's implemented (see GH issue #3).
            $expectOptions = $expect['options'];
            $actualOptions = $query->getOptions();
            ksort($expectOptions);
            ksort($actualOptions);
            $this->assertSame($expectOptions, $actualOptions);
        }
    }

    public static function prov__construct__withInvalidOptions() : array
    {
        return array_map(function (array $case) : array {
            return ['args' => ['', '', $case['options']], 'expect' => $case['expect']];
        }, SearchOptionsResolverTest::prov__resolve__withInvalidOptions());
    }

    /**
     * @dataProvider prov__construct__withInvalidOptions
     */
    public function test__construct__withInvalidOptions(array $args, array $expect) : void
    {
        $link = $this->createMock(LdapLinkInterface::class);

        $this->expectException($expect['exception']);
        $this->expectExceptionMessageMatches($expect['message']);

        new SearchQuery($link, ...$args);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // execute()/getResult()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__query() : array
    {
        $args = [
            'dc=korowai,dc=org',
            'objectClass=*',
            [
                'attributes' => ['foo'],
                'attrsOnly' => true,
                'sizeLimit' => 123,
                'timeLimit' => 456,
                'deref' => 'always'
            ]
        ];

        $expectArgs = [
            'dc=korowai,dc=org',
            'objectClass=*',
            ['foo'],
            1, 123, 456,
            LDAP_DEREF_ALWAYS
        ];

        $cases = [];
        foreach (['execute' => 2, 'getResult' => 1] as $method => $expectCalls) {
            $cases[] = [
                'method' => $method,
                'args'   => $args,
                'expect' => [
                    'calls'  => $expectCalls,
                    'method' => 'search',
                    'args'   => $expectArgs,
                ],
            ];
            foreach (self::SCOPES_METHODS as $scope => $expectMethod) {
                $case = [
                    'method' => $method,
                    'args' => $args,
                    'expect' => [
                        'calls'  => $expectCalls,
                        'method' => $expectMethod,
                        'args'   => $expectArgs,
                    ],
                ];
                $case['args'][2]['scope'] = $scope;
                $cases[] = $case;
            }
        }

        return $cases;
    }

    /**
     * @dataProvider prov__query
     */
    public function test__query(string $method, array $args, array $expect) : void
    {
        $link = $this->createMock(LdapLinkInterface::class);
        $ldapResult = $this->createMock(LdapResultInterface::class);
        $query = new SearchQuery($link, ...$args);
        $link->expects($this->exactly($expect['calls']))
             ->method($expect['method'])
             ->with(...$expect['args'])
             ->willReturn($ldapResult);
        $result = $query->$method();
        $result = $query->$method();
        $this->assertInstanceOf(ResultInterface::class, $result);
        $this->assertSame($ldapResult, $result->getLdapResult());
        if ($method !== 'getResult') {
            $this->assertSame($result, $query->getResult());
        }
    }

    public static function prov__query__withTriggerError() : array
    {
        $args = ['dc=example,dc=org', 'objectClass=*'];
        $expectArgs = ['dc=example,dc=org', 'objectClass=*', ['*'], 0, 0, 0, LDAP_DEREF_NEVER];

        $cases = [];
        foreach (['execute', 'getResult'] as $method) {
            foreach (self::SCOPES_METHODS as $scope => $expectMethod) {
                foreach (self::feedWithLdapTriggerError() as $feedCase) {
                    $case = [
                        'method' => $method,
                        'args'   => $args,
                        'config' => $feedCase['config'],
                        'expect' => $feedCase['expect'] + ['method' => $expectMethod, 'args' => $expectArgs],
                    ];
                    $case['args'][] = ['scope' => $scope];
                    $cases[] = $case;
                }
            }
        }
        return $cases;
    }

    /**
     * @dataProvider prov__query__withTriggerError
     */
    public function test__query__withTriggerError(string $method, array $args, array $config, array $expect) : void
    {
        $link = $this->createMock(LdapLinkInterface::class);
        $query = new SearchQuery($link, ...$args);

        $this->examineWithLdapTriggerError(
            [$query, $method],
            $link,
            $expect['method'],
            $expect['args'],
            $link,
            $config,
            $expect
        );
    }

    public static function prov__query__withLdapLinkReturningFalse() : array
    {
        $args = ['dc=example,dc=org', 'objectClass=*'];
        $expectArgs = ['dc=example,dc=org', 'objectClass=*', ['*'], 0, 0, 0, LDAP_DEREF_NEVER];

        $cases = [];
        foreach (['execute', 'getResult'] as $method) {
            foreach (self::SCOPES_METHODS as $scope => $expectMethod) {
                $case = [
                    'method' => $method,
                    'args'   => $args,
                    'expect' => ['method' => $expectMethod, 'args' => $expectArgs],
                ];
                $case['args'][] = ['scope' => $scope];
                $cases[] = $case;
            }
        }
        return $cases;
    }

    /**
     * @dataProvider prov__query__withLdapLinkReturningFalse
     */
    public function test__query__withLdapLinkReturningFalse(string $method, array $args, array $expect) : void
    {
        $link = $this->createMock(LdapLinkInterface::class);

        $link->expects($this->once())
             ->method($expect['method'])
             ->with(...$expect['args'])
             ->willReturn(false);

        $link->expects($this->once())
             ->method('getErrorHandler')
             ->with()
             ->willReturn($errorHandler = new LdapLinkErrorHandler($link));

        $query = new SearchQuery($link, ...$args);

        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('LdapLinkInterface::'.$expect['method'].'() returned false');

        $query->$method();
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
