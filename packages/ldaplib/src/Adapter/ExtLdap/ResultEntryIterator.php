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
use Korowai\Lib\Ldap\Adapter\ResultEntryInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ResultEntryIterator extends AbstractResultIterator implements ResultEntryIteratorInterface
{
    /**
     * Constructs ResultEntryIterator
     *
     * @param LdapResultInterface $ldapResult
     *      The ldap search result which provides first entry in the chain.
     * @param LdapResultEntryInterface|null $entry
     *      The entry currently pointed to by the iterator (``null`` to
     *      create an invalid/past the end iterator).
     * @param int $offset
     *      The offset of the $entry in the chain.
     */
    public function __construct(
        LdapResultInterface $ldapResult,
        LdapResultEntryInterface $entry = null,
        int $offset = null
    ) {
        parent::__construct($ldapResult, $entry, $offset);
    }

    /**
     * {@inheritdoc}
     */
    protected function first_item() : ?LdapResultEntryInterface
    {
        return $this->getLdapResult()->first_entry() ?: null;
    }

    /**
     * {@inheritdoc}
     */
    protected function next_item() : ?LdapResultEntryInterface
    {
        return $this->getCurent()->next_entry() ?: null;
    }

    /**
     * {@inheritdoc}
     */
    protected function wrap(LdapResultItemInterface $item) : ResultEntryInterface
    {
        return new ResultEntry($item);
    }
}

// vim: syntax=php sw=4 ts=4 et:
