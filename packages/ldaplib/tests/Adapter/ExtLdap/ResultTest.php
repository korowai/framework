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

use Korowai\Lib\Ldap\Adapter\ExtLdap\Result;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultEntry;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultReference;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultEntryIterator;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultReferenceIterator;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ExtLdapResultInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\HasResource;
use Korowai\Lib\Ldap\Adapter\ExtLdap\HasLdapLink;
use Korowai\Lib\Ldap\Adapter\ResultInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ResultTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;
    use GetLdapFunctionMock;

    private function mockResourceFunctions($arg, $return) : void
    {
        if ($return !== null) {
            $this->getLdapFunctionMock('is_resource')
                 ->expects($this->any())
                 ->with($this->identicalTo($arg))
                 ->willReturn((bool)$return);
            if ($return) {
                $this->getLdapFunctionMock('get_resource_type')
                     ->expects($this->any())
                     ->with($this->identicalTo($arg))
                     ->willReturn(is_string($return) ? $return : 'unknown');
            }
        }
    }

    private function createLdapLinkMock($resource = 'ldap link')
    {
        $builder = $this->getMockBuilder(LdapLinkInterface::class);
        if ($resource !== null) {
            $builder->setMethods(['getResource']);
        }

        $mock = $builder->getMockForAbstractClass();

        if ($resource !== null) {
            $mock->expects($this->any())
                 ->method('getResource')
                 ->with()
                 ->willReturn($resource);
        }

        return $mock;
    }

    private function createLdapResult(LdapLinkInterface $link = null, $resource = 'ldap result')
    {
        return new Result($resource, $link);
    }

    private function makeArgsForLdapMock(array $args, Result $result = null, LdapLinkInterface $ldap = null) : array
    {
        $resources = [];
        if ($result !== null) {
            $resources[] = $result->getResource();
        }
        if ($ldap !== null) {
            $resources[] = $ldap->getResource();
        }
        return array_map([$this, 'identicalTo'], array_merge($resources, $args));
    }

    private function examineFuncWithMockedBackend(string $func, array $args, $return, $expect) : void
    {
        $ldap = $this->createLdapLinkMock();
        $result = $this->createLdapResult($ldap);

        $ldapArgs = $this->makeArgsForLdapMock($args, $result, $ldap);

        $this   ->getLdapFunctionMock("ldap_$func")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, call_user_func_array([$result, $func], $args));
    }

    public function test__implements__ExtLdapResultInterface()
    {
        $this->assertImplementsInterface(ExtLdapResultInterface::class, Result::class);
    }

    public function test__uses__HasResource()
    {
        $this->assertUsesTrait(HasResource::class, Result::class);
    }

    public function test__uses__HasLdapLink()
    {
        $this->assertUsesTrait(HasLdapLink::class, Result::class);
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

    public static function prov__isLdapResultResource()
    {
        return [
            // #0
            [
                'arg'    => null,
                'return' => null,
                'expect' => false,
            ],

            // #1
            [
                'arg'    => 'foo',
                'return' => null,
                'expect' => false,
            ],

            // #2
            [
                'arg'    => 'mocked false',
                'return' => false,
                'expect' => false,
            ],

            // #3
            [
                'arg'    => 'mocked unknown',
                'return' => 'unknown',
                'expect' => false,
            ],

            // #4
            [
                'arg'    => 'ldap result',
                'return' => 'ldap result',
                'expect' => true,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__isLdapResultResource
     */
    public function test__isLdapResultResource($arg, $return, $expect)
    {
        $this->mockResourceFunctions($arg, $return);
        $this->assertSame($expect, Result::isLdapResultResource($arg));
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__isLdapResultResource
     */
    public function test__isValid($arg, $return, $expect)
    {
        $this->mockResourceFunctions($arg, $return);
        $ldap = $this->createLdapLinkMock();
        $result = new Result($arg, $ldap);
        $this->assertSame($expect, $result->isValid());
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

    /**
     * @runInSeparateProcess
     */
    public function test__getResultEntries__withEmptyResult()
    {
        $ldap = $this->createLdapLinkMock('ldap link');
        $result = new Result('ldap result', $ldap);
        $args0 = array_map([$this, 'identicalTo'], ['ldap link', 'ldap result']);

        $this   ->getLdapFunctionMock("ldap_first_entry")
                ->expects($this->exactly(2))
                ->with(...$args0)
                ->willReturn(false);
        $this   ->getLdapFunctionMock("ldap_next_entry")
                ->expects($this->never());

        $entries = $result->getResultEntries();
        $this->assertIsArray($entries);
        $this->assertCount(0, $entries);
    }

    /**
     * @runInSeparateProcess
     */
    public function test__getResultEntries()
    {
        $ldap = $this->createLdapLinkMock('ldap link');
        $result = new Result('ldap result', $ldap);
        $args0 = array_map([$this, 'identicalTo'], ['ldap link', 'ldap result']);
        $args1 = array_map([$this, 'identicalTo'], ['ldap link', 'ldap entry 1']);
        $args2 = array_map([$this, 'identicalTo'], ['ldap link', 'ldap entry 2']);

        $this   ->getLdapFunctionMock("ldap_first_entry")
                ->expects($this->exactly(2))
                ->with(...$args0)
                ->willReturn('ldap entry 1');
        $this   ->getLdapFunctionMock("ldap_next_entry")
                ->expects($this->exactly(2))
                ->withConsecutive($args1, $args2)
                ->will($this->onConsecutiveCalls('ldap entry 2', false));

        $entries = $result->getResultEntries();
        $this->assertIsArray($entries);
        $this->assertCount(2, $entries);
        $this->assertHasPropertiesSameAs(['getResource()' => 'ldap entry 1'], $entries[0]);
        $this->assertHasPropertiesSameAs(['getResource()' => 'ldap entry 2'], $entries[1]);
    }

    /**
     * @runInSeparateProcess
     */
    public function test__getResultReferences__withEmptyResult()
    {
        $ldap = $this->createLdapLinkMock('ldap link');
        $result = new Result('ldap result', $ldap);
        $args0 = array_map([$this, 'identicalTo'], ['ldap link', 'ldap result']);

        $this   ->getLdapFunctionMock("ldap_first_reference")
                ->expects($this->exactly(2))
                ->with(...$args0)
                ->willReturn(false);
        $this   ->getLdapFunctionMock("ldap_next_reference")
                ->expects($this->never());

        $references = $result->getResultReferences();
        $this->assertIsArray($references);
        $this->assertCount(0, $references);
    }

    /**
     * @runInSeparateProcess
     */
    public function test__getResultReferences()
    {
        $ldap = $this->createLdapLinkMock('ldap link');
        $result = new Result('ldap result', $ldap);
        $args0 = array_map([$this, 'identicalTo'], ['ldap link', 'ldap result']);
        $args1 = array_map([$this, 'identicalTo'], ['ldap link', 'ldap reference 1']);
        $args2 = array_map([$this, 'identicalTo'], ['ldap link', 'ldap reference 2']);

        $this   ->getLdapFunctionMock("ldap_first_reference")
                ->expects($this->exactly(2))
                ->with(...$args0)
                ->willReturn('ldap reference 1');
        $this   ->getLdapFunctionMock("ldap_next_reference")
                ->expects($this->exactly(2))
                ->withConsecutive($args1, $args2)
                ->will($this->onConsecutiveCalls('ldap reference 2', false));

        $reference = $result->getResultReferences();
        $this->assertIsArray($reference);
        $this->assertCount(2, $reference);
        $this->assertHasPropertiesSameAs(['getResource()' => 'ldap reference 1'], $reference[0]);
        $this->assertHasPropertiesSameAs(['getResource()' => 'ldap reference 2'], $reference[1]);
    }

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

    /**
     * @runInSeparateProcess
     */
    public function test__destruct__Invalid()
    {
        $ldap = $this->createLdapLinkMock(null);
        $result = new Result('ldap result', $ldap);
        $this->getLdapFunctionMock('ldap_free_result')
             ->expects($this->never())
             ->with('ldap result')
             ->willReturn('ok');

        unset($result);
    }

    /**
     * @runInSeparateProcess
     */
    public function test__unset__doesNotFreeTheResource()
    {
        $ldap = $this->createLdapLinkMock(null);
        $result = new Result('ldap result', $ldap);

        $this->getLdapFunctionMock('ldap_unbind')
             ->expects($this->never());

        $this->getLdapFunctionMock('ldap_close')
             ->expects($this->never());

        unset($result);
    }
}

// vim: syntax=php sw=4 ts=4 et:
