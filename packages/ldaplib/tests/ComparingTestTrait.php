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

//use Korowai\Testing\Ldaplib\CreateLdapLinkMockTrait;
//use Korowai\Testing\Ldaplib\ExamineCallWithLdapTriggerErrorTrait;

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

        $instance = $this->createComparingInstance($link);

        $query = $instance->createCompareQuery('dc=example,dc=org', 'foo', 'bar');

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

    public function test__compare() : void
    {
//        $link = $this->getMockBuilder(LdapLinkInterface::class)
//                     ->getMockForAbstractClass();
//
//        $instance = $this->createComparingInstance($link);
//
//        $link->expects($this->once())
//             ->method('compare')
//             ->with('dc=example,dc=org', 'foo', 'bar')
//             ->willReturn(true);
//
//        $this->assertTrue($instance->compare('dc=example,dc=org', 'foo', 'bar'));
        $this->markTestIncomplete('Test not implemented yet!');
    }
}

// vim: syntax=php sw=4 ts=4 et:
