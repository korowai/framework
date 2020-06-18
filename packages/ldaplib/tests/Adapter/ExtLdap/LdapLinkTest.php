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

use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLink;
use Korowai\Lib\Ldap\Adapter\ExtLdap\Result;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultEntry;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultReference;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LdapLinkTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    public function getLdapFunctionMock(...$args)
    {
        return $this->getFunctionMock('\Korowai\Lib\Ldap\Adapter\ExtLdap', ...$args);
    }

    private function createLdapLink($host = 'host', $port = 123, $resource = 'ldap link')
    {
        $this   ->getLdapFunctionMock("ldap_connect")
                ->expects($this->once())
                ->with($host, $port)
                ->willReturn($resource);
        return LdapLink::connect($host, $port);
    }

    private function createLdapResult(LdapLink $link = null, $resource = 'ldap result')
    {
        return new Result($resource, $link);
    }

    private function createLdapResultEntry($result = null, $resource = 'ldap result entry')
    {
        return new ResultEntry($resource, $result);
    }

    public function test__isLdapLinkResource_Null()
    {
        $this->assertSame(false, LdapLink::isLdapLinkResource(null));
    }

    public function test__isLdapLinkResource_NotResource()
    {
        $this->assertSame(false, LdapLink::isLdapLinkResource("foo"));
    }

    /**
     * @runInSeparateProcess
     */
    public function test__isLdapLinkResource()
    {
        $this->assertSame(true, LdapLink::isLdapLinkResource(ldap_connect("localhost")));
    }

    /**
     * @runInSeparateProcess
     */
    public function test__isLdapLinkResource_Closed()
    {
        $res = ldap_connect("localhost");
        ldap_close($res);
        $this->assertSame(false, LdapLink::isLdapLinkResource($res));
    }

    public function test__getResource_Null()
    {
        $link = new LdapLink(null);
        $this->assertNull($link->getResource());
    }

    public function test__getResource_LdapLink()
    {
        $link = new LdapLink("ldap link");
        $this->assertSame("ldap link", $link->getResource());
    }

    public function test__isValid_Null()
    {
        $link = new LdapLink(null);
        $this->assertSame(false, $link->isValid());
    }

    public function test__isValid_NotResource()
    {
        $link = new LdapLink("foo");
        $this->assertSame(false, $link->isValid());
    }

    /**
     * @runInSeparateProcess
     */
    public function test__isValid()
    {
        // Mocking it would be so hard...
        $link = new LdapLink(ldap_connect("localhost"));
        $this->assertSame(true, $link->isValid());
    }

    /**
     * @runInSeparateProcess
     */
    public function test__isValid_Closed()
    {
        $res = ldap_connect("localhost");
        ldap_close($res);
        $link = new LdapLink($res);
        $this->assertSame(false, $link->isValid());
    }

    public static function prov__add()
    {
        return [
            // #0
            [
                'args'   => ['dc=example,dc=org', ['foo', 'bar']],
                'return' => true,
                'expect' => true,
            ],
            // #1
            [
                'args'   => ['dc=acme,dc=com', ['foo', 'bar']],
                'return' => false,
                'expect' => false,
            ],
            // #2
            [
                'args'   => ['dc=acme,dc=com', ['foo', 'bar']],
                'return' => null,  // PHP 7.x: null may be returned (instead of TypeError())
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__add
     */
    public function test__add(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLink();
        $ldapArgs = array_map([$this, 'identicalTo'], array_merge([$ldap->getResource()], $args));
        $this   ->getLdapFunctionMock("ldap_add")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $ldap->add(...$args));
    }

    public static function prov__bind()
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
                'expect' => false
            ],
            // #2
            [
                'args'   => ['cn=admin,dc=example,dc=org'],
                'return' => true,
                'expect' => true,
            ],
            // #3
            [
                'args'   => ['cn=admin,dc=acme,dc=com'],
                'return' => false,
                'expect' => false,
            ],
            // #4
            [
                'args'   => ['cn=admin,dc=example,dc=org', '$3cr3t'],
                'return' => true,
                'expect' => true,
            ],
            // #5
            [
                'args'   => ['cn=admin,dc=acme,dc=com', '$3cr3t'],
                'return' => false,
                'expect' => false,
            ],
            // #4
            [
                'args'   => [null],
                'return' => true,
                'expect' => true,
            ],
            // #5
            [
                'args'   => [null, null],
                'return' => true,
                'expect' => true,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__bind
     */
    public function test__bind(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLink();
        $ldapArgs = array_map([$this, 'identicalTo'], array_merge([$ldap->getResource()], $args));
        $this   ->getLdapFunctionMock("ldap_bind")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $ldap->bind(...$args));
    }

    public static function prov__close()
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
                'expect' => false
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__close
     */
    public function test__close(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLink();
        $ldapArgs = array_map([$this, 'identicalTo'], array_merge([$ldap->getResource()], $args));
        $this   ->getLdapFunctionMock("ldap_close")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $ldap->close(...$args));
    }

    public static function prov__compare()
    {
        return [
            // #0
            [
                'args'   => ['dc=example,dc=org', 'attribute', 'value'],
                'return' => true,
                'expect' => true
            ],
            // #1
            [
                'args'   => ['dc=acme,dc=com', 'attribute', 'value'],
                'return' => false,
                'expect' => false
            ],
            // #2
            [
                'args'   => ['', '', ''],
                'return' => null,
                'expect' => false
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__compare
     */
    public function test__compare(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLink();
        $ldapArgs = array_map([$this, 'identicalTo'], array_merge([$ldap->getResource()], $args));
        $this   ->getLdapFunctionMock("ldap_compare")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $ldap->compare(...$args));
    }

    public static function prov__connect()
    {
        return [
            // #0
            [
                'args'   => [],
                'return' => 'ldap link 1',
                'expect' => ['getResource()' => 'ldap link 1'],
            ],
            // #1
            [
                'args'   => ['ldapi:///'],
                'return' => 'ldap link 2',
                'expect' => ['getResource()' => 'ldap link 2'],
            ],
            // #2
            [
                'args'   => ['localhost', 123],
                'return' => 'ldap link 3',
                'expect' => ['getResource()' => 'ldap link 3'],
            ],
            // #3
            [
                'args'   => [null],
                'return' => 'ldap link 4',
                'expect' => ['getResource()' => 'ldap link 4'],
            ],
            // #4
            [
                'args'   => [null, null],
                'return' => 'ldap link 5',
                'expect' => ['getResource()' => 'ldap link 5'],
            ],
            // #5
            [
                'args'   => ['ldapi:///', null],
                'return' => 'ldap link 6',
                'expect' => ['getResource()' => 'ldap link 6'],
            ],
            // #6
            [
                'args'   => ['#@#@'],
                'return' => false,
                'expect' => false,
            ],
            // #7
            [
                'args'   => ['#@#@'],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__connect
     */
    public function test__connect(array $args, $return, $expect)
    {
        $ldapArgs = array_map([$this, 'identicalTo'], $args);
        if (count($args) === 2 && $args[1] === null) {
            unset($ldapArgs[1]);
        }
        $this   ->getLdapFunctionMock("ldap_connect")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $ldap = LdapLink::connect(...$args);
        if ($return) {
            $this->assertInstanceOf(LdapLink::class, $ldap);
            $this->assertSame($return, $ldap->getResource());
            $this->assertHasPropertiesSameAs($expect, $ldap);
        } else {
            $this->assertSame($expect, $ldap);
        }
    }

    public static function prov__control_paged_result_response()
    {
        return [
            // #0
            [
                'args'   => [],
                'return' => true,
                'expect' => true,
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
            // #3
            [
                'args'   => [&$c3a1],
                'return' => null,
                'expect' => false,
            ],
            // #4
            [
                'args'   => [&$c4a1, &$c4a2],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__control_paged_result_response
     */
    public function test__control_paged_result_response(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLink();
        $result = $this->createLdapResult($ldap);
        $ldapArgs = array_map(
            [$this, 'identicalTo'],
            array_merge([$ldap->getResource(), $result->getResource()], $args)
        );

        $this   ->getLdapFunctionMock("ldap_control_paged_result_response")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturnCallback(function ($ldap, $result, &...$tail) use ($return) {
                    if (count($tail) > 0) {
                        $tail[0] = 'cookie';
                    }
                    if (count($tail) > 1) {
                        $tail[1] = 123;
                    }
                    return $return;
                });

        $this->assertSame($expect, $ldap->control_paged_result_response($result, ...$args));

        if (count($args) > 0) {
            $this->assertSame('cookie', $args[0]);
        }

        if (count($args) > 1) {
            $this->assertSame(123, $args[1]);
        }
    }

    public static function prov__control_paged_result()
    {
        return [
            // #0
            [
                'args'   => [123],
                'return' => true,
                'expect' => true,
            ],
            // #1
            [
                'args'   => [-1],
                'return' => false,
                'expect' => false,
            ],
            // #2
            [
                'args'   => [-4],
                'return' => null,
                'expect' => false,
            ],
            // #3
            [
                'args'   => [123, true],
                'return' => true,
                'expect' => true,
            ],
            // #4
            [
                'args'   => [123, true, 'cookie'],
                'return' => true,
                'expect' => true,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__control_paged_result
     */
    public function test__control_paged_result(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLink();
        $ldapArgs = array_map([$this, 'identicalTo'], array_merge([$ldap->getResource()], $args));

        $this   ->getLdapFunctionMock("ldap_control_paged_result")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $ldap->control_paged_result(...$args));
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
        $ldap = $this->createLdapLink();
        $result = $this->createLdapResult($ldap);
        $ldapArgs = array_map(
            [$this, 'identicalTo'],
            array_merge([$ldap->getResource(), $result->getResource()], $args)
        );

        $this   ->getLdapFunctionMock("ldap_count_entries")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $ldap->count_entries($result, ...$args));
    }

    /**
     * @runInSeparateProcess
     */
    public function test__count_references()
    {
        $ldap = $this->createLdapLink();
        $result = $this->createLdapResult($ldap);

        // FIXME: uncomment, once it's implemented
//        $this   ->getLdapFunctionMock("ldap_count_references")
//                ->expects($this->once())
//                ->with('ldap link', 'ldap result')
//                ->willReturn(333);

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Not implemented');
        $this->assertSame(333, $ldap->count_references($result));
    }

    public static function prov__delete()
    {
        return [
            // #0
            [
                'args'   => ['dc=example,dc=org'],
                'return' => true,
                'expect' => true,
            ],
            // #1
            [
                'args'   => ['dc=acme,dc=com'],
                'return' => false,
                'expect' => false,
            ],
            // #2
            [
                'args'   => ['$#$'],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__delete
     */
    public function test__delete(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLink();
        $ldapArgs = array_map([$this, 'identicalTo'], array_merge([$ldap->getResource()], $args));

        $this   ->getLdapFunctionMock("ldap_delete")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $ldap->delete(...$args));
    }

    public static function prov__dn2ufn()
    {
        return [
            // #0
            [
                'args'   => ['dc=example,dc=org'],
                'return' => 'example.com',
                'expect' => 'example.com',
            ],
            // #1
            [
                'args'   => ['dc=acme,$#$#'],
                'return' => false,
                'expect' => false,
            ],
            // #2
            [
                'args'   => ['$#$'],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__dn2ufn
     */
    public function test__dn2ufn(array $args, $return, $expect)
    {
        $ldapArgs = array_map([$this, 'identicalTo'], $args);

        $this   ->getLdapFunctionMock("ldap_dn2ufn")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, LdapLink::dn2ufn(...$args));
    }

    public static function prov__err2str()
    {
        return [
            // #0
            [
                'args'   => [2],
                'return' => 'Protocol error',
                'expect' => 'Protocol error',
            ],
            // #1
            [
                'args'   => [-321],
                'return' => false,
                'expect' => false,
            ],
            // #2
            [
                'args'   => [-10000000],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__err2str
     */
    public function test__err2str(array $args, $return, $expect)
    {
        $ldapArgs = array_map([$this, 'identicalTo'], $args);

        $this   ->getLdapFunctionMock("ldap_err2str")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, LdapLink::err2str(...$args));
    }

    public static function prov__errno()
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
     * @dataProvider prov__errno
     */
    public function test__errno(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLink();
        $ldapArgs = array_map([$this, 'identicalTo'], array_merge([$ldap->getResource()], $args));

        $this   ->getLdapFunctionMock("ldap_errno")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $ldap->errno(...$args));
    }

    public static function prov__error()
    {
        return [
            // #0
            [
                'args'   => [],
                'return' => 'Protocol error',
                'expect' => 'Protocol error',
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
     * @dataProvider prov__error
     */
    public function test__error(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLink();
        $ldapArgs = array_map([$this, 'identicalTo'], array_merge([$ldap->getResource()], $args));

        $this   ->getLdapFunctionMock("ldap_error")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $ldap->error(...$args));
    }

    public static function prov__escape()
    {
        return [
            // #0
            [
                'args'   => ['dc=example,dc=org'],
                'return' => '\64\63\3d\65\78\61\6d\70\6c\65\2c\64\63\3d\6f\72\67',
                'expect' => '\64\63\3d\65\78\61\6d\70\6c\65\2c\64\63\3d\6f\72\67',
            ],
            // #1
            [
                'args'   => ['dc=example,dc=org', '=,'],
                'return' => '\64\63=\65\78\61\6d\70\6c\65,\64\63=\6f\72\67',
                'expect' => '\64\63=\65\78\61\6d\70\6c\65,\64\63=\6f\72\67',
            ],
            // #2
            [
                'args'   => ['dc=example,dc=org', '', LDAP_ESCAPE_DN],
                'return' => 'dc\3dexample\2cdc\3dorg',
                'expect' => 'dc\3dexample\2cdc\3dorg',
            ],
            // #3
            [
                'args'   => ['', '', -123],
                'return' => false,
                'expect' => false
            ],
            // #4
            [
                'args'   => ['', '', -456],
                'return' => null,
                'expect' => false
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__escape
     */
    public function test__escape(array $args, $return, $expect)
    {
        $ldapArgs = array_map([$this, 'identicalTo'], $args);
        $this   ->getLdapFunctionMock("ldap_escape")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, LdapLink::escape(...$args));
    }

    public static function prov__explode_dn()
    {
        return [
            // #0
            [
                'args'   => ['dc=example,dc=org', 0],
                'return' => ['dc=example', 'dc=org', 'count' => 2],
                'expect' => ['dc=example', 'dc=org', 'count' => 2],
            ],
            // #1
            [
                'args'   => ['***', -1],
                'return' => false,
                'expect' => false
            ],
            // #2
            [
                'args'   => ['***', -2],
                'return' => null,
                'expect' => false
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__explode_dn
     */
    public function test__explode_dn(array $args, $return, $expect)
    {
        $ldapArgs = array_map([$this, 'identicalTo'], $args);
        $this   ->getLdapFunctionMock("ldap_explode_dn")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);
        $this->assertSame($expect, LdapLink::explode_dn(...$args));
    }

    public static function prov__first_attribute()
    {
        return [
            // #0
            [
                'args'   => [],
                'return' => 'first',
                'expect' => 'first',
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
                'expect' => false
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__first_attribute
     */
    public function test__first_attribute(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLink();
        $result = $this->createLdapResult($ldap);
        $entry = $this->createLdapResultEntry($result);

        $ldapArgs = array_map(
            [$this, 'identicalTo'],
            array_merge([$ldap->getResource(), $entry->getResource()], $args)
        );

        $this   ->getLdapFunctionMock("ldap_first_attribute")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);
        $this->assertSame($expect, $ldap->first_attribute($entry, ...$args));
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
        $ldap = $this->createLdapLink();
        $result = $this->createLdapResult($ldap);

        $ldapArgs = array_map(
            [$this, 'identicalTo'],
            array_merge([$ldap->getResource(), $result->getResource()], $args)
        );

        $this   ->getLdapFunctionMock("ldap_first_entry")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $entry = $ldap->first_entry($result, ...$args);
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
        $ldap = $this->createLdapLink();
        $result = $this->createLdapResult($ldap);

        $ldapArgs = array_map(
            [$this, 'identicalTo'],
            array_merge([$ldap->getResource(), $result->getResource()], $args)
        );

        $this   ->getLdapFunctionMock("ldap_first_reference")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $reference = $ldap->first_reference($result, ...$args);
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
        $ldap = $this->createLdapLink();
        $result = $this->createLdapResult($ldap);

        $ldapArgs = array_map([$this, 'identicalTo'], array_merge([$result->getResource()], $args));

        $this   ->getLdapFunctionMock("ldap_free_result")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, LdapLink::free_result($result, ...$args));
    }

    public static function prov__get_attributes()
    {
        return [
            // #0
            [
                'args'   => [],
                'return' => ['1', '2'],
                'expect' => ['1', '2']
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
     * @dataProvider prov__get_attributes
     */
    public function test__get_attributes(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLink();
        $result = $this->createLdapResult($ldap);
        $entry = $this->createLdapResultEntry($result);

        $ldapArgs = array_map(
            [$this, 'identicalTo'],
            array_merge([$ldap->getResource(), $entry->getResource()], $args)
        );

        $this   ->getLdapFunctionMock("ldap_get_attributes")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $ldap->get_attributes($entry, ...$args));
    }

    public static function prov__get_dn()
    {
        return [
            // #0
            [
                'args'   => [],
                'return' => 'dc=example,dc=org',
                'expect' => 'dc=example,dc=org',
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
     * @dataProvider prov__get_dn
     */
    public function test__get_dn(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLink();
        $result = $this->createLdapResult($ldap);
        $entry = $this->createLdapResultEntry($result);

        $ldapArgs = array_map(
            [$this, 'identicalTo'],
            array_merge([$ldap->getResource(), $entry->getResource()], $args)
        );

        $this   ->getLdapFunctionMock("ldap_get_dn")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $ldap->get_dn($entry, ...$args));
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
        $ldap = $this->createLdapLink();
        $result = $this->createLdapResult($ldap);

        $ldapArgs = array_map(
            [$this, 'identicalTo'],
            array_merge([$ldap->getResource(), $result->getResource()], $args)
        );

        $this   ->getLdapFunctionMock("ldap_get_entries")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $ldap->get_entries($result, ...$args));
    }

    public static function prov__get_option()
    {
        return [
            // #0
            [
                'args'   => [1, &$rv1],
                'return' => true,
                'expect' => true,
                'value'  => 123,
            ],
            // #1
            [
                'args'   => [2, &$rv2],
                'return' => true,
                'expect' => true,
                'value'  => 'foo',
            ],
            // #2
            [
                'args'   => [2, &$rv3],
                'return' => false,
                'expect' => false,
                'value'  => null,
            ],
            // #3
            [
                'args'   => [-1, &$rv4],
                'return' => null,
                'expect' => false,
                'value'  => null,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__get_option
     */
    public function test__get_option(array $args, $return, $expect, $value)
    {
        $ldap = $this->createLdapLink();
        $ldapArgs = array_map([$this, 'identicalTo'], array_merge([$ldap->getResource()], $args));

        $this   ->getLdapFunctionMock("ldap_get_option")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturnCallback(function ($link, int $option, &$retval) use ($value, $return) {
                    $retval = $value;
                    return $return;
                });

        $this->assertSame($expect, $ldap->get_option(...$args));
        if (count($args) > 1) {
            $this->assertSame($value, $args[1]);
        }
    }

    public static function prov__get_values_len()
    {
        return [
            // #0
            [
                'args'   => ['foo'],
                'return' => [1, 2],
                'expect' => [1, 2],
            ],
            // #1
            [
                'args'   => ['bar'],
                'return' => false,
                'expect' => false,
            ],
            // #2
            [
                'args'   => ['#$#$'],
                'return' => null,
                'expect' => false
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__get_values_len
     */
    public function test__get_values_len(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLink();
        $result = $this->createLdapResult($ldap);
        $entry = $this->createLdapResultEntry($result);

        $ldapArgs = array_map(
            [$this, 'identicalTo'],
            array_merge([$ldap->getResource(), $entry->getResource()], $args)
        );

        $this   ->getLdapFunctionMock("ldap_get_values_len")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $ldap->get_values_len($entry, ...$args));
    }

    public static function prov__get_values()
    {
        return [
            // #0
            [
                'args'   => ['foo'],
                'return' => [1, 2],
                'expect' => [1, 2],
            ],
            // #1
            [
                'args'   => ['bar'],
                'return' => false,
                'expect' => false,
            ],
            // #2
            [
                'args'   => ['#$#$'],
                'return' => null,
                'expect' => false
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__get_values
     */
    public function test__get_values(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLink();
        $result = $this->createLdapResult($ldap);
        $entry = $this->createLdapResultEntry($result);

        $ldapArgs = array_map(
            [$this, 'identicalTo'],
            array_merge([$ldap->getResource(), $entry->getResource()], $args)
        );

        $this   ->getLdapFunctionMock("ldap_get_values")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $ldap->get_values($entry, ...$args));
    }

    public static function prov__list()
    {
        return [
            // #0
            [
                'args'   => ['dc=example,dc=org', 'objectclass=*'],
                'return' => 'ldap result 1',
                'expect' => ['getResource()' => 'ldap result 1'],
            ],

            // #1
            [
                'args'   => ['dc=example,dc=org', 'objectclass=*', ['a', 'b']],
                'return' => 'ldap result 2',
                'expect' => ['getResource()' => 'ldap result 2'],
            ],

            // #2
            [
                'args'   => ['dc=example,dc=org', 'objectclass=*', ['a', 'b'], 1],
                'return' => 'ldap result 3',
                'expect' => ['getResource()' => 'ldap result 3'],
            ],

            // #3
            [
                'args'   => ['dc=example,dc=org', 'objectclass=*', ['a', 'b'], 1, 123],
                'return' => 'ldap result 4',
                'expect' => ['getResource()' => 'ldap result 4'],
            ],

            // #4
            [
                'args'   => ['dc=example,dc=org', 'objectclass=*', ['a', 'b'], 1, 123, 456],
                'return' => 'ldap result 5',
                'expect' => ['getResource()' => 'ldap result 5'],
            ],

            // #5
            [
                'args'   => ['dc=example,dc=org', 'objectclass=*', ['a', 'b'], 1, 123, 456, 0],
                'return' => 'ldap result 6',
                'expect' => ['getResource()' => 'ldap result x'],
            ],

            // #6
            [
                'args'   => ['dc=acme,dc=com', 'objectclass=*'],
                'return' => false,
                'expect' => false,
            ],

            // #7
            [
                'args'   => ['$$$', 'objectclass=*'],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__list
     */
    public function test__list(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLink();
        $ldapArgs = array_map([$this, 'identicalTo'], array_merge([$ldap->getResource()], $args));

        $this   ->getLdapFunctionMock("ldap_list")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $result = $ldap->list(...$args);
        if ($return) {
            $this->assertInstanceOf(Result::class, $result);
            $this->assertSame($ldap, $result->getLdapLink());
            $this->assertHasPropertiesSameAs($expect, $result);
        } else {
            $this->assertSame($expect, $result);
        }
    }

    /**
     * @runInSeparateProcess
     */
    public function test__list_2()
    {
        $ldap = $this->createLdapLink();

        $this   ->getLdapFunctionMock("ldap_list")
                ->expects($this->once())
                ->with('ldap link', 'dc=korowai,dc=org', 'objectclass=*')
                ->willReturn('ldap result');

        $this->assertEquals(new Result('ldap result', $ldap), $ldap->list('dc=korowai,dc=org', 'objectclass=*'));
    }

    /**
     * @runInSeparateProcess
     */
    public function test__mod_add()
    {
        $ldap = $this->createLdapLink();

        $this   ->getLdapFunctionMock("ldap_mod_add")
                ->expects($this->once())
                ->with('ldap link', 'dc=korowai,dc=org', ['entry'])
                ->willReturn('ok');

        $this->assertSame('ok', $ldap->mod_add('dc=korowai,dc=org', ['entry']));
    }

    /**
     * @runInSeparateProcess
     */
    public function test__mod_del()
    {
        $ldap = $this->createLdapLink();

        $this   ->getLdapFunctionMock("ldap_mod_del")
                ->expects($this->once())
                ->with('ldap link', 'dc=korowai,dc=org', ['entry'])
                ->willReturn('ok');

        $this->assertSame('ok', $ldap->mod_del('dc=korowai,dc=org', ['entry']));
    }

    /**
     * @runInSeparateProcess
     */
    public function test__mod_replace()
    {
        $ldap = $this->createLdapLink();

        $this   ->getLdapFunctionMock("ldap_mod_replace")
                ->expects($this->once())
                ->with('ldap link', 'dc=korowai,dc=org', ['entry'])
                ->willReturn('ok');

        $this->assertSame('ok', $ldap->mod_replace('dc=korowai,dc=org', ['entry']));
    }

    /**
     * @runInSeparateProcess
     */
    public function test__modify_batch()
    {
        $ldap = $this->createLdapLink();

        $this   ->getLdapFunctionMock("ldap_modify_batch")
                ->expects($this->once())
                ->with('ldap link', 'dc=korowai,dc=org', ['entry'])
                ->willReturn('ok');

        $this->assertSame('ok', $ldap->modify_batch('dc=korowai,dc=org', ['entry']));
    }

    /**
     * @runInSeparateProcess
     */
    public function test__modify()
    {
        $ldap = $this->createLdapLink();

        $this   ->getLdapFunctionMock("ldap_modify")
                ->expects($this->once())
                ->with('ldap link', 'dc=korowai,dc=org', ['entry'])
                ->willReturn('ok');

        $this->assertSame('ok', $ldap->modify('dc=korowai,dc=org', ['entry']));
    }

    /**
     * @runInSeparateProcess
     */
    public function test__next_attribute()
    {
        $ldap = $this->createLdapLink();
        $result = $this->createLdapResult($ldap);
        $entry = $this->createLdapResultEntry($result);

        $this   ->getLdapFunctionMock("ldap_next_attribute")
                ->expects($this->once())
                ->with('ldap link', 'ldap result entry')
                ->willReturn('next attribute');

        $this->assertSame('next attribute', $ldap->next_attribute($entry));
    }

    /**
     * @runInSeparateProcess
     */
    public function test__next_entry_1()
    {
        $ldap = $this->createLdapLink();
        $result = $this->createLdapResult($ldap);
        $entry = $this->createLdapResultEntry($result);

        $this   ->getLdapFunctionMock("ldap_next_entry")
                ->expects($this->once())
                ->with('ldap link', 'ldap result entry')
                ->willReturn(false);

        $this->assertFalse($ldap->next_entry($entry));
    }

    /**
     * @runInSeparateProcess
     */
    public function test__next_entry_2()
    {
        $ldap = $this->createLdapLink();
        $result = $this->createLdapResult($ldap);
        $entry = $this->createLdapResultEntry($result);

        $this   ->getLdapFunctionMock("ldap_next_entry")
                ->expects($this->once())
                ->with($this->identicalTo($ldap->getResource()), $this->identicalTo($entry->getResource()))
                ->willReturn('next entry');

        $this->assertEquals(new ResultEntry('next entry', $result), $ldap->next_entry($entry));
    }

    /**
     * @runInSeparateProcess
     */
    public function test__next_reference_1()
    {
        $ldap = $this->createLdapLink();
        $result = $this->createLdapResult($ldap);
        $first = new ResultReference('first reference', $result);

        $this   ->getLdapFunctionMock("ldap_next_reference")
                ->expects($this->once())
                ->with('ldap link', 'first reference')
                ->willReturn(false);

        $this->assertFalse($ldap->next_reference($first));
    }

    /**
     * @runInSeparateProcess
     */
    public function test__next_reference_2()
    {
        $ldap = $this->createLdapLink();
        $result = $this->createLdapResult($ldap);
        $first = new ResultReference('first reference', $result);

        $this   ->getLdapFunctionMock("ldap_next_reference")
                ->expects($this->once())
                ->with('ldap link', 'first reference')
                ->willReturn('next reference');


        $this->assertEquals(new ResultReference('next reference', $result), $ldap->next_reference($first));
    }

    /**
     * @runInSeparateProcess
     */
    public function test__parse_reference()
    {
        $ldap = $this->createLdapLink();
        $result = $this->createLdapResult($ldap);
        $ref = new ResultReference('ldap result reference', $result);

        $this   ->getLdapFunctionMock("ldap_parse_reference")
                ->expects($this->once())
                ->with('ldap link', 'ldap result reference')
                ->willReturnCallback(function ($link, $ref, &$referrals) {
                    $referrals = ['ref 1', 'ref 2'];
                    return 'ok';
                });


        $this->assertSame('ok', $ldap->parse_reference($ref, $referrals));
        $this->assertSame(['ref 1', 'ref 2'], $referrals);
    }

    /**
     * @runInSeparateProcess
     */
    public function test__parse_result()
    {
        $ldap = $this->createLdapLink();
        $result = $this->createLdapResult($ldap);

        $this   ->getLdapFunctionMock("ldap_parse_result")
                ->expects($this->once())
                ->with('ldap link', 'ldap result')
                ->willReturnCallback(function ($link, $result, &$errcode, &...$tail) {
                    $errcode = 12;
                    if (count($tail) > 0) {
                        $tail[0] = 'matcheddn';
                    }
                    if (count($tail) > 1) {
                        $tail[1] = 'errmsg';
                    }
                    if (count($tail) > 2) {
                        $tail[2] = 'referrals';
                    }
                    return 'ok';
                });

        $this->assertSame('ok', $ldap->parse_result($result, $errcode));
        $this->assertSame(12, $errcode);
    }

    /**
     * @runInSeparateProcess
     */
    public function test__read_1()
    {
        $ldap = $this->createLdapLink();

        $this   ->getLdapFunctionMock("ldap_read")
                ->expects($this->once())
                ->with('ldap link', 'dc=korowai,dc=org', 'objectclass=*')
                ->willReturn(false);

        $this->assertSame(false, $ldap->read('dc=korowai,dc=org', 'objectclass=*'));
    }

    /**
     * @runInSeparateProcess
     */
    public function test__read_2()
    {
        $ldap = $this->createLdapLink();

        $this   ->getLdapFunctionMock("ldap_read")
                ->expects($this->once())
                ->with('ldap link', 'dc=korowai,dc=org', 'objectclass=*')
                ->willReturn('ldap result');

        $this->assertEquals(new Result('ldap result', $ldap), $ldap->read('dc=korowai,dc=org', 'objectclass=*'));
    }

    /**
     * @runInSeparateProcess
     */
    public function test__rename()
    {
        $ldap = $this->createLdapLink();

        $this   ->getLdapFunctionMock("ldap_rename")
                ->expects($this->once())
                ->with('ldap link', 'dc=korowai,dc=org', 'cn=ldap', 'dc=example,dc=org', true)
                ->willReturn('ok');

        $this->assertSame('ok', $ldap->rename('dc=korowai,dc=org', 'cn=ldap', 'dc=example,dc=org', true));
    }

    /**
     * @runInSeparateProcess
     */
    public function test__sasl_bind()
    {
        $ldap = $this->createLdapLink();
        $this   ->getLdapFunctionMock("ldap_sasl_bind")
                ->expects($this->once())
                ->with('ldap link', 'dc=korowai,dc=org', '$3cr3t', 'mech', 'realm', 'authc_id', 'authz_id', 'props')
                ->willReturn('ok');

        $this->assertSame('ok', $ldap->sasl_bind('dc=korowai,dc=org', '$3cr3t', 'mech', 'realm', 'authc_id', 'authz_id', 'props'));
    }

    /**
     * @runInSeparateProcess
     */
    public function test__search_1()
    {
        $ldap = $this->createLdapLink();

        $this   ->getLdapFunctionMock("ldap_search")
                ->expects($this->once())
                ->with('ldap link', 'dc=korowai,dc=org', 'objectclass=*')
                ->willReturn(false);

        $this->assertSame(false, $ldap->search('dc=korowai,dc=org', 'objectclass=*'));
    }

    /**
     * @runInSeparateProcess
     */
    public function test__search_2()
    {
        $ldap = $this->createLdapLink();

        $this   ->getLdapFunctionMock("ldap_search")
                ->expects($this->once())
                ->with('ldap link', 'dc=korowai,dc=org', 'objectclass=*')
                ->willReturn('ldap result');

        $this->assertEquals(new Result('ldap result', $ldap), $ldap->search('dc=korowai,dc=org', 'objectclass=*'));
    }

    /**
     * @runInSeparateProcess
     */
    public function test__set_option()
    {
        $ldap = $this->createLdapLink();

        $this   ->getLdapFunctionMock("ldap_set_option")
                ->expects($this->once())
                ->with('ldap link', 12, 'value')
                ->willReturn('ok');

        $this->assertSame('ok', $ldap->set_option(12, 'value'));
    }

    /**
     * @runInSeparateProcess
     */
    public function test__set_rebind_proc()
    {
        $ldap = $this->createLdapLink();

        $proc = function () {
        };

        $this   ->getLdapFunctionMock("ldap_set_rebind_proc")
                ->expects($this->once())
                ->with('ldap link', $this->identicalTo($proc))
                ->willReturn('ok');

        $this->assertSame('ok', $ldap->set_rebind_proc($proc));
    }

    /**
     * @runInSeparateProcess
     */
    public function test__sort()
    {
        $ldap = $this->createLdapLink();
        $result = $this->createLdapResult($ldap);

        $this   ->getLdapFunctionMock("ldap_sort")
                ->expects($this->once())
                ->with('ldap link', 'ldap result', 'sortfilter')
                ->willReturn('ok');

        $this->assertSame('ok', $ldap->sort($result, 'sortfilter'));
    }

    /**
     * @runInSeparateProcess
     */
    public function test__start_tls()
    {
        $ldap = $this->createLdapLink();

        $this   ->getLdapFunctionMock("ldap_start_tls")
                ->expects($this->once())
                ->with('ldap link')
                ->willReturn('ok');

        $this->assertSame('ok', $ldap->start_tls());
    }

    /**
     * @runInSeparateProcess
     */
    public function test__unbind()
    {
        $ldap = $this->createLdapLink();

        $this   ->getLdapFunctionMock("ldap_unbind")
                ->expects($this->once())
                ->with('ldap link')
                ->willReturn('ok');

        $this->assertSame('ok', $ldap->unbind());
    }

    /**
     * @runInSeparateProcess
     */
    public function test__destruct_Uninitialized()
    {
        $this   ->getLdapFunctionMock("ldap_unbind")
                ->expects($this->never());
        $link = new LdapLink(null);
        unset($link);
    }

    /**
     * @runInSeparateProcess
     */
    public function test__destruct_UnbindSuccess()
    {
        $this   ->getLdapFunctionMock("ldap_unbind")
                ->expects($this->once())
                ->with('ldap link')
                ->willReturn(true);
        $this   ->getLdapFunctionMock("is_resource")
                ->expects($this->once())
                ->with('ldap link')
                ->willReturn(true);
        $this   ->getLdapFunctionMock("get_resource_type")
                ->expects($this->once())
                ->with('ldap link')
                ->willReturn('ldap link');
        $link = new LdapLink('ldap link');
        unset($link);
    }
}

// vim: syntax=php sw=4 ts=4 et:
