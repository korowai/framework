<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter\ExtLdap;

use Korowai\Lib\Ldap\Adapter\ResultItemIteratorInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class AbstractResultItemIterator implements ResultItemIteratorInterface
{
    /**
     * @var LdapResultItemIteratorInterface
     *
     * @psalm-readonly
     */
    protected $ldapIterator;

    /**
     * Constructs ResultEntryIterator
     *
     * @param LdapResultItemIteratorInterface $ldapIterator
     */
    public function __construct(LdapResultItemIteratorInterface $ldapIterator)
    {
        $this->ldapIterator = $ldapIterator;
    }

    /**
     * Returns the encapsulated LdapResultItemIteratorInterface
     *
     * @return LdapResultItemIteratorInterface
     *
     * @psalm-mutation-free
     * @psalm-pure
     */
    public function getLdapResultItemIterator() : LdapResultItemIteratorInterface
    {
        return $this->ldapIterator;
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-mutation-free
     */
    final public function key() : ?int
    {
        return $this->ldapIterator->key();
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-mutation-free
     */
    final public function valid() : bool
    {
        return $this->ldapIterator->valid();
    }

    /**
     * {@inheritdoc}
     */
    final public function next() : void
    {
        $this->ldapIterator->next();
    }

    /**
     * {@inheritdoc}
     */
    final public function rewind() : void
    {
        $this->ldapIterator->rewind();
    }
}

// vim: syntax=php sw=4 ts=4 et:
