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
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\HasResource;
use Korowai\Lib\Ldap\Adapter\ExtLdap\Result;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultEntry;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultReference;

// tests with process isolation can't use native PHP closures (they're not serializable)
use Korowai\Tests\Lib\Ldap\Adapter\ExtLdap\Closures\LdapGetOptionClosure;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapLinkTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    private function getLdapFunctionMock(...$args)
    {
        return $this->getFunctionMock('\Korowai\Lib\Ldap\Adapter\ExtLdap', ...$args);
    }

    /**
     * Returns an array of constraints based on $args for ldap function call
     * expectation.
     *
     * @return array
     */
    private function makeArgsForLdapMock(array $args, LdapLink $ldap = null) : array
    {
        if ($ldap !== null) {
            return array_map([$this, 'identicalTo'], array_merge([$ldap->getResource()], $args));
        }
        return array_map([$this, 'identicalTo'], $args);
    }

    /**
     * Returns new instance of LdapLink.
     *
     * @param  mixed $resource
     *
     * @return LdapLink
     */
    private static function createLdapLink($resource = 'ldap link') : LdapLink
    {
        return new LdapLink($resource);
    }

    /**
     * Returns data to be provided to: test__list, test__read, and test__search.
     *
     * @return array
     */
    private static function getProviderDataForSearchFunc() : array
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
                'expect' => ['getResource()' => 'ldap result 6'],
            ],

            // #6
            [
                'args'   => ['dc=example,dc=org', 'objectclass=*', ['a', 'b'], 1, 123, 456, 0, ['serverctls']],
                'return' => 'ldap result 6',
                'expect' => ['getResource()' => 'ldap result 6'],
            ],

            // #7
            [
                'args'   => ['', ''],
                'return' => false,
                'expect' => false,
            ],

            // #8
            [
                'args'   => ['', ''],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * Implementation of test__list, test__read, and test__search.
     */
    private function performSearchFuncTest(string $func, array $args, $return, $expect) : void
    {
        $ldap = $this->createLdapLink();
        $ldapArgs = $this->makeArgsForLdapMock($args, $ldap);

        $this   ->getLdapFunctionMock("ldap_$func")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $result = call_user_func_array([$ldap, $func], $args);
        if ($return) {
            $this->assertInstanceOf(Result::class, $result);
            $this->assertSame($ldap, $result->getLdapLink());
            $this->assertHasPropertiesSameAs($expect, $result);
        } else {
            $this->assertSame($expect, $result);
        }
    }

    private static function getProviderDataForModifyFunc()
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
                'args'   => ['dc=example,dc=org', ['foo', 'bar'], ['serverctls']],
                'return' => true,
                'expect' => true,
            ],

            // #3
            [
                'args'   => ['', []],
                'return' => false,
                'expect' => false,
            ],

            // #4
            [
                'args'   => ['', []],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    private function performModifyFuncTest(string $func, array $args, $return, $expect) : void
    {
        $ldap = $this->createLdapLink();
        $ldapArgs = $this->makeArgsForLdapMock($args, $ldap);

        $this   ->getLdapFunctionMock("ldap_$func")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, call_user_func_array([$ldap, $func], $args));
    }

    public function test__implementes__LdapLinkInterface() : void
    {
        $this->assertImplementsInterface(LdapLinkInterface::class, LdapLink::class);
    }

    public function test__uses__HasResource()
    {
        $this->assertUsesTrait(HasResource::class, LdapLink::class);
    }

    public static function prov__isLdapLinkResource()
    {
        $open = \ldap_connect('localhost');
        $close = \ldap_connect('localhost');
        \ldap_close($close);

        return [
            // #0
            'null' => [
                'args'   => [null],
                'expect' => false
            ],

            // #1
            '"foo"' => [
                'args'   => ['foo'],
                'expect' => false
            ],

            // #2
            'open ldap link' => [
                'args'   => [$open],
                'expect' => true
            ],

            // #3
            'closed ldap link' => [
                'args'   => [$close],
                'expect' => false
            ],
        ];
    }

    /**
     * @dataProvider prov__isLdapLinkResource
     */
    public function test__isLdapLinkResource(array $args, $expect) : void
    {
        $this->assertSame($expect, LdapLink::isLdapLinkResource(...$args));
    }

    public static function prov__getResource()
    {
        return [
            // #0
            [
                'args'   => [null],
                'expect' => null,
            ],

            // #1
            [
                'args'   => ['ldap link'],
                'expect' => 'ldap link',
            ],
        ];
    }

    /**
     * @dataProvider prov__getResource
     */
    public function test__getResource(array $args, $expect) : void
    {
        $link = new LdapLink(...$args);
        $this->assertSame($expect, $link->getResource());
    }

    public static function prov__isValid()
    {
        return static::prov__isLdapLinkResource();
    }

    /**
     * @dataProvider prov__isValid
     */
    public function test__isValid(array $args, $expect) : void
    {
        $link = new LdapLink(...$args);
        $this->assertSame($expect, $link->isValid());
    }

    public static function prov__add()
    {
        return static::getProviderDataForModifyFunc();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__add
     */
    public function test__add(array $args, $return, $expect)
    {
        static::performModifyFuncTest('add', $args, $return, $expect);
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
        $ldapArgs = $this->makeArgsForLdapMock($args, $ldap);

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
        $ldapArgs = $this->makeArgsForLdapMock($args, $ldap);

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
                'args'   => ['dc=example,dc=org', 'attribute', 'value', ['serverctls']],
                'return' => true,
                'expect' => true
            ],
            // #2
            [
                'args'   => ['', '', ''],
                'return' => false,
                'expect' => false
            ],
            // #3
            [
                'args'   => ['', '', ''],
                'return' => -1,
                'expect' => -1
            ],
            // #4
            [
                'args'   => ['', '', ''],
                'return' => null,
                'expect' => -1
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
        $ldapArgs = $this->makeArgsForLdapMock($args, $ldap);

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
                'args'   => [null, 123],
                'return' => 'ldap link 5',
                'expect' => ['getResource()' => 'ldap link 5'],
            ],
            // #5
            [
                'args'   => ['ldapi:///', 123],
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
        $ldapArgs = $this->makeArgsForLdapMock($args);
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
        $ldapArgs = $this->makeArgsForLdapMock($args, $ldap);

        $this   ->getLdapFunctionMock("ldap_control_paged_result")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $ldap->control_paged_result(...$args));
    }

    public static function prov__delete()
    {
        return static::getProviderDataForModifyFunc();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__delete
     */
    public function test__delete(array $args, $return, $expect)
    {
        static::performModifyFuncTest('delete', $args, $return, $expect);
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
        $ldapArgs = $this->makeArgsForLdapMock($args);

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
        $ldapArgs = $this->makeArgsForLdapMock($args);

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
        $ldapArgs = $this->makeArgsForLdapMock($args, $ldap);

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
        $ldapArgs = $this->makeArgsForLdapMock($args, $ldap);

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
        $ldapArgs = $this->makeArgsForLdapMock($args);
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
        $ldapArgs = $this->makeArgsForLdapMock($args);
        $this   ->getLdapFunctionMock("ldap_explode_dn")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);
        $this->assertSame($expect, LdapLink::explode_dn(...$args));
    }

    public static function prov__get_option()
    {
        return [
            // #0
            [
                'args'   => [1, &$rv1],
                'return' => true,
                'expect' => true,
                'values' => [123],
            ],
            // #1
            [
                'args'   => [2, &$rv2],
                'return' => true,
                'expect' => true,
                'values' => ['foo'],
            ],
            // #2
            [
                'args'   => [2, &$rv3],
                'return' => false,
                'expect' => false,
                'values' => [null],
            ],
            // #3
            [
                'args'   => [-1, &$rv4],
                'return' => null,
                'expect' => false,
                'values' => [null],
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__get_option
     */
    public function test__get_option(array $args, $return, $expect, array $values)
    {
        $ldap = $this->createLdapLink();
        $ldapArgs = $this->makeArgsForLdapMock($args, $ldap);

        $this   ->getLdapFunctionMock("ldap_get_option")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturnCallback(new LdapGetOptionClosure($return, $values));

        $this->assertSame($expect, $ldap->get_option(...$args));
        if (count($args) > 1) {
            $this->assertSame($values[0] ?? null, $args[1]);
        }
    }

    public static function prov__list()
    {
        return static::getProviderDataForSearchFunc();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__list
     */
    public function test__list(array $args, $return, $expect)
    {
        $this->performSearchFuncTest('list', $args, $return, $expect);
    }

    public static function prov__mod_add()
    {
        return static::getProviderDataForModifyFunc();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__mod_add
     */
    public function test__mod_add(array $args, $return, $expect)
    {
        static::performModifyFuncTest('mod_add', $args, $return, $expect);
    }

    public static function prov__mod_del()
    {
        return static::getProviderDataForModifyFunc();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__mod_del
     */
    public function test__mod_del(array $args, $return, $expect)
    {
        static::performModifyFuncTest('mod_del', $args, $return, $expect);
    }

    public static function prov__mod_replace()
    {
        return static::getProviderDataForModifyFunc();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__mod_replace
     */
    public function test__mod_replace(array $args, $return, $expect)
    {
        static::performModifyFuncTest('mod_replace', $args, $return, $expect);
    }

    public static function prov__modify_batch()
    {
        return static::getProviderDataForModifyFunc();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__modify_batch
     */
    public function test__modify_batch(array $args, $return, $expect)
    {
        static::performModifyFuncTest('modify_batch', $args, $return, $expect);
    }

    public static function prov__modify()
    {
        return static::getProviderDataForModifyFunc();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__modify
     */
    public function test__modify(array $args, $return, $expect)
    {
        static::performModifyFuncTest('modify', $args, $return, $expect);
    }

    public static function prov__read()
    {
        return static::getProviderDataForSearchFunc();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__read
     */
    public function test__read(array $args, $return, $expect)
    {
        $this->performSearchFuncTest('read', $args, $return, $expect);
    }

    public static function prov__rename()
    {
        return [
            // #0
            [
                'args'   => ['cn=foo,dc=example,dc=org', 'cn=bar', '', false],
                'return' => true,
                'expect' => true,
            ],

            // #1
            [
                'args'   => ['cn=foo,dc=example,dc=org', 'cn=bar', '', false, ['serverctls']],
                'return' => true,
                'expect' => true,
            ],

            // #2
            [
                'args'   => ['', '', '', false],
                'return' => false,
                'expect' => false,
            ],

            // #3
            [
                'args'   => ['', '', '', false],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__rename
     */
    public function test__rename(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLink();
        $ldapArgs = $this->makeArgsForLdapMock($args, $ldap);

        $this   ->getLdapFunctionMock("ldap_rename")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $ldap->rename(...$args));
    }

    public static function prov__sasl_bind()
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
                'args'   => ['cn=admin,dc=example,dc=org'],
                'return' => true,
                'expect' => true,
            ],

            // #2
            [
                'args'   => ['cn=admin,dc=example,dc=org', '$3cr3t'],
                'return' => true,
                'expect' => true,
            ],

            // #3
            [
                'args'   => ['cn=admin,dc=example,dc=org', '$3cr3t', 'mech'],
                'return' => true,
                'expect' => true,
            ],

            // #4
            [
                'args'   => ['cn=admin,dc=example,dc=org', '$3cr3t', 'mech', 'realm'],
                'return' => true,
                'expect' => true,
            ],

            // #5
            [
                'args'   => ['cn=admin,dc=example,dc=org', '$3cr3t', 'mech', 'realm', 'authc_id'],
                'return' => true,
                'expect' => true,
            ],

            // #6
            [
                'args'   => ['cn=admin,dc=example,dc=org', '$3cr3t', 'mech', 'realm', 'authc_id', 'authz_id'],
                'return' => true,
                'expect' => true,
            ],

            // #7
            [
                'args'   => ['cn=admin,dc=example,dc=org', '$3cr3t', 'mech', 'realm', 'authc_id', 'authz_id', 'props'],
                'return' => true,
                'expect' => true,
            ],

            // #8
            [
                'args'   => ['$$'],
                'return' => false,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__sasl_bind
     */
    public function test__sasl_bind(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLink();
        $ldapArgs = $this->makeArgsForLdapMock($args, $ldap);

        $this   ->getLdapFunctionMock("ldap_sasl_bind")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $result = $ldap->sasl_bind(...$args);
        $this->assertSame($expect, $result);
    }

    public static function prov__search()
    {
        return static::getProviderDataForSearchFunc();
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__search
     */
    public function test__search(array $args, $return, $expect)
    {
        $this->performSearchFuncTest('search', $args, $return, $expect);
    }

    public static function prov__set_option()
    {
        return [
            // #0
            [
                'args'   => [1, 'a'],
                'return' => true,
                'expect' => true,
            ],

            // #1
            [
                'args'   => [2, ['x']],
                'return' => false,
                'expect' => false,
            ],

            // #2
            [
                'args'   => [0, ''],
                'return' => false,
                'expect' => false,
            ],

            // #3
            [
                'args'   => [-1, null],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__set_option
     */
    public function test__set_option(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLink();
        $ldapArgs = $this->makeArgsForLdapMock($args, $ldap);
        $this   ->getLdapFunctionMock("ldap_set_option")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $ldap->set_option(...$args));
    }

    public static function prov__set_rebind_proc()
    {
        return [
            // #0
            [
                'args'   => ['foo'],
                'return' => true,
                'expect' => true,
            ],

            // #1
            [
                'args'   => [''], // unsetting
                'return' => true,
                'expect' => true,
            ],

            // #2
            [
                'args'   => ['inexistent'],
                'return' => false,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__set_rebind_proc
     */
    public function test__set_rebind_proc(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLink();
        $ldapArgs = $this->makeArgsForLdapMock($args, $ldap);

        $this   ->getLdapFunctionMock("ldap_set_rebind_proc")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $ldap->set_rebind_proc(...$args));
    }

    public static function prov__start_tls()
    {
        return [
            // #1
            [
                'args'   => [],
                'return' => true,
                'expect' => true,
            ],

            // #2
            [
                'args'   => [],
                'return' => false,
                'expect' => false,
            ],

            // #3
            [
                'args'   => [],
                'return' => null,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__start_tls
     */
    public function test__start_tls(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLink();
        $ldapArgs = $this->makeArgsForLdapMock($args, $ldap);

        $this   ->getLdapFunctionMock("ldap_start_tls")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $ldap->start_tls(...$args));
    }

    public static function prov__unbind()
    {
        return [
            // #1
            [
                'args'   => [],
                'return' => true,
                'expect' => true,
            ],

            // #2
            [
                'args'   => [],
                'return' => false,
                'expect' => false,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__unbind
     */
    public function test__unbind(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLink();
        $ldapArgs = $this->makeArgsForLdapMock($args, $ldap);

        $this   ->getLdapFunctionMock("ldap_unbind")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $ldap->unbind(...$args));
    }

    /**
     * @runInSeparateProcess
     */
    public function test__unset__doesNotFreeTheResource()
    {
        $ldap = new LdapLink('ldap link');

        $this->getLdapFunctionMock('ldap_free_ldap')
             ->expects($this->never());

        unset($ldap);
    }
}

// vim: syntax=php sw=4 ts=4 et:
