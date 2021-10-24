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

use Korowai\Lib\Ldap\Core\LdapResultReferenceInterface;
use Korowai\Lib\Ldap\Core\LdapResultReferenceIteratorInterface;
use Korowai\Lib\Ldap\ResultReferenceInterface;
use Korowai\Lib\Ldap\ResultReferenceIterator;
use Korowai\Lib\Ldap\ResultReferenceIteratorInterface;
use Korowai\Testing\Ldaplib\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\ResultReferenceIterator
 * @covers \Korowai\Tests\Lib\Ldap\ResultItemIteratorTestTrait
 *
 * @internal
 */
final class ResultReferenceIteratorTest extends TestCase
{
    use ResultItemIteratorTestTrait;
    use ImplementsInterfaceTrait;
    use ExtendsClassTrait;

    public function getIteratorClass(): string
    {
        return ResultReferenceIterator::class;
    }

    public function getIteratorInterface(): string
    {
        return ResultReferenceIteratorInterface::class;
    }

    public function getLdapIteratorInterface(): string
    {
        return LdapResultReferenceIteratorInterface::class;
    }

    public function createIteratorInstance($ldapIterator)
    {
        return new ResultReferenceIterator($ldapIterator);
    }

    public function getItemInterface(): string
    {
        return ResultReferenceInterface::class;
    }

    public function getLdapItemInterface(): string
    {
        return LdapResultReferenceInterface::class;
    }
}

// vim: syntax=php sw=4 ts=4 et:
