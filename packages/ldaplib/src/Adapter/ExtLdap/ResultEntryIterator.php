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

use Korowai\Lib\Ldap\Adapter\ResultEntryIteratorInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ResultEntryIterator extends AbstractResultIterator implements ResultEntryIteratorInterface
{
    /** @var ResultEntry|null */
    private $entry;

    /**
     * Constructs ResultEntryIterator
     *
     * @param LdapResultInterface $ldapResult
     *      The ldap search result which provides first entry in the entry
     *      chain
     * @param LdapResultEntryInterface|null $current
     *      The current entry in the chain or ``null`` to create an invalid
     *      (past the end) iterator
     *
     * The ``$result`` object is used by ``rewind()`` method.
     */
    public function __construct(LdapResultInterface $ldapResult, ?LdapResultEntryInterface $current)
    {
        parent::__construct($ldapResult, $current);
    }

    /**
     * @todo Write documentation.
     */
    public function getEntry()
    {
        return $this->current();
    }

    protected function getMethodForFirst()
    {
        return 'first_entry';
    }

    protected function getMethodForNext()
    {
        return 'next_entry';
    }

    protected function wrapElement($unwrapped)
    {
        return new ResultEntry($unwrapped);
    }

    protected function unwrapElement($wrapped)
    {
        return $wrapped->getLdapResultEntry();
    }
}

// vim: syntax=php sw=4 ts=4 et:
