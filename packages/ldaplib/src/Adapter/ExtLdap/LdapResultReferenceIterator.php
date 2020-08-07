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

use Korowai\Lib\Ldap\Adapter\ResultReferenceIteratorInterface;
use Korowai\Lib\Ldap\Adapter\ResultReferenceInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapResultReferenceIterator extends AbstractLdapResultItemIterator implements
    LdapResultReferenceIteratorInterface
{
    /**
     * Initializes the iterator
     *
     * @param  LdapResultInterface $ldapResult
     *      The ldap search result which provides first reference in the chain.
     * @param  LdapResultReferenceInterface $current
     *      The reference currently pointed to by iterator (``null`` to create an
     *      invalid/past the end iterator).
     * @param  int $offset
     *      The offset of the $current reference in the chain.
     */
    public function __construct(
        LdapResultInterface $ldapResult,
        LdapResultReferenceInterface $current = null,
        int $offset = null
    ) {
        parent::__construct($ldapResult, $current, $offset);
    }

    /**
     * @return LdapResultReferenceInterface|null
     *
     * @psalm-mutation-free
     * @psalm-suppress MoreSpecificReturnType
     */
    public function current() : ?LdapResultReferenceInterface
    {
        /** @var LdapResultReferenceInterface|null */
        return $this->current;
    }

    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

    /**
     * Returns first reference from the result.
     *
     * @return LdapResultReferenceInterface|false
     *
     * @psalm-mutation-free
     */
    protected function first_item()
    {
        return $this->getLdapResult()->first_reference();
    }

    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd
}

// vim: syntax=php sw=4 ts=4 et:
