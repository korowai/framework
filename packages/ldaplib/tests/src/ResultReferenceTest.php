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

use Korowai\Lib\Ldap\Core\LdapResultReferenceWrapperInterface;
use Korowai\Lib\Ldap\Core\LdapResultReferenceWrapperTrait;
use Korowai\Lib\Ldap\ResultReference;
use Korowai\Lib\Ldap\ResultReferenceInterface;
use Korowai\Lib\Ldap\ResultReferralIterator;
use Korowai\Testing\Ldaplib\CreateLdapLinkMockTrait;
use Korowai\Testing\Ldaplib\CreateLdapResultMockTrait;
use Korowai\Testing\Ldaplib\CreateLdapResultReferenceMockTrait;
use Korowai\Testing\Ldaplib\ExamineLdapLinkErrorHandlerTrait;
use Korowai\Testing\Ldaplib\GetLdapFunctionMockTrait;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestFixture;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestSubject;
use Korowai\Testing\Ldaplib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\ResultReference
 *
 * @internal
 */
final class ResultReferenceTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;
    use GetLdapFunctionMockTrait;
    use CreateLdapLinkMockTrait;
    use CreateLdapResultMockTrait;
    use CreateLdapResultReferenceMockTrait;
    use ExamineLdapLinkErrorHandlerTrait;

    //
    //
    // TESTS
    //
    //

    public function testImplementsResultReferenceInterface(): void
    {
        $this->assertImplementsInterface(ResultReferenceInterface::class, ResultReference::class);
    }

    public function testImplementsLdapResultReferenceWraperInterface(): void
    {
        $this->assertImplementsInterface(LdapResultReferenceWrapperInterface::class, ResultReference::class);
    }

    public function testUsesLdapResultReferenceWrapperTrait(): void
    {
        $this->assertUsesTrait(LdapResultReferenceWrapperTrait::class, ResultReference::class);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getLdapResultReference()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function testGetLdapResultReference(): void
    {
        [$reference, $ldapReference] = $this->createResultReferenceAndMocks(1);
        $this->assertSame($ldapReference, $reference->getLdapResultReference());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getReferrals()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__getReferrals(): array
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
     *
     * @param mixed $return
     * @param mixed $output
     * @param mixed $expect
     */
    public function testGetReferrals($return, $output, $expect): void
    {
        [$reference, $ldapReference] = $this->createResultReferenceAndMocks(1);

        $ldapReference->expects($this->once())
            ->method('parse_reference')
            ->will($this->returnCallback(function (&$referrals) use ($return, $output) {
                          $referrals = $output;

                          return $return;
                      }))
        ;

        $this->assertSame($expect, $reference->getReferrals());
    }

    public static function prov__getReferrals__withTriggerError(): array
    {
        return static::feedLdapLinkErrorHandler();
    }

    /**
     * @dataProvider prov__getReferrals__withTriggerError
     */
    public function testGetReferralsWithTriggerError(LdapTriggerErrorTestFixture $fixture): void
    {
        $this->examineMethodWithTriggerError('getReferrals', 'parse_reference', [&$referrals], $fixture);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getReferralIterator(), getIterator()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function prov__getReferralIterator(): array
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
     *
     * @param mixed $return
     * @param mixed $output
     * @param mixed $expect
     */
    public function testGetReferralIterator($return, $output, $expect): void
    {
        [$reference, $ldapReference] = $this->createResultReferenceAndMocks(1);

        $ldapReference->expects($this->once())
            ->method('parse_reference')
            ->will($this->returnCallback(function (&$referrals) use ($return, $output) {
                          $referrals = $output;

                          return $return;
                      }))
        ;

        $iterator = $reference->getReferralIterator();
        $this->assertInstanceOf(ResultReferralIterator::class, $iterator);

        $this->assertSame($iterator, $reference->getReferralIterator());
        $this->assertSame($iterator, $reference->getIterator());

        $this->assertSame($expect, $iterator->getArrayCopy());
    }

    public static function prov__getReferralIterator__withTriggerError(): array
    {
        return static::feedLdapLinkErrorHandler();
    }

    /**
     * @dataProvider prov__getReferralIterator__withTriggerError
     */
    public function testGetReferralIteratorWithTriggerError(LdapTriggerErrorTestFixture $fixture): void
    {
        $this->examineMethodWithTriggerError('getReferralIterator', 'parse_reference', [&$referrals], $fixture);
    }

    private function createResultReferenceAndMocks(int $mocksDepth = 3): array
    {
        $link = $mocksDepth >= 3 ? $this->createLdapLinkMock() : null;
        $ldapResult = $mocksDepth >= 2 ? $this->createLdapResultMock($link) : null;
        $ldapReference = $this->createLdapResultReferenceMock($ldapResult);
        $resultReference = new ResultReference($ldapReference);

        return array_slice([$resultReference, $ldapReference, $ldapResult, $link], 0, max(2, 1 + $mocksDepth));
    }

    private function examineMethodWithTriggerError(
        string $method,
        string $backendMethod,
        array $args,
        LdapTriggerErrorTestFixture $fixture
    ): void {
        [$reference, $ldapReference, $ldapResult, $link] = $this->createResultReferenceAndMocks();

        $function = function () use ($reference, $method, $args): void {
            $reference->{$method}(...$args);
        };

        $subject = new LdapTriggerErrorTestSubject($ldapReference, $backendMethod);
        $this->examineLdapLinkErrorHandler($function, $subject, $link, $fixture);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
