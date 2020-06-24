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

use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResult;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultEntry;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultReference;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\HasResource;
use Korowai\Lib\Ldap\Adapter\ExtLdap\HasLdapLink;

// tests with process isolation can't use native PHP closures (they're not serializable)
use Korowai\Tests\Lib\Ldap\Adapter\ExtLdap\Closures\LdapParseResultClosure;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapResultTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    private function getLdapFunctionMock(...$args)
    {
        return $this->getFunctionMock('\\Korowai\\Lib\\Ldap\\Adapter\ExtLdap', ...$args);
    }

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

    private function makeArgsForBackendMock(array $args, LdapResult $result = null, LdapLinkInterface $ldap = null) : array
    {
        $resources = [];
        if ($ldap !== null) {
            $resources[] = $ldap->getResource();
        }
        if ($result !== null) {
            $resources[] = $result->getResource();
        }
        return array_map([$this, 'identicalTo'], array_merge($resources, $args));
    }

    private function examineFuncWithMockedBackend(
        string $func,
        array $args,
        $return,
        $expect,
        bool $backendWithoutLdapLink = false
    ) : void {
        $ldap = $this->createLdapLinkMock();
        $result = new LdapResult('ldap link', $ldap);

        if ($backendWithoutLdapLink) {
            $ldapArgs = $this->makeArgsForBackendMock($args, $result);
        } else {
            $ldapArgs = $this->makeArgsForBackendMock($args, $result, $ldap);
        }

        $this   ->getLdapFunctionMock("ldap_$func")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, call_user_func_array([$result, $func], $args));
    }

    //
    //
    // TESTS
    //
    //

    public function test__implements__LdapResultInterface()
    {
        $this->assertImplementsInterface(LdapResultInterface::class, LdapResult::class);
    }

    public function test__uses__HasResource()
    {
        $this->assertUsesTrait(HasResource::class, LdapResult::class);
    }

    public function test__uses__HasLdapLink()
    {
        $this->assertUsesTrait(HasLdapLink::class, LdapResult::class);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getResource()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function test__getResource()
    {
        $ldap = $this->createLdapLinkMock(null);
        $result = new LdapResult('ldap result', $ldap);
        $this->assertSame('ldap result', $result->getResource());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getLdapLink()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function test__getLdapLink()
    {
        $ldap = $this->createLdapLinkMock(null);
        $result = new LdapResult('ldap result', $ldap);
        $this->assertSame($ldap, $result->getLdapLink());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // isLdapResultResource()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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
                'arg'    => 'foo',
                'return' => null,
                'expect' => false,
            ],

            // #3
            [
                'arg'    => 'mocked false',
                'return' => false,
                'expect' => false,
            ],

            // #4
            [
                'arg'    => 'mocked unknown',
                'return' => 'unknown',
                'expect' => false,
            ],

            // #5
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
        $this->assertSame($expect, LdapResult::isLdapResultResource($arg));
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // isValid()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @runInSeparateProcess
     * @dataProvider prov__isLdapResultResource
     */
    public function test__isValid($arg, $return, $expect)
    {
        $this->mockResourceFunctions($arg, $return);
        $ldap = $this->createLdapLinkMock();
        $result = new LdapResult($arg, $ldap);
        $this->assertSame($expect, $result->isValid());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // count_entries()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__count_entries__withMockedBackend()
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
     * @dataProvider prov__count_entries__withMockedBackend
     */
    public function test__count_entries__withMockedBackend(array $args, $return, $expect)
    {
        $this->examineFuncWithMockedBackend('count_entries', $args, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // count_references()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @runInSeparateProcess
     */
    public function test__count_references()
    {
        $ldap = $this->createLdapLinkMock();
        $result = new LdapResult('ldap result', $ldap);

        // FIXME: uncomment, once it's implemented
//        $this   ->getLdapFunctionMock("ldap_count_references")
//                ->expects($this->once())
//                ->with('ldap link', 'ldap result')
//                ->willReturn(333);

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Not implemented');
        $result->count_references();
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // first_entry()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__first_entry__withMockedBackend()
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
     * @dataProvider prov__first_entry__withMockedBackend
     */
    public function test__first_entry__withMockedBackend(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLinkMock();
        $result = new LdapResult('ldap result', $ldap);

        $ldapArgs = $this->makeArgsForBackendMock($args, $result, $ldap);

        $this   ->getLdapFunctionMock("ldap_first_entry")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $entry = $result->first_entry(...$args);
        if ($return) {
            $this->assertInstanceOf(LdapResultEntry::class, $entry);
            $this->assertSame($result, $entry->getLdapResult());
            $this->assertSame($return, $entry->getResource());
            $this->assertHasPropertiesSameAs($expect, $entry);
        } else {
            $this->assertSame($expect, $entry);
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // first_reference()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__first_reference__withMockedBackend()
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
     * @dataProvider prov__first_reference__withMockedBackend
     */
    public function test__first_reference__withMockedBackend(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLinkMock();
        $result = new LdapResult('ldap result', $ldap);

        $ldapArgs = $this->makeArgsForBackendMock($args, $result, $ldap);

        $this   ->getLdapFunctionMock("ldap_first_reference")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $reference = $result->first_reference(...$args);
        if ($return) {
            $this->assertInstanceOf(LdapResultReference::class, $reference);
            $this->assertSame($result, $reference->getLdapResult());
            $this->assertSame($return, $reference->getResource());
            $this->assertHasPropertiesSameAs($expect, $reference);
        } else {
            $this->assertSame($expect, $reference);
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // free_result()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__free_result__withMockedBackend()
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
     * @dataProvider prov__free_result__withMockedBackend
     */
    public function test__free_result__withMockedBackend(array $args, $return, $expect)
    {
        $this->examineFuncWithMockedBackend('free_result', $args, $return, $expect, true);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // get_entries()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__get_entries__withMockedBackend()
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
     * @dataProvider prov__get_entries__withMockedBackend
     */
    public function test__get_entries__withMockedBackend(array $args, $return, $expect)
    {
        $this->examineFuncWithMockedBackend('get_entries', $args, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // parse_result()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function prov__parse_result__withMockedBackend()
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
     * @dataProvider prov__parse_result__withMockedBackend
     */
    public function test__parse_result__withMockedBackend(array $args, $return, $expect, array $values)
    {
        $ldap = $this->createLdapLinkMock();
        $result = new LdapResult('ldap result', $ldap);

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

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // sort()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__sort__withMockedBackend()
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
     * @dataProvider prov__sort__withMockedBackend
     */
    public function test__sort__withMockedBackend(array $args, $return, $expect)
    {
        $this->examineFuncWithMockedBackend('sort', $args, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // unset()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @runInSeparateProcess
     */
    public function test__unset__doesNotFreeTheResource()
    {
        $ldap = $this->createLdapLinkMock(null);
        $result = new LdapResult('ldap result', $ldap);

        $this->getLdapFunctionMock('ldap_free_result')
             ->expects($this->never());

        unset($result);
    }
}

// vim: syntax=php sw=4 ts=4 et:
