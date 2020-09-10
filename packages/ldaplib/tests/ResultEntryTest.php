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
use Korowai\Testing\Ldaplib\CreateLdapLinkMockTrait;
use Korowai\Testing\Ldaplib\CreateLdapResultMockTrait;
use Korowai\Testing\Ldaplib\CreateLdapResultEntryMockTrait;
use Korowai\Testing\Ldaplib\ExamineCallWithLdapTriggerErrorTrait;

use Korowai\Lib\Ldap\ResultEntry;
use Korowai\Lib\Ldap\ResultEntryInterface;
use Korowai\Lib\Ldap\Entry;
use Korowai\Lib\Ldap\ResultAttributeIterator;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultEntryWrapperInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultEntryWrapperTrait;
use Korowai\Lib\Ldap\Exception\LdapException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\ResultEntry
 */
final class ResultEntryTest extends TestCase
{
    use CreateLdapLinkMockTrait;
    use CreateLdapResultMockTrait;
    use CreateLdapResultEntryMockTrait;
    use ExamineCallWithLdapTriggerErrorTrait;

    private function examineMethodWithTriggerError(
        string $method,
        string $backendMethod,
        array $args,
        array $config,
        array $expect
    ) : void {
        $ldap = $this->createLdapLinkMock('ldap link', ['isValid', 'errno']);
        $ldapResult = $this->createLdapResultMock($ldap);
        $ldapEntry = $this->createLdapResultEntryMock($ldapResult, 'ldap result entry', [$backendMethod]);
        $entry = new ResultEntry($ldapEntry);

        $this->examineCallWithLdapTriggerError(
            function () use ($entry, $method, $args) : void {
                $entry->$method(...$args);
            },
            $ldapEntry,
            $backendMethod,
            $args,
            $ldap,
            $config,
            $expect
        );
    }

    //
    //
    // TESTS
    //
    //

    public function test__implements__ResultEntryInterface() : void
    {
        $this->assertImplementsInterface(ResultEntryInterface::class, ResultEntry::class);
    }

    public function test__implements__LdapResultEntryWrapperInterface() : void
    {
        $this->assertImplementsInterface(LdapResultEntryWrapperInterface::class, ResultEntry::class);
    }

    public function test__uses__LdapResultEntryWrapperTrait() : void
    {
        $this->assertUsesTrait(LdapResultEntryWrapperTrait::class, ResultEntry::class);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getLdapResultEntry()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function test_getLdapResultEntry() : void
    {
        $ldapEntry = $this->createLdapResultEntryMock(null, null);
        $entry = new ResultEntry($ldapEntry);
        $this->assertSame($ldapEntry, $entry->getLdapResultEntry());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getLdapResultItem()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function test_getLdapResultItem() : void
    {
        $ldapEntry = $this->createLdapResultEntryMock(null, null);
        $entry = new ResultEntry($ldapEntry);
        $this->assertSame($ldapEntry, $entry->getLdapResultItem());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getDn()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__getDn() : array
    {
        return [
            // #0
            [
                'return' => 'dc=example,dc=org',
                'expect' => 'dc=example,dc=org',
            ],
            // #1
            [
                'return' => false,
                'expect' => '',
            ],
        ];
    }

    /**
     * @dataProvider prov__getDn
     */
    public function test__getDn($return, $expect) : void
    {
        $ldapLink = $this->createLdapLinkMock();
        $ldapResult = $this->createLdapResultMock($ldapLink);
        $ldapEntry = $this->createLdapResultEntryMock($ldapResult, 'ldap result entry', ['get_dn']);
        $entry = new ResultEntry($ldapEntry);

        $ldapEntry->expects($this->once())
                  ->method('get_dn')
                  ->with()
                  ->willReturn($return);

        $this->assertSame($expect, $entry->getDn());
    }

    public static function prov__getDn__withTriggerError() : array
    {
        return static::feedCallWithLdapTriggerError();
    }

    /**
     * @dataProvider prov__getDn__withTriggerError
     */
    public function test__getDn__withTriggerError(array $config, array $expect) : void
    {
        $this->examineMethodWithTriggerError('getDn', 'get_dn', [], $config, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getAttributes()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__getAttributes() : array
    {
        return [
            // #0
            [
                'return' => false,
                'expect' => [],
            ],
            // #1
            [
                'return' => [],
                'expect' => [],
            ],
            // #2
            [
                'return' => ['count' => 0],
                'expect' => [],
            ],
            // #3
            [
                'return' => [
                    'objectClass' => ['top', 'inetOrgPerson'],
                    'cn' => ['John Smith'],
                ],
                'expect' => [
                    'objectclass' => ['top', 'inetOrgPerson'],
                    'cn' => ['John Smith'],
                ],
            ],
            // #4
            [
                'return' => [
                    'objectClass' => [
                        'count' => 2,
                        'top',
                        'inetOrgPerson'
                    ],
                    'cn' => [
                        'count' => 1,
                        'John Smith'
                    ],
                    'count' => 2,
                ],
                'expect' => [
                    'objectclass' => ['top', 'inetOrgPerson'],
                    'cn' => ['John Smith'],
                ],
            ],
        ];
    }

    /**
     * @dataProvider prov__getAttributes
     */
    public function test__getAttributes($return, $expect) : void
    {
        $ldapLink = $this->createLdapLinkMock();
        $ldapResult = $this->createLdapResultMock($ldapLink);
        $ldapEntry = $this->createLdapResultEntryMock($ldapResult, 'ldap result entry', ['get_attributes']);
        $entry = new ResultEntry($ldapEntry);

        $ldapEntry->expects($this->once())
                  ->method('get_attributes')
                  ->with()
                  ->willReturn($return);

        $this->assertSame($expect, $entry->getAttributes());
    }

    public static function prov__getAttributes__withTriggerError() : array
    {
        return static::feedCallWithLdapTriggerError();
    }

    /**
     * @dataProvider prov__getAttributes__withTriggerError
     */
    public function test__getAttributes__withTriggerError(array $config, array $expect) : void
    {
        $this->examineMethodWithTriggerError('getAttributes', 'get_attributes', [], $config, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getAttributeIterator()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function test__getAttributeIterator() : void
    {
        $ldapLink = $this->createLdapLinkMock();
        $ldapResult = $this->createLdapResultMock($ldapLink, 'ldap result', ['next_attribute']);
        $ldapEntry = $this->createLdapResultEntryMock($ldapResult, 'ldap result entry', ['first_attribute']);
        $entry = new ResultEntry($ldapEntry);

        $ldapEntry->expects($this->exactly(2))
                  ->method('get_values')
                  ->withConsecutive(['firstattribute'], ['secondattribute'])
                  ->will($this->onConsecutiveCalls(['FIRST', 'count' => 1], ['SECOND', 'count' => 1]));

        $ldapEntry->expects($this->once())
                  ->method('first_attribute')
                  ->with()
                  ->willReturn('FirstAttribute');

        $ldapEntry->expects($this->once())
                  ->method('next_attribute')
                  ->with()
                  ->willReturn('SecondAttribute');

        $iterator = $entry->getAttributeIterator();
        $this->assertInstanceOf(ResultAttributeIterator::class, $iterator);

        $this->assertSame($ldapEntry, $iterator->getLdapResultEntry());
        $this->assertEquals('firstattribute', $iterator->key());
        $this->assertEquals(['FIRST'], $iterator->current());

        $iterator->next();

        // single iterator instance per ResultEntry (dictated by ext-ldap implementation)
        $this->assertSame($iterator, $entry->getAttributeIterator());
        $this->assertEquals('secondattribute', $iterator->key());
        $this->assertEquals(['SECOND'], $iterator->current());
    }

    public static function prov__getAttributeIterator__withTriggerError() : array
    {
        return static::feedCallWithLdapTriggerError();
    }

    /**
     * @dataProvider prov__getAttributeIterator__withTriggerError
     */
    public function test__getAttributeIterator__withTriggerError(array $config, array $expect) : void
    {
        $this->examineMethodWithTriggerError('getAttributeIterator', 'first_attribute', [], $config, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getIterator()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function test__getIterator() : void
    {
        $ldapLink = $this->createLdapLinkMock();
        $ldapResult = $this->createLdapResultMock($ldapLink, 'ldap result', ['next_attribute']);
        $ldapEntry = $this->createLdapResultEntryMock($ldapResult, 'ldap result entry', ['first_attribute']);
        $entry = new ResultEntry($ldapEntry);

        $ldapEntry->expects($this->once())
                  ->method('first_attribute')
                  ->with()
                  ->willReturn('first attribute');

        $this->assertSame($entry->getIterator(), $entry->getAttributeIterator());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // toEntry()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function test__toEntry()
    {
        $ldapDn = 'uid=jsmith,ou=people,dc=korowai,dc=org';
        $ldapAttributes = [
            'count' => 3,
            'uid' => ['count' => 1, 'jsmith'],
            'firstName' => ['count' => 1, 'John'],
            'sn' => ['count' => 1, 'Smith']
        ];
        $expectAttributes = [
            'uid' => ['jsmith'],
            'firstname' => ['John'],
            'sn' => ['Smith']
        ];

        $ldapLink = $this->createLdapLinkMock();
        $ldapResult = $this->createLdapResultMock($ldapLink, 'ldap result', ['next_attribute']);
        $ldapEntry = $this->createLdapResultEntryMock($ldapResult, 'ldap result entry', ['first_attribute']);
        $resultEntry = new ResultEntry($ldapEntry);

        $ldapEntry->expects($this->once())
                  ->method('get_dn')
                  ->with()
                  ->willReturn($ldapDn);
        $ldapEntry->expects($this->once())
                  ->method('get_attributes')
                  ->with()
                  ->willReturn($ldapAttributes);

        $entry = $resultEntry->toEntry();

        $this->assertInstanceOf(Entry::class, $entry);
        $this->assertEquals($ldapDn, $entry->getDn());
        $this->assertSame($expectAttributes, $entry->getAttributes());
    }
}

// vim: syntax=php sw=4 ts=4 et: