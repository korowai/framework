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

use Korowai\Lib\Ldap\Adapter\ExtLdap\Result;
use Korowai\Lib\Ldap\Adapter\ResultInterface;
use Korowai\Lib\Ldap\Adapter\AbstractResult;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultEntry;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultReference;
//use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
//use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultEntryIterator;
//use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultReferenceIterator;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultWrapperInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultWrapperTrait;
//use Korowai\Lib\Ldap\Adapter\ResultInterface;
use PHPUnit\Framework\Constraint\Constraint;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ResultTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;
    use GetLdapFunctionMock;
    use CreateLdapLinkMock;
    use CreateLdapResultMock;
    use CreateLdapResultEntryMock;
    use CreateLdapResultReferenceMock;
    use ExamineMethodWithBackendTriggerError;

    private function examineMethodWithTriggerError(
        string $method,
        string $backendMethod,
        array $args,
        array $config,
        array $expect
    ) : void {
        $ldap = $this->createLdapLinkMock('ldap link', ['isValid', 'errno']);
        $ldapResult = $this->createLdapResultMock($ldap, 'ldap result', [$backendMethod]);
        $result = new Result($ldapResult);

        $this->examineMethodWithBackendTriggerError(
            $result,
            $method,
            $ldapResult,
            $backendMethod,
            $ldap,
            $args,
            $config,
            $expect
        );
    }

//    private function mockResourceFunctions($arg, $return) : void
//    {
//        if ($return !== null) {
//            $this->getLdapFunctionMock('is_resource')
//                 ->expects($this->any())
//                 ->with($this->identicalTo($arg))
//                 ->willReturn((bool)$return);
//            if ($return) {
//                $this->getLdapFunctionMock('get_resource_type')
//                     ->expects($this->any())
//                     ->with($this->identicalTo($arg))
//                     ->willReturn(is_string($return) ? $return : 'unknown');
//            }
//        }
//    }
//
//    private function createLdapLinkMock($resource = 'ldap link')
//    {
//        $builder = $this->getMockBuilder(LdapLinkInterface::class);
//        if ($resource !== null) {
//            $builder->setMethods(['getResource']);
//        }
//
//        $mock = $builder->getMockForAbstractClass();
//
//        if ($resource !== null) {
//            $mock->expects($this->any())
//                 ->method('getResource')
//                 ->with()
//                 ->willReturn($resource);
//        }
//
//        return $mock;
//    }
//
//    private function createLdapResult(LdapLinkInterface $link = null, $resource = 'ldap result')
//    {
//        return new Result($resource, $link);
//    }
//
//    private function makeArgsForLdapMock(array $args, Result $result = null, LdapLinkInterface $ldap = null) : array
//    {
//        $resources = [];
//        if ($result !== null) {
//            $resources[] = $result->getResource();
//        }
//        if ($ldap !== null) {
//            $resources[] = $ldap->getResource();
//        }
//        return array_map([$this, 'identicalTo'], array_merge($resources, $args));
//    }
//
//    private function examineFuncWithMockedBackend(string $func, array $args, $return, $expect) : void
//    {
//        $ldap = $this->createLdapLinkMock();
//        $result = $this->createLdapResult($ldap);
//
//        $ldapArgs = $this->makeArgsForLdapMock($args, $result, $ldap);
//
//        $this   ->getLdapFunctionMock("ldap_$func")
//                ->expects($this->once())
//                ->with(...$ldapArgs)
//                ->willReturn($return);
//
//        $this->assertSame($expect, call_user_func_array([$result, $func], $args));
//    }
//

    //
    //
    // TEST
    //
    //

    public function test__extends__AbstractResult()
    {
        $this->assertExtendsClass(AbstractResult::class, Result::class);
    }

    public function test__implements__LdapResultWrapperInterface()
    {
        $this->assertImplementsInterface(LdapResultWrapperInterface::class, Result::class);
    }

    public function test__uses__LdapResultWrapperTrait()
    {
        $this->assertUsesTrait(LdapResultWrapperTrait::class, Result::class);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getLdapResult()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function test__getLdapResult()
    {
        $ldapResult = $this->createLdapResultMock(null);
        $result = new Result($ldapResult);
        $this->assertSame($ldapResult, $result->getLdapResult());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getResultEntries()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__getResultEntries()
    {
        return [
            // #0
            [
                'entries' => [],
            ],

            // #1
            [
                'entries' => ['first'],
            ],

            // #2
            [
                'entries' => ['first', 'second'],
            ],

            // #3
            [
                'entries' => ['first', 'second', 'third'],
            ],
        ];
    }

    /**
     * @dataProvider prov__getResultEntries
     */
    public function test__getResultEntries(array $ldapEntries = [])
    {
        $ldapLink = $this->createLdapLinkMock();
        $ldapResult = $this->createLdapResultMock($ldapLink, 'ldap result', ['first_entry']);
        $result = new Result($ldapResult);

        $ldapEntries = array_map(function ($ldapEntry) use ($ldapResult) {
            return $this->createLdapResultEntryMock($ldapResult, $ldapEntry, ['next_entry']);
        }, $ldapEntries);

        $first = $ldapEntries[0] ?? false;
        $ldapResult->expects($this->exactly(2))
                   ->method('first_entry')
                   ->with()
                   ->willReturn($first);

        for ($i = 0; $i < count($ldapEntries); ++$i) {
            $curr = $ldapEntries[$i];
            $next = $ldapEntries[$i+1] ?? false;
            $curr->expects($this->once())
                 ->method('next_entry')
                 ->with()
                 ->willReturn($next);
        }

        $constraints = array_map(function ($ldapEntry) {
            return $this->logicalAnd(
                $this->isInstanceOf(ResultEntry::class),
                $this->hasPropertiesIdenticalTo(['getLdapResultEntry()' => $ldapEntry]),
            );
        }, $ldapEntries);

        $entries = $result->getResultEntries();

        $this->assertCount(count($ldapEntries), $entries);

        for ($i = 0; $i < count($entries); ++$i) {
            $entry = $entries[$i];
            $expect = $constraints[$i];
            $this->assertThat($entry, $expect);
        }
    }

    public static function prov__getResultEntries__withTriggerError()
    {
        return static::feedMethodWithBackendTriggerError();
    }

    /**
     * @dataProvider prov__getResultEntries__withTriggerError
     */
    public function test__getResultEntries__withTriggerError(array $config, array $expect)
    {
        $this->examineMethodWithTriggerError('getResultEntries', 'first_entry', [], $config, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getResultReferences()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getResultEntryIterator()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getResultReferenceIterator()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getEntries()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getIteraator()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

}

// vim: syntax=php sw=4 ts=4 et:
