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

use Korowai\Lib\Ldap\Core\LdapLinkErrorHandler;
use Korowai\Lib\Ldap\Core\LdapLinkInterface;
use Korowai\Lib\Ldap\Core\LdapLinkWrapperInterface;
use Korowai\Lib\Ldap\Core\LdapLinkWrapperTrait;
use Korowai\Lib\Ldap\Core\LdapResultInterface;
use Korowai\Lib\Ldap\ErrorException;
use Korowai\Lib\Ldap\ResultInterface;
use Korowai\Lib\Ldap\SearchQuery;
use Korowai\Testing\Ldaplib\ExamineLdapLinkErrorHandlerTrait;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestFixture;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestSubject;
use Korowai\Testing\Ldaplib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\SearchQuery
 * @covers \Korowai\Testing\Ldaplib\ExamineLdapLinkErrorHandlerTrait
 *
 * @internal
 */
final class SearchQueryTest extends TestCase
{
    use ExamineLdapLinkErrorHandlerTrait;

    public const SCOPES_METHODS = [
        'base' => 'read',
        'one' => 'list',
        'sub' => 'search',
    ];

    //
    //
    // TESTS
    //
    //

    public function testImplementsLdapLinkWrapperInterface(): void
    {
        $this->assertImplementsInterface(LdapLinkWrapperInterface::class, SearchQuery::class);
    }

    public function testImplementsSearchQueryInterface(): void
    {
        $this->assertImplementsInterface(LdapLinkWrapperInterface::class, SearchQuery::class);
    }

    public function testUsesLdapLinkWrapperTrait(): void
    {
        $this->assertUsesTrait(LdapLinkWrapperTrait::class, SearchQuery::class);
    }

    public static function provConstruct(): array
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

        foreach (SearchOptionsResolverTest::provResolve() as $case) {
            $cases[] = [
                'args' => ['', '', $case['options']],
                'expect' => [
                    'options' => $case['expect'],
                ],
            ];
        }

        return $cases;
    }

    /**
     * @dataProvider provConstruct
     */
    public function testConstruct(array $args, array $expect): void
    {
        $link = $this->createMock(LdapLinkInterface::class);
        $query = new SearchQuery($link, ...$args);
        $this->assertSame($link, $query->getLdapLink());

        if (array_key_exists('properties', $expect)) {
            $this->assertObjectPropertiesIdenticalTo($expect['properties'], $query);
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

    public static function provConstructWithInvalidOptions(): array
    {
        return array_map(function (array $case): array {
            return ['args' => ['', '', $case['options']], 'expect' => $case['expect']];
        }, SearchOptionsResolverTest::provResolveWithInvalidOptions());
    }

    /**
     * @dataProvider provConstructWithInvalidOptions
     */
    public function testConstructWithInvalidOptions(array $args, array $expect): void
    {
        $link = $this->createMock(LdapLinkInterface::class);

        $this->expectException($expect['exception']);
        $this->expectExceptionMessageMatches($expect['message']);

        new SearchQuery($link, ...$args);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // execute()/getResult()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function provQuery(): array
    {
        $args = [
            'dc=korowai,dc=org',
            'objectClass=*',
            [
                'attributes' => ['foo'],
                'attrsOnly' => true,
                'sizeLimit' => 123,
                'timeLimit' => 456,
                'deref' => 'always',
            ],
        ];

        $expectArgs = [
            'dc=korowai,dc=org',
            'objectClass=*',
            ['foo'],
            1, 123, 456,
            LDAP_DEREF_ALWAYS,
        ];

        $cases = [];
        foreach (['execute' => 2, 'getResult' => 1] as $method => $expectCalls) {
            $cases[] = [
                'method' => $method,
                'args' => $args,
                'expect' => [
                    'calls' => $expectCalls,
                    'method' => 'search',
                    'args' => $expectArgs,
                ],
            ];
            foreach (self::SCOPES_METHODS as $scope => $expectMethod) {
                $case = [
                    'method' => $method,
                    'args' => $args,
                    'expect' => [
                        'calls' => $expectCalls,
                        'method' => $expectMethod,
                        'args' => $expectArgs,
                    ],
                ];
                $case['args'][2]['scope'] = $scope;
                $cases[] = $case;
            }
        }

        return $cases;
    }

    /**
     * @dataProvider provQuery
     */
    public function testQuery(string $method, array $args, array $expect): void
    {
        $link = $this->createMock(LdapLinkInterface::class);
        $ldapResult = $this->createMock(LdapResultInterface::class);
        $query = new SearchQuery($link, ...$args);
        $link->expects($this->exactly($expect['calls']))
            ->method($expect['method'])
            ->with(...$expect['args'])
            ->willReturn($ldapResult)
        ;
        $result = $query->{$method}();
        $result = $query->{$method}();
        $this->assertInstanceOf(ResultInterface::class, $result);
        $this->assertSame($ldapResult, $result->getLdapResult());
        if ('getResult' !== $method) {
            $this->assertSame($result, $query->getResult());
        }
    }

    public static function provQueryWithTriggerError(): array
    {
        $args = ['dc=example,dc=org', 'objectClass=*'];

        $cases = [];
        foreach (['execute', 'getResult'] as $method) {
            foreach (self::SCOPES_METHODS as $scope => $expectMethod) {
                foreach (self::feedLdapLinkErrorHandler() as $fixture) {
                    $case = [
                        'method' => $method,
                        'args' => $args,
                        'expect' => ['method' => $expectMethod],
                    ];
                    $case['args'][] = ['scope' => $scope];
                    $cases[] = array_merge($case, $fixture);
                }
            }
        }

        return $cases;
    }

    /**
     * @dataProvider provQueryWithTriggerError
     */
    public function testQueryWithTriggerError(
        string $method,
        array $args,
        array $expect,
        LdapTriggerErrorTestFixture $fixture
    ): void {
        $link = $this->createMock(LdapLinkInterface::class);
        $query = new SearchQuery($link, ...$args);

        $subject = new LdapTriggerErrorTestSubject($link, $expect['method']);
        $this->examineLdapLinkErrorHandler([$query, $method], $subject, $link, $fixture);
    }

    public static function provQueryWithLdapLinkReturningFalse(): array
    {
        $args = ['dc=example,dc=org', 'objectClass=*'];

        $cases = [];
        foreach (['execute', 'getResult'] as $method) {
            foreach (self::SCOPES_METHODS as $scope => $expectMethod) {
                $case = [
                    'method' => $method,
                    'args' => $args,
                    'expect' => ['method' => $expectMethod],
                ];
                $case['args'][] = ['scope' => $scope];
                $cases[] = $case;
            }
        }

        return $cases;
    }

    /**
     * @dataProvider provQueryWithLdapLinkReturningFalse
     */
    public function testQueryWithLdapLinkReturningFalse(string $method, array $args, array $expect): void
    {
        $link = $this->createMock(LdapLinkInterface::class);

        $link->expects($this->once())
            ->method($expect['method'])
            ->willReturn(false)
        ;

        $link->expects($this->once())
            ->method('getErrorHandler')
            ->willReturn($errorHandler = new LdapLinkErrorHandler($link))
        ;

        $query = new SearchQuery($link, ...$args);

        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('LdapLinkInterface::'.$expect['method'].'() returned false');

        $query->{$method}();
    }
}

// vim: syntax=php sw=4 ts=4 et:
