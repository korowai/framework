<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Adapter\ExtLdap;

use Korowai\Testing\TestCase;
use \Phake;

use Korowai\Lib\Ldap\Adapter\ExtLdap\Result;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultEntry;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultReference;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultEntryIterator;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultReferenceIterator;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ExtLdapResultInterface;
use Korowai\Lib\Ldap\Adapter\ResultInterface;

// because we use process isolation heavily, we can't use native PHP closures
// (they're not serializable)
use Korowai\Tests\Lib\Ldap\Adapter\ExtLdap\Closures\LdapControlPagedResultResponseClosure;
use Korowai\Tests\Lib\Ldap\Adapter\ExtLdap\Closures\LdapParseResultClosure;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    private function getLdapFunctionMock(...$args)
    {
        return $this->getFunctionMock('\\Korowai\\Lib\\Ldap\\Adapter\ExtLdap', ...$args);
    }

    private function createLdapLinkMock($resource = 'ldap link')
    {
        $builder = $this->getMockBuilder(LdapLinkInterface::class);
        if ($resource !== null) {
            $builder->setMethods(['getResource']);
        }

        $ldap = $builder->getMockForAbstractClass();

        if ($resource !== null) {
            $ldap->expects($this->any())
                 ->method('getResource')
                 ->with()
                 ->willReturn($resource);
        }

        return $ldap;
    }

    private function createLdapResult(LdapLinkInterface $link = null, $resource = 'ldap result')
    {
        return new Result($resource, $link);
    }

    private function createLdapResultEntry($result = null, $resource = 'ldap result entry')
    {
        return new ResultEntry($resource, $result);
    }

    public function test__implements__ResultInterface()
    {
        $this->assertImplementsInterface(ResultInterface::class, Result::class);
    }

    public function test__implements__ExtLdapResultInterface()
    {
        $this->assertImplementsInterface(ExtLdapResultInterface::class, Result::class);
    }

    public function test__getResource()
    {
        $ldap = $this->createLdapLinkMock(null);
        $result = new Result('ldap result', $ldap);
        $this->assertSame('ldap result', $result->getResource());
    }

    public function test__getLdapLink()
    {
        $ldap = $this->createLdapLinkMock(null);
        $result = new Result('ldap result', $ldap);
        $this->assertSame($ldap, $result->getLdapLink());
    }

    public static function prov__control_paged_result_response()
    {
        return [
            // #0
            [
                'args'   => [],
                'return' => true,
                'expect' => true,
                'values' => [],
            ],
            // #1
            [
                'args'   => [&$c3a1],
                'return' => true,
                'expect' => true,
                'values' => ['cookie'],
            ],
            // #2
            [
                'args'   => [&$c4a1, &$c4a2],
                'return' => true,
                'expect' => true,
                'values' => ['cookie', 123],
            ],
            // #3
            [
                'args'   => [],
                'return' => false,
                'expect' => false,
                'values' => [],
            ],
            // #4
            [
                'args'   => [],
                'return' => null,
                'expect' => false,
                'values' => [],
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__control_paged_result_response
     */
    public function test__control_paged_result_response(array $args, $return, $expect, array $values)
    {
        $ldap = $this->createLdapLinkMock();
        $result = $this->createLdapResult($ldap);

        $ldapArgs = array_map(
            [$this, 'identicalTo'],
            array_merge([$ldap->getResource(), $result->getResource()], $args)
        );

        $this   ->getLdapFunctionMock("ldap_control_paged_result_response")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturnCallback(new LdapControlPagedResultResponseClosure($return, $values));

        $this->assertSame($expect, $result->control_paged_result_response(...$args));

        for ($offset = 0; $offset < count($args); ++$offset) {
            $this->assertSame($values[$offset] ?? null, $args[$offset]);
        }
    }

    public static function prov__count_entries()
    {
        return [
            // #0
            [
                'args'   => [],
                'return' => 123,
                'expect' => 123,
            ],
            // #1
            [
                'args'   => [],
                'return' => false,
                'expect' => false,
            ],
            // #2
            [
                'args'   => [],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__count_entries
     */
    public function test__count_entries(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLinkMock();
        $result = $this->createLdapResult($ldap);

        $ldapArgs = array_map(
            [$this, 'identicalTo'],
            array_merge([$ldap->getResource(), $result->getResource()], $args)
        );

        $this   ->getLdapFunctionMock("ldap_count_entries")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $result->count_entries(...$args));
    }

    /**
     * @runInSeparateProcess
     */
    public function test__count_references()
    {
        $ldap = $this->createLdapLinkMock();
        $result = $this->createLdapResult($ldap);

        // FIXME: uncomment, once it's implemented
//        $this   ->getLdapFunctionMock("ldap_count_references")
//                ->expects($this->once())
//                ->with('ldap link', 'ldap result')
//                ->willReturn(333);

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Not implemented');
        $result->count_references();
    }

    public static function prov__first_entry()
    {
        return [
            // #0
            [
                'args'   => [],
                'return' => 'first result entry',
                'expect' => ['getResource()' => 'first result entry']
            ],
            // #1
            [
                'args'   => [],
                'return' => false,
                'expect' => false,
            ],
            // #2
            [
                'args'   => [],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__first_entry
     */
    public function test__first_entry(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLinkMock();
        $result = $this->createLdapResult($ldap);

        $ldapArgs = array_map(
            [$this, 'identicalTo'],
            array_merge([$ldap->getResource(), $result->getResource()], $args)
        );

        $this   ->getLdapFunctionMock("ldap_first_entry")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $entry = $result->first_entry(...$args);
        if ($return) {
            $this->assertInstanceOf(ResultEntry::class, $entry);
            $this->assertSame($result, $entry->getResult());
            $this->assertSame($return, $entry->getResource());
            $this->assertHasPropertiesSameAs($expect, $entry);
        } else {
            $this->assertSame($expect, $entry);
        }
    }

    public static function prov__first_reference()
    {
        return [
            // #0
            [
                'args'   => [],
                'return' => 'first result reference',
                'expect' => ['getResource()' => 'first result reference']
            ],
            // #1
            [
                'args'   => [],
                'return' => false,
                'expect' => false,
            ],
            // #2
            [
                'args'   => [],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__first_reference
     */
    public function test__first_reference(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLinkMock();
        $result = $this->createLdapResult($ldap);

        $ldapArgs = array_map(
            [$this, 'identicalTo'],
            array_merge([$ldap->getResource(), $result->getResource()], $args)
        );

        $this   ->getLdapFunctionMock("ldap_first_reference")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $reference = $result->first_reference(...$args);
        if ($return) {
            $this->assertInstanceOf(ResultReference::class, $reference);
            $this->assertSame($result, $reference->getResult());
            $this->assertSame($return, $reference->getResource());
            $this->assertHasPropertiesSameAs($expect, $reference);
        } else {
            $this->assertSame($expect, $reference);
        }
    }

    public static function prov__free_result()
    {
        return [
            // #0
            [
                'args'   => [],
                'return' => true,
                'expect' => true
            ],
            // #1
            [
                'args'   => [],
                'return' => false,
                'expect' => false,
            ],
            // #2
            [
                'args'   => [],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__free_result
     */
    public function test__free_result(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLinkMock();
        $result = $this->createLdapResult($ldap);

        $ldapArgs = array_map([$this, 'identicalTo'], array_merge([$result->getResource()], $args));

        $this   ->getLdapFunctionMock("ldap_free_result")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $result->free_result(...$args));
    }

    public static function prov__get_entries()
    {
        return [
            // #0
            [
                'args'   => [],
                'return' => ['entry 1', 'entry 2'],
                'expect' => ['entry 1', 'entry 2']
            ],
            // #1
            [
                'args'   => [],
                'return' => false,
                'expect' => false,
            ],
            // #2
            [
                'args'   => [],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__get_entries
     */
    public function test__get_entries(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLinkMock();
        $result = $this->createLdapResult($ldap);

        $ldapArgs = array_map(
            [$this, 'identicalTo'],
            array_merge([$ldap->getResource(), $result->getResource()], $args)
        );

        $this   ->getLdapFunctionMock("ldap_get_entries")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $result->get_entries(...$args));
    }

    public function prov__parse_result()
    {
        return [
            // #0
            [
                'args'   => [&$errcode1],
                'return' => true,
                'expect' => true,
                'values' => [123],
            ],

            // #1
            [
                'args'   => [&$errcode2, &$matcheddn2],
                'return' => true,
                'expect' => true,
                'values' => [123, 'dc=example,dc=org'],
            ],

            // #2
            [
                'args'   => [&$errcode3, &$matcheddn3, &$errmsg3],
                'return' => true,
                'expect' => true,
                'values' => [123, 'dc=example,dc=org', 'An error'],
            ],

            // #3
            [
                'args'   => [&$errcode4, &$matcheddn4, &$errmsg4, &$referrals4],
                'return' => true,
                'expect' => true,
                'values' => [123, 'dc=example,dc=org', 'An error', ['referrals']],
            ],

            // #4
            [
                'args'   => [&$errcode4, &$matcheddn4, &$errmsg4, &$referrals4, &$serverctls],
                'return' => true,
                'expect' => true,
                'values' => [123, 'dc=example,dc=org', 'An error', ['referrals'], ['serverctls']],
            ],

            // #5
            [
                'args'   => [&$errcode5],
                'return' => false,
                'expect' => false,
                'values' => [null],
            ],

            // #6
            [
                'args'   => [&$errcode6],
                'return' => null,
                'expect' => false,
                'values' => [null],
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__parse_result
     */
    public function test__parse_result(array $args, $return, $expect, array $values)
    {
        $ldap = $this->createLdapLinkMock();
        $result = $this->createLdapResult($ldap);

        $ldapArgs = array_map(
            [$this, 'identicalTo'],
            array_merge([$ldap->getResource(), $result->getResource()], $args)
        );

        $this   ->getLdapFunctionMock("ldap_parse_result")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturnCallback(new LdapParseResultClosure($return, $values));

        $this->assertSame($expect, $result->parse_result(...$args));
        for ($i = 0; $i < 4; $i++) {
            if (count($args) > $i) {
                $this->assertSame($values[$i] ?? null, $args[$i]);
            }
        }
    }

    public static function prov__sort()
    {
        return [
            // #1
            [
                'args'   => ['filter'],
                'return' => true,
                'expect' => true,
            ],

            // #2
            [
                'args'   => ['***'],
                'return' => false,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__sort
     */
    public function test__sort(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLinkMock();
        $result = $this->createLdapResult($ldap);
        $ldapArgs = array_map(
            [$this, 'identicalTo'],
            array_merge([$ldap->getResource(), $result->getResource()], $args)
        );
        $this   ->getLdapFunctionMock("ldap_sort")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $result->sort(...$args));
    }

//    public function test__getResultEntries()
//    {
//        $link = $this->getMockBuilder(LdapLinkInterface::class)->getMock();
//        $entry1 = $this->createMock(ResultEntry::class);
//        $entry2 = $this->createMock(ResultEntry::class);
//        $result = new Result('ldap result', $link);
//
//        $link->expects($this->any())
//             ->method('first_entry')
//             ->with($result)
//             ->willReturn($entry1);
//        $entry1->expects($this->once())
//               ->method('next_entry')
//               ->with()
//               ->willReturn($entry2);
//        $entry2->expects($this->once())
//               ->method('next_entry')
//               ->with()
//               ->willReturn(false);
//
//        $entries = $result->getResultEntries();
//        $this->assertIsArray($entries);
//        $this->assertCount(2, $entries);
//        $this->assertSame([$entry1, $entry2], $entries);
//    }
//
//    public function test__getResultReferences()
//    {
//        $link = $this->getMockBuilder(LdapLinkInterface::class)->getMock();
//        $reference1 = $this->createMock(ResultReference::class);
//        $reference2 = $this->createMock(ResultReference::class);
//        $result = new Result('ldap result', $link);
//
//        $link->expects($this->any())
//             ->method('first_reference')
//             ->with($result)
//             ->willReturn($reference1);
//        $reference1->expects($this->once())
//               ->method('next_reference')
//               ->with()
//               ->willReturn($reference2);
//        $reference2->expects($this->once())
//               ->method('next_reference')
//               ->with()
//               ->willReturn(false);
//
//        $references = $result->getResultReferences();
//        $this->assertIsArray($references);
//        $this->assertCount(2, $references);
//        $this->assertSame([$reference1, $reference2], $references);
//    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__first_entry
     */
    public function test__getResultEntryIterator(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLinkMock();
        $result = $this->createLdapResult($ldap);

        $ldapArgs = array_map(
            [$this, 'identicalTo'],
            array_merge([$ldap->getResource(), $result->getResource()], $args)
        );

        $this   ->getLdapFunctionMock("ldap_first_entry")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $iter = $result->getResultEntryIterator();
        $this->assertInstanceOf(ResultEntryIterator::class, $iter);
        $this->assertSame($result, $iter->getResult());
        $entry = $iter->getEntry();
        if ($return) {
            $this->assertInstanceOf(ResultEntry::class, $entry);
            $this->assertHasPropertiesSameAs($expect, $entry);
        } else {
            $this->assertNull($entry);
        }
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__first_reference
     */
    public function test__getResultReferenceIterator(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLinkMock();
        $result = $this->createLdapResult($ldap);

        $ldapArgs = array_map(
            [$this, 'identicalTo'],
            array_merge([$ldap->getResource(), $result->getResource()], $args)
        );

        $this   ->getLdapFunctionMock("ldap_first_reference")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $iter = $result->getResultReferenceIterator();
        $this->assertInstanceOf(ResultReferenceIterator::class, $iter);
        $this->assertSame($result, $iter->getResult());
        $reference = $iter->getReference();
        if ($return) {
            $this->assertInstanceOf(ResultReference::class, $reference);
        } else {
            $this->assertNull($reference);
        }
    }

//    public function test__getResultReferenceIterator()
//    {
//        $link = $this->getMockBuilder(LdapLinkInterface::class)->getMock();
//        $ref = $this->createMock(ResultReference::class);
//        $result = new Result('ldap result', $link);
//
//        $link->expects($this->once())
//             ->method('first_reference')
//             ->with($result)
//             ->willReturn($ref);
//
//        $iter = $result->getResultReferenceIterator();
//        $this->assertSame($result, $iter->getResult());
//        $this->assertSame($ref, $iter->getReference());
//    }
//
//    public function test__getResultReferenceIterator__NullFirstReference()
//    {
//        $link = $this->getMockBuilder(LdapLinkInterface::class)->getMock();
//        $result = new Result('ldap result', $link);
//
//        $link->expects($this->once())
//             ->method('first_reference')
//             ->with($result)
//             ->willReturn(null);
//
//        $iter = $result->getResultReferenceIterator();
//        $this->assertSame($result, $iter->getResult());
//        $this->assertNull($iter->getReference());
//    }
//
//    public function test__getResultReferenceIterator__FalseFirstReference()
//    {
//        $link = $this->getMockBuilder(LdapLinkInterface::class)->getMock();
//        $result = new Result('ldap result', $link);
//
//        $link->expects($this->once())
//             ->method('first_reference')
//             ->with($result)
//             ->willReturn(false);
//
//        $iter = $result->getResultReferenceIterator();
//        $this->assertSame($result, $iter->getResult());
//        $this->assertNull($iter->getReference());
//    }

    /**
     * @runInSeparateProcess
     */
    public function test__destruct__Invalid()
    {
        $link = $this->getMockBuilder(LdapLinkInterface::class)->getMock();
        $result = new Result('ldap result', $link);
        $this->getLdapFunctionMock('ldap_free_result')
             ->expects($this->never())
             ->with('ldap result')
             ->willReturn('ok');

        unset($result);
    }

//    /**
//     * @runInSeparateProcess
//     */
//    public function test__destruct__Valid()
//    {
//        $link = $this->getMockBuilder(LdapLinkInterface::class)->getMock();
//        $result = new Result('FAKE LDAP RESULT', $link);
//
//        $this->getLdapFunctionMock('is_resource')
//             ->expects($this->any())
//             ->with('FAKE LDAP RESULT')
//             ->willReturn(true);
//
//        $this->getLdapFunctionMock('get_resource_type')
//             ->expects($this->any())
//             ->with('FAKE LDAP RESULT')
//             ->willReturn('ldap result');
//
//        $this->getLdapFunctionMock('ldap_free_result')
//             ->expects($this->once())
//             ->with('FAKE LDAP RESULT')
//             ->willReturn(true);
//
//        unset($result);
//    }
}

// vim: syntax=php sw=4 ts=4 et:
