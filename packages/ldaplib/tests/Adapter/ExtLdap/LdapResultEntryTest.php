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

use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultEntry;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultEntryInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultRecord;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapResultEntryTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    private function getLdapFunctionMock(...$args)
    {
        return $this->getFunctionMock('\Korowai\Lib\Ldap\Adapter\ExtLdap', ...$args);
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

    private function createLdapLinkMock($resource = 'ldap link') : LdapLinkInterface
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

    private function createLdapResultMock(LdapLinkInterface $ldap = null, $resource = 'ldap result', array $methods = [])
    {
        $builder = $this->getMockBuilder(LdapResultInterface::class);

        if ($ldap !== null && !in_array('getLdapLink', $methods)) {
            $methods[] = 'getLdapLink';
        }

        if ($resource !== null && !in_array('getLdapLink', $methods)) {
            $methods[] = 'getResource';
        }

        $builder->setMethods($methods);

        $mock = $builder->getMockForAbstractClass();

        if ($ldap !== null) {
            $mock->expects($this->any())
                   ->method('getLdapLink')
                   ->with()
                   ->willReturn($ldap);
        }

        if ($resource !== null) {
            $mock->expects($this->any())
                   ->method('getResource')
                   ->with()
                   ->willReturn($resource);
        }

        return $mock;
    }

    private function makeArgsForBackendMock(
        array $args,
        LdapResultEntry $entry = null,
        LdapLinkInterface $ldap = null
    ) : array {
        $resources = [];
        if ($ldap !== null) {
            $resources[] = $ldap->getResource();
        }
        if ($entry !== null) {
            $resources[] = $entry->getResource();
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
        $result = $this->createLdapResultMock($ldap);
        $entry = new LdapResultEntry('ldap result entry', $result);

        if ($backendWithoutLdapLink) {
            $ldapArgs = $this->makeArgsForBackendMock($args, $entry);
        } else {
            $ldapArgs = $this->makeArgsForBackendMock($args, $entry, $ldap);
        }

        $this   ->getLdapFunctionMock("ldap_$func")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, call_user_func_array([$entry, $func], $args));
    }

    //
    //
    // TESTS
    //
    //

    public function test__extends__LdapResultRecord()
    {
        $this->assertExtendsClass(LdapResultRecord::class, LdapResultEntry::class);
    }

    public function test__implements__LdapResultEntryInterface()
    {
        $this->assertImplementsInterface(LdapResultEntryInterface::class, LdapResultEntry::class);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getResource()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function test__getResource()
    {
        $result = $this->createLdapResultMock(null, null);
        $entry = new LdapResultEntry('ldap entry', $result);
        $this->assertSame('ldap entry', $entry->getResource());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getLdapResult()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function test__getLdapResult()
    {
        $result = $this->createLdapResultMock(null, null);
        $entry = new LdapResultEntry('ldap entry', $result);
        $this->assertSame($result, $entry->getLdapResult());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // isLdapResultEntryResource()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__isLdapResultEntryResource()
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
                'arg'    => 'ldap result entry',
                'return' => 'ldap result entry',
                'expect' => true,
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__isLdapResultEntryResource
     */
    public function test__isLdapResultEntryResource($arg, $return, $expect)
    {
        $this->mockResourceFunctions($arg, $return);
        $this->assertSame($expect, LdapResultEntry::isLdapResultEntryResource($arg));
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // isValid()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @runInSeparateProcess
     * @dataProvider prov__isLdapResultEntryResource
     */
    public function test__isValid($arg, $return, $expect)
    {
        $this->mockResourceFunctions($arg, $return);
        $result = $this->createLdapResultMock(null, null);
        $entry = new LdapResultEntry($arg, $result);
        $this->assertSame($expect, $entry->isValid());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // get_dn()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__get_dn__withMockedBackend()
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
     * @dataProvider prov__get_dn__withMockedBackend
     */
    public function test__get_dn__withMockedBackend(array $args, $return, $expect)
    {
        $this->examineFuncWithMockedBackend('get_dn', $args, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // first_attribute()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__first_attribute__withMockedBackend()
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
     * @dataProvider prov__first_attribute__withMockedBackend
     */
    public function test__first_attribute__withMockedBackend(array $args, $return, $expect)
    {
        $this->examineFuncWithMockedBackend('first_attribute', $args, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // get_attributes()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__get_attributes__withMockedBackend()
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
     * @dataProvider prov__get_attributes__withMockedBackend
     */
    public function test__get_attributes__withMockedBackend(array $args, $return, $expect)
    {
        $this->examineFuncWithMockedBackend('get_attributes', $args, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // get_values_len()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__get_values_len__withMockedBackend()
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
     * @dataProvider prov__get_values_len__withMockedBackend
     */
    public function test__get_values_len__withMockedBackend(array $args, $return, $expect)
    {
        $this->examineFuncWithMockedBackend('get_values_len', $args, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // get_values()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__get_values__withMockedBackend()
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
     * @dataProvider prov__get_values__withMockedBackend
     */
    public function test__get_values__withMockedBackend(array $args, $return, $expect)
    {
        $this->examineFuncWithMockedBackend('get_values', $args, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // next_attribute()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__next_attribute__withMockedBackend()
    {
        return [
            // #0
            [
                'args'   => [],
                'return' => 'second',
                'expect' => 'second'
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
     * @dataProvider prov__next_attribute__withMockedBackend
     */
    public function test__next_attribute__withMockedBackend(array $args, $return, $expect)
    {
        $this->examineFuncWithMockedBackend('next_attribute', $args, $return, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // next_entry()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__next_entry__withMockedBackend()
    {
        return [
            // #0
            [
                'args'   => [],
                'return' => 'ldap entry next',
                'expect' => ['getResource()' => 'ldap entry next'],
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
     * @dataProvider prov__next_entry__withMockedBackend
     */
    public function test__next_entry__withMockedBackend(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLinkMock();
        $result = $this->createLdapResultMock($ldap);
        $entry = new LdapResultEntry('ldap result entry', $result);

        $ldapArgs = array_map(
            [$this, 'identicalTo'],
            array_merge([$ldap->getResource(), $entry->getResource()], $args)
        );

        $this   ->getLdapFunctionMock("ldap_next_entry")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $next = $entry->next_entry(...$args);
        if ($return) {
            $this->assertInstanceOf(LdapResultEntry::class, $next);
            $this->assertSame($result, $next->getLdapResult());
            $this->assertHasPropertiesSameAs($expect, $next);
        } else {
            $this->assertSame($expect, $next);
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
