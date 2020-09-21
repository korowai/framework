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
use Korowai\Testing\Ldaplib\CreateLdapResultEntryMockTrait;
use Korowai\Testing\Ldaplib\CreateLdapResultMockTrait;
use Korowai\Testing\Ldaplib\ExamineLdapLinkErrorHandlerTrait;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestFixture;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestSubject;
use PHPUnit\Framework\MockObject\MockObject;

use Korowai\Lib\Ldap\ResultEntry;
use Korowai\Lib\Ldap\ResultEntryInterface;
use Korowai\Lib\Ldap\Entry;
use Korowai\Lib\Ldap\ResultAttributeIterator;
use Korowai\Lib\Ldap\Core\LdapResultEntryWrapperInterface;
use Korowai\Lib\Ldap\Core\LdapResultEntryWrapperTrait;
use Korowai\Lib\Ldap\LdapException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\ResultEntry
 */
final class ResultEntryTest extends TestCase
{
    use CreateLdapLinkMockTrait;
    use CreateLdapResultMockTrait;
    use CreateLdapResultEntryMockTrait;
    use ExamineLdapLinkErrorHandlerTrait;

    private function createResultEntryAndMocks(int $mocksDepth = 3) : array
    {
        $link        = $mocksDepth >= 3 ? $this->createLdapLinkMock() : null;
        $ldapResult  = $mocksDepth >= 2 ? $this->createLdapResultMock($link) : null;
        $ldapEntry   = $this->createLdapResultEntryMock($ldapResult);
        $resultEntry = new ResultEntry($ldapEntry);
        return array_slice([$resultEntry, $ldapEntry, $ldapResult, $link], 0, max(2, 1+ $mocksDepth));
    }

    private function examineResultEntryMethodWithTriggerError(
        string $method,
        string $backendMethod,
        array $args,
        LdapTriggerErrorTestFixture $fixture
    ) : void {
        [$resultEntry, $ldapEntry, $ldapResult, $link] = $this->createResultEntryAndMocks();

        $function  = function () use ($resultEntry, $method, $args) : void {
            $resultEntry->$method(...$args);
        };

        $subject = new LdapTriggerErrorTestSubject($ldapEntry, $backendMethod);
        $this->examineLdapLinkErrorHandler($function, $subject, $link, $fixture);
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
        [$resultEntry, $ldapEntry] = $this->createResultEntryAndMocks(1);
        $this->assertSame($ldapEntry, $resultEntry->getLdapResultEntry());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getLdapResultItem()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function test_getLdapResultItem() : void
    {
        [$resultEntry, $ldapEntry] = $this->createResultEntryAndMocks(1);
        $this->assertSame($ldapEntry, $resultEntry->getLdapResultItem());
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
        [$resultEntry, $ldapEntry] = $this->createResultEntryAndMocks(1);

        $ldapEntry->expects($this->once())
                  ->method('get_dn')
                  ->willReturn($return);

        $this->assertSame($expect, $resultEntry->getDn());
    }

    public static function prov__getDn__withTriggerError() : array
    {
        return static::feedLdapLinkErrorHandler();
    }

    /**
     * @dataProvider prov__getDn__withTriggerError
     */
    public function test__getDn__withTriggerError(LdapTriggerErrorTestFixture $fixture) : void
    {
        $this->examineResultEntryMethodWithTriggerError('getDn', 'get_dn', [], $fixture);
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
        [$resultEntry, $ldapEntry] = $this->createResultEntryAndMocks(1);

        $ldapEntry->expects($this->once())
                  ->method('get_attributes')
                  ->willReturn($return);

        $this->assertSame($expect, $resultEntry->getAttributes());
    }

    public static function prov__getAttributes__withTriggerError() : array
    {
        return static::feedLdapLinkErrorHandler();
    }

    /**
     * @dataProvider prov__getAttributes__withTriggerError
     */
    public function test__getAttributes__withTriggerError(LdapTriggerErrorTestFixture $fixture) : void
    {
        $this->examineResultEntryMethodWithTriggerError('getAttributes', 'get_attributes', [], $fixture);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getAttributeIterator()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function test__getAttributeIterator() : void
    {
        [$resultEntry, $ldapEntry] = $this->createResultEntryAndMocks(1);

        $ldapEntry->expects($this->exactly(2))
                  ->method('get_values')
                  ->withConsecutive(['firstattribute'], ['secondattribute'])
                  ->will($this->onConsecutiveCalls(['FIRST', 'count' => 1], ['SECOND', 'count' => 1]));

        $ldapEntry->expects($this->once())
                  ->method('first_attribute')
                  ->willReturn('FirstAttribute');

        $ldapEntry->expects($this->once())
                  ->method('next_attribute')
                  ->willReturn('SecondAttribute');

        $iterator = $resultEntry->getAttributeIterator();
        $this->assertInstanceOf(ResultAttributeIterator::class, $iterator);

        $this->assertSame($ldapEntry, $iterator->getLdapResultEntry());
        $this->assertEquals('firstattribute', $iterator->key());
        $this->assertEquals(['FIRST'], $iterator->current());

        $iterator->next();

        // single iterator instance per ResultEntry (dictated by ext-ldap implementation)
        $this->assertSame($iterator, $resultEntry->getAttributeIterator());
        $this->assertEquals('secondattribute', $iterator->key());
        $this->assertEquals(['SECOND'], $iterator->current());
    }

    public static function prov__getAttributeIterator__withTriggerError() : array
    {
        return static::feedLdapLinkErrorHandler();
    }

    /**
     * @dataProvider prov__getAttributeIterator__withTriggerError
     */
    public function test__getAttributeIterator__withTriggerError(LdapTriggerErrorTestFixture $fixture) : void
    {
        $this->examineResultEntryMethodWithTriggerError('getAttributeIterator', 'first_attribute', [], $fixture);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getIterator()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function test__getIterator() : void
    {
        [$resultEntry, $ldapEntry] = $this->createResultEntryAndMocks(1);

        $ldapEntry->expects($this->once())
                  ->method('first_attribute')
                  ->willReturn('first attribute');

        $this->assertSame($resultEntry->getIterator(), $resultEntry->getAttributeIterator());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // toEntry()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function test__toEntry() : void
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

        [$resultEntry, $ldapEntry] = $this->createResultEntryAndMocks(1);

        $ldapEntry->expects($this->once())
                  ->method('get_dn')
                  ->willReturn($ldapDn);
        $ldapEntry->expects($this->once())
                  ->method('get_attributes')
                  ->willReturn($ldapAttributes);

        $entry = $resultEntry->toEntry();

        $this->assertInstanceOf(Entry::class, $entry);
        $this->assertEquals($ldapDn, $entry->getDn());
        $this->assertSame($expectAttributes, $entry->getAttributes());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
