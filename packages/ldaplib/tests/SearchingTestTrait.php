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

use Korowai\Lib\Ldap\SearchingInterface;
use Korowai\Lib\Ldap\SearchQuery;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait SearchingTestTrait
{
    abstract public function createSearchingInstance(LdapLinkInterface $ldapLink) : SearchingInterface;
    abstract public function examineCallWithLdapTriggerError(
        callable $function,
        object $mock,
        string $mockMethod,
        array $mockArgs,
        LdapLinkInterface $ldapLinkMock,
        array $config,
        array $expect
    ) : void;
    abstract public static function feedCallWithLdapTriggerError() : array;

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

    // TODO: start from here!

//    //
//    // search()
//    //
//
//    public static function prov__search() : array
//    {
//        return [
//            // #0
//            [
//                'args'   => ['dc=example,dc=org', 'attribute', 'matching'],
//                'return' => false,
//                'expect' => false,
//            ],
//            // #1
//            [
//                'args'   => ['dc=example,dc=org', 'attribute', 'non-matching'],
//                'return' => true,
//                'expect' => true,
//            ],
//        ];
//    }
//
//    /**
//     * @dataProvider prov__search
//     */
//    public function test__search(array $args, $return, $expect) : void
//    {
//        $link = $this->createMock(LdapLinkInterface::class);
//        $searching = $this->createSearchingInstance($link);
//        $link->expects($this->exactly(2))
//             ->method('search')
//             ->with(...$args)
//             ->willReturn($return);
//        $this->assertSame($expect, $searching->search(...$args));
//        $this->assertSame($expect, $searching->search(...$args));
//    }
//
//    public static function prov__search__withLdapTriggerError() : array
//    {
//        return self::feedCallWithLdapTriggerError();
//    }
//
//    /**
//     * @dataProvider prov__search__withLdapTriggerError
//     */
//    public function test__search__withLdapTriggerError(array $config, array $expect): void
//    {
//        $link = $this->createMock(LdapLinkInterface::class);
//        $searching = $this->createSearchingInstance($link);
//        $args = ['dc=example,dc=org', 'attribute', 'value'];
//        $function = function () use ($searching, $args) {
//            return $searching->search(...$args);
//        };
//
//        $this->examineCallWithLdapTriggerError($function, $link, 'search', $args, $link, $config, $expect);
//    }
//
//    public function test__search__withLdapReturningFailure() : void
//    {
//        $link = $this->createMock(LdapLinkInterface::class);
//        $searching = $this->createSearchingInstance($link);
//
//        $args = ['dc=example,dc=org', 'attribute', 'value'];
//        $link->expects($this->once())
//             ->method('search')
//             ->with(...$args)
//             ->willReturn(-1);
//
//        $this->expectException(\ErrorException::class);
//        $this->expectExceptionMessage('LdapLink::search() returned -1');
//
//        $searching->search(...$args);
//    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
