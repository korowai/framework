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

use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultEntry;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultAttributeIterator;
use Korowai\Lib\Ldap\Adapter\ResultEntryToEntry;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ExtLdapResultEntryInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ExtLdapResultInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultEntryTest extends TestCase
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
        $builder = $this->getMockBuilder(ExtLdapResultInterface::class);

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

    private function createLdapResultEntry(ExtLdapResultInterface $result = null, $resource = 'ldap result entry')
    {
        return new ResultEntry($resource, $result);
    }

    public function test__implements__ExtLdapResultEntryInterface()
    {
        $this->assertImplementsInterface(ExtLdapResultEntryInterface::class, ResultEntry::class);
    }

    public function test__uses__ResultEntryToEntry()
    {
        $this->assertUsesTrait(ResultEntryToEntry::class, ResultEntry::class);
    }

    public function test_getResource()
    {
        $result = $this->createLdapResultMock(null, null);
        $entry = new ResultEntry('ldap entry', $result);
        $this->assertSame('ldap entry', $entry->getResource());
    }

    public function test_getResult()
    {
        $result = $this->createLdapResultMock(null, null);
        $entry = new ResultEntry('ldap entry', $result);
        $this->assertSame($result, $entry->getResult());
    }

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
        $this->assertSame($expect, ResultEntry::isLdapResultEntryResource($arg));
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__isLdapResultEntryResource
     */
    public function test__isValid($arg, $return, $expect)
    {
        $this->mockResourceFunctions($arg, $return);
        $result = $this->createLdapResultMock(null, null);
        $entry = new ResultEntry($arg, $result);
        $this->assertSame($expect, $entry->isValid());
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
        $ldap = $this->createLdapLinkMock();
        $result = $this->createLdapResultMock($ldap);
        $entry = $this->createLdapResultEntry($result);

        $ldapArgs = array_map(
            [$this, 'identicalTo'],
            array_merge([$ldap->getResource(), $entry->getResource()], $args)
        );

        $this   ->getLdapFunctionMock("ldap_first_attribute")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);
        $this->assertSame($expect, $entry->first_attribute(...$args));
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
        $ldap = $this->createLdapLinkMock();
        $result = $this->createLdapResultMock($ldap);
        $entry = $this->createLdapResultEntry($result);

        $ldapArgs = array_map(
            [$this, 'identicalTo'],
            array_merge([$ldap->getResource(), $entry->getResource()], $args)
        );

        $this   ->getLdapFunctionMock("ldap_get_attributes")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $entry->get_attributes(...$args));
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
        $ldap = $this->createLdapLinkMock();
        $result = $this->createLdapResultMock($ldap);
        $entry = $this->createLdapResultEntry($result);

        $ldapArgs = array_map(
            [$this, 'identicalTo'],
            array_merge([$ldap->getResource(), $entry->getResource()], $args)
        );

        $this   ->getLdapFunctionMock("ldap_get_dn")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $entry->get_dn(...$args));
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
        $ldap = $this->createLdapLinkMock();
        $result = $this->createLdapResultMock($ldap);
        $entry = $this->createLdapResultEntry($result);

        $ldapArgs = array_map(
            [$this, 'identicalTo'],
            array_merge([$ldap->getResource(), $entry->getResource()], $args)
        );

        $this   ->getLdapFunctionMock("ldap_get_values_len")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $entry->get_values_len(...$args));
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
        $ldap = $this->createLdapLinkMock();
        $result = $this->createLdapResultMock($ldap);
        $entry = $this->createLdapResultEntry($result);

        $ldapArgs = array_map(
            [$this, 'identicalTo'],
            array_merge([$ldap->getResource(), $entry->getResource()], $args)
        );

        $this   ->getLdapFunctionMock("ldap_get_values")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $entry->get_values(...$args));
    }

    public static function prov__next_attribute()
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
     * @dataProvider prov__next_attribute
     */
    public function test__next_attribute(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLinkMock();
        $result = $this->createLdapResultMock($ldap);
        $entry = $this->createLdapResultEntry($result);

        $ldapArgs = array_map(
            [$this, 'identicalTo'],
            array_merge([$ldap->getResource(), $entry->getResource()], $args)
        );

        $this   ->getLdapFunctionMock("ldap_next_attribute")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $entry->next_attribute(...$args));
    }

    public static function prov__next_entry()
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
     * @dataProvider prov__next_entry
     */
    public function test__next_entry(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLinkMock();
        $result = $this->createLdapResultMock($ldap);
        $entry = $this->createLdapResultEntry($result);

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
            $this->assertInstanceOf(ResultEntry::class, $next);
            $this->assertSame($result, $next->getResult());
            $this->assertHasPropertiesSameAs($expect, $next);
        } else {
            $this->assertSame($expect, $next);
        }
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__get_dn
     */
    public function test_getDn(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLinkMock();
        $result = $this->createLdapResultMock($ldap);
        $entry = $this->createLdapResultEntry($result);

        $ldapArgs = array_map(
            [$this, 'identicalTo'],
            array_merge([$ldap->getResource(), $entry->getResource()], $args)
        );

        $this   ->getLdapFunctionMock("ldap_get_dn")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        if ($expect === false) {
            $expect = '';
        }

        $this->assertSame($expect, $entry->getDn(...$args));
    }

    public static function prov__getAttributes()
    {
        return [
            // #0
            [
                'args'   => [],
                'return' => [
                    'uid' => [
                        0 => 'korowai',
                        'count' => 1
                    ],
                    'firstName' => [
                        0 => 'Old',
                        'count' => 1

                    ],
                    'sn' => [
                        0 => 'Bro',
                        1 => 'Foo',
                        'count' => 2
                    ],
                    'count' => 3
                ],
                'expect' => [
                    'uid' => ['korowai'],
                    'firstname' => ['Old'],
                    'sn' => ['Bro', 'Foo']
                ]
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider prov__getAttributes
     */
    public function test_getAttributes(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLinkMock();
        $result = $this->createLdapResultMock($ldap);
        $entry = $this->createLdapResultEntry($result);

        $ldapArgs = array_map([$this, 'identicalTo'], array_merge([$ldap->getResource(), $entry->getResource()]));

        $this   ->getLdapFunctionMock("ldap_get_attributes")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $this->assertSame($expect, $entry->getAttributes());
    }
//
//    public function test_getAttributeIterator()
//    {
//        $result = $this->getResultMock();
//        $entry = new ResultEntry('ldap entry', $result);
//
//        $result->getLink()
//               ->expects($this->once())
//               ->method('first_attribute')
//               ->with($this->identicalTo($entry))
//               ->willReturn('first attribute');
//
//        $iterator = $entry->getAttributeIterator();
//        $this->assertInstanceOf(ResultAttributeIterator::class, $iterator);
//
//        $this->assertSame($entry, $iterator->getEntry());
//        $this->assertEquals('first attribute', $iterator->key());
//
//        $result->getLink()
//               ->method('next_attribute')
//               ->with($this->identicalTo($entry))
//               ->willReturn('second attribute');
//
//        $iterator->next();
//
//        // single iterator instance per ResultEntry (dictated by ext-ldap implementation)
//        $iterator2 = $entry->getAttributeIterator();
//        $this->assertSame($iterator, $iterator2);
//        $this->assertEquals('second attribute', $iterator->key());
//    }
}

// vim: syntax=php sw=4 ts=4 et:
