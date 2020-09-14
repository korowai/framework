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

use Korowai\Testing\Ldaplib\TestCase;
use Korowai\Testing\Ldaplib\CreateLdapLinkMockTrait;
use Korowai\Testing\Ldaplib\ExamineCallWithMockedLdapFunctionTrait;
use Korowai\Testing\Ldaplib\MakeArgsForLdapFunctionMockTrait;
use Korowai\Testing\Ldaplib\GetLdapFunctionMockTrait;
use Korowai\Testing\Basiclib\ResourceWrapperTestHelpersTrait;

use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResult;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultWrapperInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultEntry;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultReference;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperTrait;
use Korowai\Lib\Basic\ResourceWrapperTrait;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\MockObject\Stub\Stub;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResult
 */
final class LdapResultTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;
    use GetLdapFunctionMockTrait;
    use MakeArgsForLdapFunctionMockTrait;
    use ExamineCallWithMockedLdapFunctionTrait;
    use CreateLdapLinkMockTrait;
    use ResourceWrapperTestHelpersTrait;

    private function examineCallWithMockedBackend(
        string $method,
        array &$args,
        $will,
        $expect,
        bool $bare = false
    ) : void {
        $ldap = $this->createLdapLinkMock();
        $result = new LdapResult('ldap link', $ldap);

        if ($bare) {
            $resources = [$result];
        } else {
            $resources = [$ldap, $result];
        }

        $actual = $this->examineCallWithMockedLdapFunction(
            [$result, $method],
            $resources,
            $args,
            $will,
            $expect,
            "ldap_$method"
        );

        if ($actual instanceof LdapResultWrapperInterface) {
            $this->assertSame($result, $actual->getLdapResult());
        }
    }

    //
    //
    // TESTS
    //
    //

    public function test__implements__LdapResultInterface() : void
    {
        $this->assertImplementsInterface(LdapResultInterface::class, LdapResult::class);
    }

    public function test__uses__ResourceWrapperTrait() : void
    {
        $this->assertUsesTrait(ResourceWrapperTrait::class, LdapResult::class);
    }

    public function test__uses__LdapLinkWrapperTrait() : void
    {
        $this->assertUsesTrait(LdapLinkWrapperTrait::class, LdapResult::class);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getResource()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function test__getResource() : void
    {
        $ldap = $this->createLdapLinkMock(null);
        $result = new LdapResult('ldap result', $ldap);
        $this->assertSame('ldap result', $result->getResource());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getLdapLink()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function test__getLdapLink() : void
    {
        $ldap = $this->createLdapLinkMock(null);
        $result = new LdapResult('ldap result', $ldap);
        $this->assertSame($ldap, $result->getLdapLink());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // supportsResourceType()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__supportsResourceType() : array
    {
        return static::feedSupportsResourceType('ldap result');
    }

    /**
     * @dataProvider prov__supportsResourceType()
     */
    public function test__supportsResourceType(array $args, $expect) : void
    {
        $ldap = $this->createLdapLinkMock(null);
        $result = new LdapResult('foo', $ldap);

        $this->examineSupportsResourceType($result, $args, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // isValid()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__isValid() : array
    {
        return static::feedIsValid('ldap result');
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__isValid
     */
    public function test__isValid($arg, $return, $expect) : void
    {
        $ldap = $this->createLdapLinkMock();
        $result = new LdapResult($arg, $ldap);
        $this->examineIsValid($result, $arg, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // count_entries()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__count_entries__withMockedBackend() : array
    {
        return [
            // #0
            [
                'args'   => [],
                'will'   => 123,
                'expect' => 123,
            ],
            // #1
            [
                'args'   => [],
                'will'   => false,
                'expect' => false,
            ],
            // #2
            [
                'args'   => [],
                'will'   => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__count_entries__withMockedBackend
     */
    public function test__count_entries__withMockedBackend(array $args, $will, $expect) : void
    {
        $this->examineCallWithMockedBackend('count_entries', $args, $will, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // count_references()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @runInSeparateProcess
     */
    public function test__count_references() : void
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

    public static function prov__first_entry__withMockedBackend() : array
    {
        return [
            // #0
            [
                'args'   => [],
                'will'   => 'first result entry',
                'expect' => static::logicalAnd(
                    static::isInstanceOf(LdapResultEntry::class),
                    static::hasPropertiesIdenticalTo(['getResource()' => 'first result entry'])
                ),
            ],
            // #1
            [
                'args'   => [],
                'will'   => false,
                'expect' => false,
            ],
            // #2
            [
                'args'   => [],
                'will'   => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__first_entry__withMockedBackend
     */
    public function test__first_entry__withMockedBackend(array $args, $will, $expect) : void
    {
        $this->examineCallWithMockedBackend('first_entry', $args, $will, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // first_reference()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__first_reference__withMockedBackend() : array
    {
        return [
            // #0
            [
                'args'   => [],
                'will'   => 'first result reference',
                'expect' => static::logicalAnd(
                    static::isInstanceOf(LdapResultReference::class),
                    static::hasPropertiesIdenticalTo(['getResource()' => 'first result reference'])
                ),
            ],
            // #1
            [
                'args'   => [],
                'will'   => false,
                'expect' => false,
            ],
            // #2
            [
                'args'   => [],
                'will'   => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__first_reference__withMockedBackend
     */
    public function test__first_reference__withMockedBackend(array $args, $will, $expect) : void
    {
        $this->examineCallWithMockedBackend('first_reference', $args, $will, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // free_result()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__free_result__withMockedBackend() : array
    {
        return [
            // #0
            [
                'args'   => [],
                'will'   => true,
                'expect' => true
            ],
            // #1
            [
                'args'   => [],
                'will'   => false,
                'expect' => false,
            ],
            // #2
            [
                'args'   => [],
                'will'   => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__free_result__withMockedBackend
     */
    public function test__free_result__withMockedBackend(array $args, $will, $expect) : void
    {
        $this->examineCallWithMockedBackend('free_result', $args, $will, $expect, true);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // get_entries()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__get_entries__withMockedBackend() : array
    {
        return [
            // #0
            [
                'args'   => [],
                'will'   => ['entry 1', 'entry 2'],
                'expect' => ['entry 1', 'entry 2']
            ],
            // #1
            [
                'args'   => [],
                'will'   => false,
                'expect' => false,
            ],
            // #2
            [
                'args'   => [],
                'will'   => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__get_entries__withMockedBackend
     */
    public function test__get_entries__withMockedBackend(array $args, $will, $expect) : void
    {
        $this->examineCallWithMockedBackend('get_entries', $args, $will, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // parse_result()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function prov__parse_result__withMockedBackend() : array
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
    public function test__parse_result__withMockedBackend(array $args, $return, $expect, array $values) : void
    {
        $will = $this->returnCallback(new LdapParseResultClosure($return, $values));
        $this->examineCallWithMockedBackend('parse_result', $args, $will, $expect);
        for ($i = 0; $i < 4; $i++) {
            if (count($args) > $i) {
                $this->assertSame($values[$i] ?? null, $args[$i]);
            }
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // sort()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__sort__withMockedBackend() : array
    {
        return [
            // #1
            [
                'args'   => ['filter'],
                'will'   => true,
                'expect' => true,
            ],

            // #2
            [
                'args'   => ['***'],
                'will'   => false,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__sort__withMockedBackend
     */
    public function test__sort__withMockedBackend(array $args, $will, $expect) : void
    {
        $this->examineCallWithMockedBackend('sort', $args, $will, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // unset()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @runInSeparateProcess
     */
    public function test__unset__doesNotFreeTheResource() : void
    {
        $ldap = $this->createLdapLinkMock(null);
        $result = new LdapResult('ldap result', $ldap);

        $this->getLdapFunctionMock('ldap_free_result')
             ->expects($this->never());

        unset($result);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
