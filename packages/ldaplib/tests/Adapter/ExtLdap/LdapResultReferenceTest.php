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

use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultReference;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultReferenceInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultRecord;

// tests with process isolation can't use native PHP closures (they're not serializable)
use Korowai\Tests\Lib\Ldap\Adapter\ExtLdap\Closures\LdapParseReferenceClosure;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapResultReferenceTest extends TestCase
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
        LdapResultReference $reference = null,
        LdapLinkInterface $ldap = null
    ) : array {
        $resources = [];
        if ($ldap !== null) {
            $resources[] = $ldap->getResource();
        }
        if ($reference !== null) {
            $resources[] = $reference->getResource();
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
        $reference = new LdapResultReference('ldap result reference', $result);

        if ($backendWithoutLdapLink) {
            $ldapArgs = $this->makeArgsForBackendMock($args, $reference);
        } else {
            $ldapArgs = $this->makeArgsForBackendMock($args, $reference, $ldap);
        }

        $this   ->getLdapFunctionMock("ldap_$func")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, call_user_func_array([$reference, $func], $args));
    }

    //
    //
    // TESTS
    //
    //

    public function test__extends__LdapResultRecord()
    {
        $this->assertExtendsClass(LdapResultRecord::class, LdapResultReference::class);
    }

    public function test__implements__LdapResultReferenceInterface()
    {
        $this->assertImplementsInterface(LdapResultReferenceInterface::class, LdapResultReference::class);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getResource()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function test__getResource()
    {
        $result = $this->createLdapResultMock();
        $ref = new LdapResultReference('ldap reference', $result);
        $this->assertSame('ldap reference', $ref->getResource());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getLdapResult()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function test__getLdapResult()
    {
        $result = $this->createLdapResultMock(null, null);
        $ref = new LdapResultReference('ldap reference', $result);
        $this->assertSame($result, $ref->getLdapResult());
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
        $this->assertSame($expect, LdapResultReference::isLdapResultEntryResource($arg));
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
        $entry = new LdapResultReference($arg, $result);
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
    // next_reference()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__next_reference__withMockedBackend()
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
     * @dataProvider prov__next_reference__withMockedBackend
     */
    public function test__next_reference__withMockedBackend(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLinkMock();
        $result = $this->createLdapResultMock($ldap);
        $reference = new LdapResultReference('ldap result reference', $result);

        $ldapArgs = $this->makeArgsForBackendMock($args, $reference, $ldap);

        $this   ->getLdapFunctionMock("ldap_next_reference")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $next = $reference->next_reference(...$args);
        if ($return) {
            $this->assertInstanceOf(LdapResultReference::class, $next);
            $this->assertSame($result, $next->getLdapResult());
            $this->assertHasPropertiesSameAs($expect, $next);
        } else {
            $this->assertSame($expect, $next);
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // parse_reference()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function prov__parse_reference__withMockedBackend()
    {
        return [
            // #0
            [
                'args'   => [&$rv1],
                'return' => true,
                'expect' => true,
                'values' => [['r']],
            ],

            // #1
            [
                'args'   => [&$rv2],
                'return' => false,
                'expect' => false,
                'values' => [null],
            ],

            // #2
            [
                'args'   => [&$rv3],
                'return' => false,
                'expect' => false,
                'values' => [null],
            ],
        ];
    }


    /**
     * @runInSeparateProcess
     * @dataProvider prov__parse_reference__withMockedBackend
     */
    public function test__parse_reference__withMockedBackend(array $args, $return, $expect, array $values)
    {
        $ldap = $this->createLdapLinkMock();
        $result = $this->createLdapResultMock($ldap);
        $reference = new LdapResultReference('ldap result reference', $result);

        $ldapArgs = $this->makeArgsForBackendMock($args, $reference, $ldap);

        $this   ->getLdapFunctionMock("ldap_parse_reference")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturnCallback(new LdapParseReferenceClosure($return, $values));

        $this->assertSame($expect, $reference->parse_reference(...$args));
        if (count($args) > 1) {
            $this->assertSame($values[0] ?? null, $args[1]);
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
