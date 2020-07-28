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
     * Return the key of the current element, that is DN of the current entry
     *
     * @return string
     */
    public function key()
    {
        // FIXME: DN's may be non-unique in result, while keys should be unique
        return $this->current()->getDn();
    }

    protected function first_item()
    {
        return $this->getLdapResult()->first_entry();
    }

    protected function next_item()
    {
        return $this->getCurent()->next_entry();
    }

    protected function wrap(LdapResultItemInterface $item)
    {
        return new ResultEntry($item);
    }
}

// vim: syntax=php sw=4 ts=4 et:
