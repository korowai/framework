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
use Korowai\Testing\Ldaplib\CreateLdapResultReferenceMockTrait;
use Korowai\Testing\Ldaplib\ExamineCallWithLdapTriggerErrorTrait;

use PHPUnit\Framework\Constraint\Constraint;

use Korowai\Lib\Ldap\Result;
use Korowai\Lib\Ldap\ResultInterface;
use Korowai\Lib\Ldap\ResultEntry;
use Korowai\Lib\Ldap\ResultReference;
use Korowai\Lib\Ldap\ResultEntryIteratorInterface;
use Korowai\Lib\Ldap\ResultReferenceIteratorInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultWrapperInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultWrapperTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Result
 */
final class ResultTest extends TestCase
{
    use CreateLdapLinkMockTrait;
    use CreateLdapResultMockTrait;
    use CreateLdapResultEntryMockTrait;
    use CreateLdapResultReferenceMockTrait;
    use ExamineCallWithLdapTriggerErrorTrait;

    private function createResultAndMocks(int $mocksDepth = 2) : array
    {
        $link = $mocksDepth >= 2 ? $this->createLdapLinkMock() : null;
        $ldapResult = $this->createLdapResultMock($link);
        $result = new Result($ldapResult);
        return array_slice([$result, $ldapResult, $link], 0, max(2, 1 + $mocksDepth));
    }

    private function examineResultMethodWithTriggerError(
        string $method,
        string $backendMethod,
        array $args,
        array $config,
        array $expect
    ) : void {
        [$result, $ldapResult, $link] = $this->createResultAndMocks();

        $this->examineCallWithLdapTriggerError(
            function () use ($result, $method, $args) : void {
                $result->$method(...$args);
            },
            $ldapResult,
            $backendMethod,
            $args,
            $link,
            $config,
            $expect
        );
    }

    //
    //
    // TEST
    //
    //

    public function test__implements__ResultInterface()
    {
        $this->assertImplementsInterface(ResultInterface::class, Result::class);
    }

    public function test__implements__LdapResultWrapperInterface()
    {
        $this->assertImplementsInterface(LdapResultWrapperInterface::class, Result::class);
    }

    public function test__uses__LdapResultWrapperTrait()
    {
        $this->assertUsesTrait(LdapResultWrapperTrait::class, Result::class);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getLdapResult()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function test__getLdapResult()
    {
        [$result, $ldapResult] = $this->createResultAndMocks(1);
        $this->assertSame($ldapResult, $result->getLdapResult());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getResultEntries()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__getResultEntries()
    {
        return [
            // #0
            [
                'entries' => [],
            ],

            // #1
            [
                'entries' => ['first'],
            ],

            // #2
            [
                'entries' => ['first', 'second'],
            ],

            // #3
            [
                'entries' => ['first', 'second', 'third'],
            ],
        ];
    }

    /**
     * @dataProvider prov__getResultEntries
     */
    public function test__getResultEntries(array $ldapEntries = [])
    {
        [$result, $ldapResult] = $this->createResultAndMocks(1);

        $ldapEntries = array_map(function ($ldapEntry) use ($ldapResult) {
            return $this->createLdapResultEntryMock($ldapResult, $ldapEntry, ['next_item']);
        }, $ldapEntries);

        $first = $ldapEntries[0] ?? false;
        $ldapResult->expects($this->once())
                   ->method('first_entry')
                   ->with()
                   ->willReturn($first);

        for ($i = 0; $i < count($ldapEntries); ++$i) {
            $curr = $ldapEntries[$i];
            $next = $ldapEntries[$i+1] ?? false;
            $curr->expects($this->once())
                 ->method('next_item')
                 ->with()
                 ->willReturn($next);
        }

        $constraints = array_map(function ($ldapEntry) {
            return $this->logicalAnd(
                $this->isInstanceOf(ResultEntry::class),
                $this->hasPropertiesIdenticalTo(['getLdapResultEntry()' => $ldapEntry]),
            );
        }, $ldapEntries);

        $entries = $result->getResultEntries();

        $this->assertCount(count($ldapEntries), $entries);

        for ($i = 0; $i < count($entries); ++$i) {
            $entry = $entries[$i];
            $expect = $constraints[$i];
            $this->assertThat($entry, $expect);
        }
    }

    public static function prov__getResultEntries__withTriggerError()
    {
        return static::feedCallWithLdapTriggerError();
    }

    /**
     * @dataProvider prov__getResultEntries__withTriggerError
     */
    public function test__getResultEntries__withTriggerError(array $config, array $expect)
    {
        $this->examineResultMethodWithTriggerError('getResultEntries', 'first_entry', [], $config, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getResultReferences()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__getResultReferences()
    {
        return [
            // #0
            [
                'references' => [],
            ],

            // #1
            [
                'references' => ['first'],
            ],

            // #2
            [
                'references' => ['first', 'second'],
            ],

            // #3
            [
                'references' => ['first', 'second', 'third'],
            ],
        ];
    }

    /**
     * @dataProvider prov__getResultReferences
     */
    public function test__getResultReferences(array $ldapReferences = [])
    {
        [$result, $ldapResult] = $this->createResultAndMocks(1);

        $ldapReferences = array_map(function ($ldapReference) use ($ldapResult) {
            return $this->createLdapResultReferenceMock($ldapResult, $ldapReference, ['next_item']);
        }, $ldapReferences);

        $first = $ldapReferences[0] ?? false;
        $ldapResult->expects($this->once())
                   ->method('first_reference')
                   ->with()
                   ->willReturn($first);

        for ($i = 0; $i < count($ldapReferences); ++$i) {
            $curr = $ldapReferences[$i];
            $next = $ldapReferences[$i+1] ?? false;
            $curr->expects($this->once())
                 ->method('next_item')
                 ->with()
                 ->willReturn($next);
        }

        $constraints = array_map(function ($ldapReference) {
            return $this->logicalAnd(
                $this->isInstanceOf(ResultReference::class),
                $this->hasPropertiesIdenticalTo(['getLdapResultReference()' => $ldapReference]),
            );
        }, $ldapReferences);

        $references = $result->getResultReferences();

        $this->assertCount(count($ldapReferences), $references);

        for ($i = 0; $i < count($references); ++$i) {
            $reference = $references[$i];
            $expect = $constraints[$i];
            $this->assertThat($reference, $expect);
        }
    }

    public static function prov__getResultReferences__withTriggerError()
    {
        return static::feedCallWithLdapTriggerError();
    }

    /**
     * @dataProvider prov__getResultReferences__withTriggerError
     */
    public function test__getResultReferences__withTriggerError(array $config, array $expect)
    {
        $this->examineResultMethodWithTriggerError('getResultReferences', 'first_reference', [], $config, $expect);
    }


    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getResultEntryIterator()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function test__getResultEntryIterator()
    {
        [$result, $ldapResult] = $this->createResultAndMocks(1);

        $ldapResult->expects($this->once())
                   ->method('first_entry')
                   ->with()
                   ->willReturn(false);

        $iterator = $result->getResultEntryIterator();

        $this->assertInstanceOf(ResultEntryIteratorInterface::class, $iterator);
        $this->assertNull($iterator->current());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getResultReferenceIterator()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function test__getResultReferenceIterator()
    {
        [$result, $ldapResult] = $this->createResultAndMocks(1);

        $ldapResult->expects($this->once())
                   ->method('first_reference')
                   ->with()
                   ->willReturn(false);

        $iterator = $result->getResultReferenceIterator();

        $this->assertInstanceOf(ResultReferenceIteratorInterface::class, $iterator);
        $this->assertNull($iterator->current());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getEntries()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function test__getEntries()
    {
        $this->markTestIncomplete('Test not implemented yet');
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getIterator()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function test__getIterator()
    {
        $this->markTestIncomplete('Test not implemented yet');
    }
}

// vim: syntax=php sw=4 ts=4 et tw=120:
