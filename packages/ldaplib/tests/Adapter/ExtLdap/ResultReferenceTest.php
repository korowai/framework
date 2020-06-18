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

use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultReference;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultReferralIterator;
use Korowai\Lib\Ldap\Adapter\ExtLdap\Result;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ExtLdapResultInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ExtLdapResultReferenceInterface;
use Korowai\Lib\Ldap\Adapter\ResultReferenceInterface;
use Korowai\Lib\Ldap\Adapter\ReferralsIterationInterface;
use Korowai\Lib\Ldap\Exception\LdapException;

// because we use process isolation heavily, we can't use native PHP closures
// (they're not serializable)
use Korowai\Tests\Lib\Ldap\Adapter\ExtLdap\Closures\LdapParseReferenceClosure;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultReferenceTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    private function getLdapFunctionMock(...$args)
    {
        return $this->getFunctionMock('\Korowai\Lib\Ldap\Adapter\ExtLdap', ...$args);
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

    private function createLdapResultMock(LdapLinkInterface $ldap = null, $resource = 'ldap result') : ExtLdapResultInterface
    {
        $builder = $this->getMockBuilder(ExtLdapResultInterface::class);
        $methods = [];

        if ($ldap !== null) {
            $methods[] = 'getLdapLink';
        }

        if ($resource !== null) {
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

    private function createLdapResultReference(ExtLdapResultInterface $result = null, $resource = 'ldap result reference') : ResultReference
    {
        return new ResultReference($resource, $result);
    }

    public function test__implements__ResultReferenceInterface()
    {
        $this->assertImplementsInterface(ResultReferenceInterface::class, ResultReference::class);
    }

    public function test__implements__ExtLdapResultReferenceInterface()
    {
        $this->assertImplementsInterface(ExtLdapResultReferenceInterface::class, ResultReference::class);
    }

    public function test__implements__ResultReferralArrayIterationInterface()
    {
        $this->assertImplementsInterface(ReferralsIterationInterface::class, ResultReference::class);
    }

    public function test__getResource()
    {
        $result = $this->createLdapResultMock();
        $ref = new ResultReference('ldap reference', $result);
        $this->assertSame('ldap reference', $ref->getResource());
    }

    public function test__getResult()
    {
        $result = $this->createLdapResultMock(null, null);
        $ref = new ResultReference('ldap reference', $result);
        $this->assertSame($result, $ref->getResult());
    }

    public function test__getReferralIterator()
    {
        $result = $this->createLdapResultMock(null, null);
        $ref = new ResultReference('ldap reference', $result);

        $iterator1 = $ref->getReferralIterator();
        $this->assertInstanceOf(ResultReferralIterator::class, $iterator1);

        $iterator2 = $ref->getReferralIterator();
        $this->assertSame($iterator1, $iterator2);
    }

    public static function prov__next_reference()
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
     * @dataProvider prov__next_reference
     */
    public function test__next_reference(array $args, $return, $expect)
    {
        $ldap = $this->createLdapLinkMock();
        $result = $this->createLdapResultMock($ldap);
        $reference = $this->createLdapResultReference($result);

        $ldapArgs = array_map(
            [$this, 'identicalTo'],
            array_merge([$ldap->getResource(), $reference->getResource()], $args)
        );

        $this   ->getLdapFunctionMock("ldap_next_reference")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturn($return);

        $next = $reference->next_reference(...$args);
        if ($return) {
            $this->assertInstanceOf(ResultReference::class, $next);
            $this->assertSame($result, $next->getResult());
            $this->assertHasPropertiesSameAs($expect, $next);
        } else {
            $this->assertSame($expect, $next);
        }
    }

    public function prov__parse_reference()
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
     * @dataProvider prov__parse_reference
     */
    public function test__parse_reference(array $args, $return, $expect, array $values)
    {
        $ldap = $this->createLdapLinkMock();
        $result = $this->createLdapResultMock($ldap);
        $reference = $this->createLdapResultReference($result);

        $ldapArgs = array_map(
            [$this, 'identicalTo'],
            array_merge([$ldap->getResource(), $reference->getResource()], $args)
        );

        $this   ->getLdapFunctionMock("ldap_parse_reference")
                ->expects($this->once())
                ->with(...$ldapArgs)
                ->willReturnCallback(new LdapParseReferenceClosure($return, $values));

        $this->assertSame($expect, $reference->parse_reference(...$args));
        if (count($args) > 1) {
            $this->assertSame($values[0] ?? null, $args[1]);
        }
    }

//    /**
//     * @runInSeparateProcess
//     * @dataProvider prov__parse_reference
//     */
//    public function test__getReferrals__Failure()
//    {
//        $ldap = $this->createLdapLinkMock();
//        $result = $this->createLdapResultMock($ldap);
//
//        $ref = new ResultReference('ldap reference', $result);
//
//        $ldap->expects($this->once())
//             ->method('parse_reference')
//             ->with($this->identicalTo($ref), $this->anything())
//             ->willReturn(false);
//        $ldap->expects($this->once())
//             ->method('errno')
//             ->willReturn(0x54);
//
//        $this->expectException(LdapException::class);
//        $this->expectExceptionCode(0x54);
//
//        $ref->getReferrals();
//    }
//
//    public function test__getReferrals__Success()
//    {
//        $ldap = $this->createLdapLinkMock();
//        $result = $this->createLdapResultMock($ldap);
//
//        $ref = new ResultReference('ldap reference', $result);
//
//        $callback = function ($ref, &$referrals) {
//            $referrals = ['A'];
//            return true;
//        };
//
//        $ldap->expects($this->once())
//             ->method('parse_reference')
//             ->with($this->identicalTo($ref), $this->anything())
//             ->will($this->returnCallback($callback));
//
//        $this->assertSame(['A'], $ref->getReferrals());
//    }
//
//    public function test__referrals__iteration()
//    {
//        $ldap = $this->createLdapLinkMock();
//        $result = $this->createLdapResultMock($ldap);
//
//        $ref = new ResultReference('ldap reference', $result);
//
//        $callback = function ($ref, &$referrals) {
//            $referrals = ['A', 'B'];
//            return true;
//        };
//
//        $ldap->expects($this->once())
//             ->method('parse_reference')
//             ->with($this->identicalTo($ref), $this->anything())
//             ->will($this->returnCallback($callback));
//
//        $this->assertSame(0, $ref->referrals_key());
//        $this->assertSame('A', $ref->referrals_current());
//
//        $this->assertSame('B', $ref->referrals_next());
//
//        $this->assertSame(1, $ref->referrals_key());
//        $this->assertSame('B', $ref->referrals_current());
//
//        $this->assertFalse($ref->referrals_next());
//        $this->assertNull($ref->referrals_key());
//        $this->assertFalse($ref->referrals_current());
//
//        $ref->referrals_reset();
//
//        $this->assertSame(0, $ref->referrals_key());
//        $this->assertSame('A', $ref->referrals_current());
//    }
}

// vim: syntax=php sw=4 ts=4 et:
