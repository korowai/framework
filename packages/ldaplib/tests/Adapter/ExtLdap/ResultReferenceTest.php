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

use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultReference;
use Korowai\Lib\Ldap\Adapter\ResultReferenceInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultReferenceWrapperInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultReferenceWrapperTrait;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultReferralIterator;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultReferenceTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;
    use GetLdapFunctionMock;
    use CreateLdapLinkMock;
    use CreateLdapResultMock;
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
        $ldapResult = $this->createLdapResultMock($ldap);
        $ldapReference = $this->createLdapResultReferenceMock($ldapResult, 'ldap result reference', [$backendMethod]);
        $reference = new ResultReference($ldapReference);

        $this->examineMethodWithBackendTriggerError(
            $reference,
            $method,
            $ldapReference,
            $backendMethod,
            $ldap,
            $args,
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
    // getDn()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__getDn() : array
    {
        return [
            // #0
            [
                'return' => 'dc=example,dc=org',
                'expect' => 'dc=example,dc=org',
            ],
            // #1
            [
                'return' => false,
                'expect' => '',
            ],
        ];
    }

    /**
     * @dataProvider prov__getDn
     */
    public function test__getDn($return, $expect) : void
    {
        $ldapLink = $this->createLdapLinkMock();
        $ldapResult = $this->createLdapResultMock($ldapLink);
        $ldapResultReference = $this->createLdapResultReferenceMock($ldapResult, 'ldap result reference', ['get_dn']);
        $reference = new ResultReference($ldapResultReference);

        $ldapResultReference->expects($this->once())
                        ->method('get_dn')
                        ->with()
                        ->willReturn($return);

        $this->assertSame($expect, $reference->getDn());
    }

    public static function prov__getDn__withTriggerError() : array
    {
        return static::feedMethodWithBackendTriggerError();
    }

    /**
     * @dataProvider prov__getDn__withTriggerError
     */
    public function test__getDn__withTriggerError(array $config, array $expect) : void
    {
        $this->examineMethodWithTriggerError('getDn', 'get_dn', [], $config, $expect);
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
        return static::feedMethodWithBackendTriggerError();
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
        return static::feedMethodWithBackendTriggerError();
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
