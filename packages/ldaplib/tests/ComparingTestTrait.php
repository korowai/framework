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

use Korowai\Lib\Ldap\ComparingTrait;
use Korowai\Lib\Ldap\ComparingInterface;
use Korowai\Lib\Ldap\CompareQueryInterface;
use Korowai\Lib\Ldap\CompareQuery;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ComparingTestTrait
{
    abstract public function createComparingInstance(LdapLinkInterface $ldapLink) : ComparingInterface;
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
    // createCompareQuery()
    //
    public function test__createCompareQuery() : void
    {
        $link = $this->getMockBuilder(LdapLinkInterface::class)
                     ->getMockForAbstractClass();

        $comparator = $this->createComparingInstance($link);

        $query = $comparator->createCompareQuery('dc=example,dc=org', 'foo', 'bar');

        $this->assertInstanceOf(CompareQuery::class, $query);
        $this->assertHasPropertiesSameAs([
            'getLdapLink()' => $link,
            'getDn()' => 'dc=example,dc=org',
            'getAttribute()' => 'foo',
            'getValue()' => 'bar'
        ], $query);
    }

    //
    // compare()
    //

    public static function prov__compare() : array
    {
        return [
            // #0
            [
                'args'   => ['dc=example,dc=org', 'attribute', 'matching'],
                'return' => false,
                'expect' => false,
            ],
            // #1
            [
                'args'   => ['dc=example,dc=org', 'attribute', 'non-matching'],
                'return' => true,
                'expect' => true,
            ],
        ];
    }

    /**
     * @dataProvider prov__compare
     */
    public function test__compare(array $args, $return, $expect) : void
    {
        $link = $this->getMockBuilder(LdapLinkInterface::class)
                     ->getMockForAbstractClass();
        $comparator = $this->createComparingInstance($link);
        $link->expects($this->exactly(2))
             ->method('compare')
             ->with(...$args)
             ->willReturn($return);
        $this->assertSame($expect, $comparator->compare(...$args));
        $this->assertSame($expect, $comparator->compare(...$args));
    }

    public static function prov__compare__withLdapTriggerError() : array
    {
        return self::feedCallWithLdapTriggerError();
    }

    /**
     * @dataProvider prov__compare__withLdapTriggerError
     */
    public function test__compare__withLdapTriggerError(array $config, array $expect): void
    {
        $link = $this->getMockBuilder(LdapLinkInterface::class)
                     ->getMockForAbstractClass();
        $comparator = $this->createComparingInstance($link);
        $args = ['dc=example,dc=org', 'attribute', 'value'];
        $function = function () use ($comparator, $args) {
            return $comparator->compare(...$args);
        };

        $this->examineCallWithLdapTriggerError($function, $link, 'compare', $args, $link, $config, $expect);
    }

    public function test__compare__withLdapReturningFailure() : void
    {
        $link = $this->getMockBuilder(LdapLinkInterface::class)
                     ->getMockForAbstractClass();
        $comparator = $this->createComparingInstance($link);

        $args = ['dc=example,dc=org', 'attribute', 'value'];
        $link->expects($this->once())
             ->method('compare')
             ->with(...$args)
             ->willReturn(-1);

        $this->expectException(\ErrorException::class);
        $this->expectExceptionMessage('LdapLink::compare() returned -1');

        $comparator->compare(...$args);
    }
}

// vim: syntax=php sw=4 ts=4 et:
