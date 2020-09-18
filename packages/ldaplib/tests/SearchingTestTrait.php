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

use PHPUnit\Framework\MockObject\MockObject;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestFixture;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestSubject;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkErrorHandler;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultInterface;
use Korowai\Lib\Ldap\Exception\ErrorException;
use Korowai\Lib\Ldap\Result;
use Korowai\Lib\Ldap\ResultInterface;
use Korowai\Lib\Ldap\SearchQuery;
use Korowai\Lib\Ldap\SearchingInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait SearchingTestTrait
{
    abstract public function createSearchingInstance(LdapLinkInterface $ldapLink) : SearchingInterface;

    abstract public function examineLdapLinkErrorHandler(
        callable $function,
        LdapTriggerErrorTestSubject $subject,
        MockObject $link,
        LdapTriggerErrorTestFixture $fixture
    ) : void;

    abstract public static function feedLdapLinkErrorHandler() : array;

    abstract public function createMock(string $name);

    //
    //
    // TESTS
    //
    //

    //
    // createSearchQuery()
    //
    public static function prov__createSearchQuery() : array
    {
        return [
            // #0
            [
                'args'   => ['dc=example,dc=org', 'objectClass=*'],
                'expect' => [
                    'properties' => [
                        'getBaseDn()'  => 'dc=example,dc=org',
                        'getFilter()'  => 'objectClass=*',
                    ],
                    'options' => [
                        'attributes' => ['*'],
                        'attrsOnly' => 0,
                        'deref' => LDAP_DEREF_NEVER,
                        'scope' => 'sub',
                        'sizeLimit' => 0,
                        'timeLimit' => 0,
                    ],
                ],
            ],
            // #1
            [
                'args'   => ['dc=example,dc=org', 'objectClass=*', [
                    'attributes' => '*',
                    'attrsOnly'  => true,
                    'deref'      => 'always',
                    'scope'      => 'one',
                    'sizeLimit'  => 123,
                    'timeLimit'  => 456,
                ]],
                'expect' => [
                    'properties' => [
                        'getBaseDn()'  => 'dc=example,dc=org',
                        'getFilter()'  => 'objectClass=*',
                    ],
                    'options' => [
                        'attributes' => ['*'],
                        'attrsOnly' => 1,
                        'deref' => LDAP_DEREF_ALWAYS,
                        'scope' => 'one',
                        'sizeLimit' => 123,
                        'timeLimit' => 456,
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider prov__createSearchQuery
     */
    public function test__createSearchQuery(array $args, array $expect) : void
    {
        $link = $this->createMock(LdapLinkInterface::class);

        $searching = $this->createSearchingInstance($link);

        $query = $searching->createSearchQuery(...$args);

        $this->assertInstanceOf(SearchQuery::class, $query);
        $this->assertSame($link, $query->getLdapLink());
        $this->assertHasPropertiesSameAs($expect['properties'], $query);

        // FIXME: use self::assertEqualsKsorted() once it's implemented (see GH issue #3).
        $expectOptions = $expect['options'];
        $actualOptions = $query->getOptions();
        ksort($expectOptions);
        ksort($actualOptions);
        $this->assertSame($expectOptions, $actualOptions);
    }

    //
    // search()
    //

    public static function prov__search() : array
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

        $cases[] = [
            'args'   => $args,
            'expect' => [
                'method' => 'search',
                'args'   => $expectArgs,
            ],
        ];
        foreach (SearchQueryTest::SCOPES_METHODS as $scope => $expectMethod) {
            $case = [
                'args' => $args,
                'expect' => [
                    'method' => $expectMethod,
                    'args'   => $expectArgs,
                ],
            ];
            $case['args'][2]['scope'] = $scope;
            $cases[] = $case;
        }

        return $cases;
    }

    /**
     * @dataProvider prov__search
     */
    public function test__search(array $args, array $expect) : void
    {
        $link       = $this->createMock(LdapLinkInterface::class);
        $ldapResult = $this->createMock(LdapResultInterface::class);
        $link->expects($this->exactly(1))
             ->method($expect['method'])
             ->with(...$expect['args'])
             ->willReturn($ldapResult);

        $searching = $this->createSearchingInstance($link);
        $result = $searching->search(...$args);

        $this->assertInstanceOf(Result::class, $result);
        $this->assertSame($ldapResult, $result->getLdapResult());
    }

    public static function prov__search__withLdapTriggerError() : array
    {
        return self::feedLdapLinkErrorHandler();
    }

    /**
     * @dataProvider prov__search__withLdapTriggerError
     */
    public function test__search__withLdapTriggerError(LdapTriggerErrorTestFixture $fixture) : void
    {
        $link = $this->createMock(LdapLinkInterface::class);
        $searching = $this->createSearchingInstance($link);
        $function = function () use ($searching) {
            return $searching->search('', '', []);
        };
        $subject = new LdapTriggerErrorTestSubject($link, 'search');

        $this->examineLdapLinkErrorHandler($function, $subject, $link, $fixture);
    }

    public function test__search__withLdapReturningFalse() : void
    {
        $link = $this->createMock(LdapLinkInterface::class);
        $searching = $this->createSearchingInstance($link);

        $link->expects($this->once())
             ->method('search')
             ->with('', '', ['*'], 0, 0, 0, LDAP_DEREF_NEVER)
             ->willReturn(false);

        $link->expects($this->once())
             ->method('getErrorHandler')
             ->with()
             ->willReturn(new LdapLinkErrorHandler($link));

        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('LdapLinkInterface::search() returned false');

        $searching->search('', '', []);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
