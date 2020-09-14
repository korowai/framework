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

use Korowai\Lib\Ldap\ResultReferralIterator;
use Korowai\Lib\Ldap\ResultReferralIteratorInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\ResultReferralIterator
 */
final class ResultReferralIteratorTest extends TestCase
{
    public function test__implements__ResultReferralIteratorInterface()
    {
        $this->assertImplementsInterface(ResultReferralIteratorInterface::class, ResultReferralIterator::class);
    }

    public function test__extends__ArrayIterator()
    {
        $this->assertExtendsClass(\ArrayIterator::class, ResultReferralIterator::class);
    }

    public function test__construct()
    {
        $iterator = new ResultReferralIterator(['foo', 'bar']);
        $this->assertSame(['foo', 'bar'], $iterator->getArrayCopy());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
