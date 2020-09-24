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

use Korowai\Lib\Ldap\ResultReferenceInterface;
use Korowai\Lib\Ldap\ResultReferralIteratorInterface;
use Korowai\Testing\LdaplibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldap\ResultReferenceInterfaceTrait
 *
 * @internal
 */
final class ResultReferenceInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class() implements ResultReferenceInterface {
            use ResultReferenceInterfaceTrait;
        };
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ResultReferenceInterface::class, $dummy);
    }

    public function testGetReferrals(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->referrals = [];
        $this->assertSame($dummy->referrals, $dummy->getReferrals());
    }

    public function testGetReferralsWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->referrals = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->getReferrals();
    }

    public function testGetReferralIterator(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->referralIterator = $this->createStub(ResultReferralIteratorInterface::class);
        $this->assertSame($dummy->referralIterator, $dummy->getReferralIterator());
    }

    public function testGetReferralIteratorWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->referralIterator = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('ResultReferralIteratorInterface');
        $dummy->getReferralIterator();
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
