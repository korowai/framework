<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap;

use Korowai\Testing\Ldaplib\TestCase;
use Korowai\Testing\Ldaplib\CreateLdapLinkMockTrait;
use Korowai\Testing\Ldaplib\CreateLdapResultMockTrait;
use Korowai\Testing\Ldaplib\CreateLdapResultReferenceMockTrait;
use Korowai\Testing\Ldaplib\ExamineCallWithLdapTriggerErrorTrait;
use Korowai\Testing\Ldaplib\GetLdapFunctionMockTrait;

use Korowai\Lib\Ldap\ResultReference;
use Korowai\Lib\Ldap\ResultReferenceInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultReferenceWrapperInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultReferenceWrapperTrait;
use Korowai\Lib\Ldap\ResultReferralIterator;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers Korowai\Lib\Ldap\ResultReference
 */
final class ResultReferenceTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;
    use GetLdapFunctionMockTrait;
    use CreateLdapLinkMockTrait;
    use CreateLdapResultMockTrait;
    use CreateLdapResultReferenceMockTrait;
    use ExamineCallWithLdapTriggerErrorTrait;

    private function examineMethodWithTriggerError(
        string $method,
        string $backendMethod,
        array $args,
        array $config,
        array $expect
    ) : void {
        $ldap = $this->createLdapLinkMock('ldap link', ['isValid', 'errno']);
        $ldapResult = $this->createLdapResultMock($ldap);
        $ldapReference = $this->createLdapResultReferenceMock($ldapResult, 'ldap result reference', [$backendMethod]);
        $reference = new ResultReference($ldapReference);

        $this->examineCallWithLdapTriggerError(
            function () use ($reference, $method, $args) : void {
                $reference->$method(...$args);
            },
            $ldapReference,
            $backendMethod,
            $args,
            $ldap,
            $config,
            $expect
        );
    }

    //
    //
    // TESTS
    //
    //

    public function test__implements__ResultReferenceInterface()
    {
        $this->assertImplementsInterface(ResultReferenceInterface::class, ResultReference::class);
    }

    public function test__implements__LdapResultReferenceWraperInterface()
    {
        $this->assertImplementsInterface(LdapResultReferenceWrapperInterface::class, ResultReference::class);
    }

    public function test__uses__LdapResultReferenceWrapperTrait() : void
    {
        $this->assertUsesTrait(LdapResultReferenceWrapperTrait::class, ResultReference::class);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getLdapResultReference()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function test_getLdapResultReference() : void
    {
        $ldapReference = $this->createLdapResultReferenceMock(null, null);
        $reference = new ResultReference($ldapReference);
        $this->assertSame($ldapReference, $reference->getLdapResultReference());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getReferrals()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__getReferrals() : array
    {
        return [
            // #0
            [
                'return' => false,
                'output' => null,
                'expect' => [],
            ],
            // #1
            [
                'return' => true,
                'output' => [],
                'expect' => [],
            ],
            // #2
            [
                'return' => true,
                'output' => ['ldap://example.org/dc=subtree,dc=example,dc=org??sub'],
                'expect' => ['ldap://example.org/dc=subtree,dc=example,dc=org??sub'],
            ],
        ];
    }

    /**
     * @dataProvider prov__getReferrals
     */
    public function test__getReferrals($return, $output, $expect) : void
    {
        $ldapLink = $this->createLdapLinkMock();
        $ldapResult = $this->createLdapResultMock($ldapLink);
        $ldapResultReference = $this->createLdapResultReferenceMock($ldapResult, 'ldap result entry', ['parse_reference']);
        $reference = new ResultReference($ldapResultReference);

        $ldapResultReference->expects($this->once())
                            ->method('parse_reference')
                            ->with()
                            ->will($this->returnCallback(function (&$referrals) use ($return, $output) {
                                $referrals = $output;
                                return $return;
                            }));

        $this->assertSame($expect, $reference->getReferrals());
    }

    public static function prov__getReferrals__withTriggerError() : array
    {
        return static::feedCallWithLdapTriggerError();
    }

    /**
     * @dataProvider prov__getReferrals__withTriggerError
     */
    public function test__getReferrals__withTriggerError(array $config, array $expect) : void
    {
        $this->examineMethodWithTriggerError('getReferrals', 'parse_reference', [&$referrals], $config, $expect);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getReferralIterator(), getIterator()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__getReferralIterator() : array
    {
        return [
            // #0
            [
                'return' => false,
                'output' => null,
                'expect' => [],
            ],
            // #1
            [
                'return' => true,
                'output' => [],
                'expect' => [],
            ],
            // #2
            [
                'return' => true,
                'output' => ['ldap://example.org/dc=subtree,dc=example,dc=org??sub'],
                'expect' => ['ldap://example.org/dc=subtree,dc=example,dc=org??sub'],
            ],
        ];
    }

    /**
     * @dataProvider prov__getReferralIterator
     */
    public function test__getReferralIterator($return, $output, $expect)
    {
        $ldapLink = $this->createLdapLinkMock();
        $ldapResult = $this->createLdapResultMock($ldapLink);
        $ldapResultReference = $this->createLdapResultReferenceMock($ldapResult, 'ldap result entry', ['parse_reference']);
        $reference = new ResultReference($ldapResultReference);

        $ldapResultReference->expects($this->once())
                            ->method('parse_reference')
                            ->with()
                            ->will($this->returnCallback(function (&$referrals) use ($return, $output) {
                                $referrals = $output;
                                return $return;
                            }));

        $iterator = $reference->getReferralIterator();
        $this->assertInstanceOf(ResultReferralIterator::class, $iterator);

        $this->assertSame($iterator, $reference->getReferralIterator());
        $this->assertSame($iterator, $reference->getIterator());

        $this->assertSame($expect, $iterator->getArrayCopy());
    }

    public static function prov__getReferralIterator__withTriggerError() : array
    {
        return static::feedCallWithLdapTriggerError();
    }

    /**
     * @dataProvider prov__getReferralIterator__withTriggerError
     */
    public function test__getReferralIterator__withTriggerError(array $config, array $expect) : void
    {
        $this->examineMethodWithTriggerError('getReferralIterator', 'parse_reference', [&$referrals], $config, $expect);
    }
}

// vim: syntax=php sw=4 ts=4 et:
