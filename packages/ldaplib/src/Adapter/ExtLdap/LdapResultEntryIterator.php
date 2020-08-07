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
final class LdapResultEntryIterator extends AbstractLdapResultItemIterator implements
    LdapResultEntryIteratorInterface
{
    /**
     * Initializes the iterator
     *
     * @param  LdapResultInterface $ldapResult
     *      The ldap search result which provides first entry in the chain.
     * @param  LdapResultEntryInterface $current
     *      The entry currently pointed to by iterator (``null`` to create an
     *      invalid/past the end iterator).
     * @param  int $offset
     *      The offset of the $current entry in the chain.
     */
    public function __construct(
        LdapResultInterface $ldapResult,
        LdapResultEntryInterface $current = null,
        int $offset = null
    ) {
        parent::__construct($ldapResult, $current, $offset);
    }

    /**
     * @return LdapResultEntryInterface|null
     *
     * @psalm-mutation-free
     * @psalm-suppress MoreSpecificReturnType
     */
    public function current() : ?LdapResultEntryInterface
    {
        /** @var LdapResultEntryInterface|null */
        return $this->current;
    }

    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

    /**
     * Returns first entry from the result.
     *
     * @return LdapResultEntryInterface|false
     *
     * @psalm-mutation-free
     */
    protected function first_item()
    {
        return $this->getLdapResult()->first_entry();
    }

    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd
}

// vim: syntax=php sw=4 ts=4 et:
