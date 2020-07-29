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
final class ResultReferenceIterator extends AbstractResultIterator implements ResultReferenceIteratorInterface
{
    /**
     * Constructs ResultReferenceIterator
     *
     * @param LdapResultInterface $ldapResult
     *      The ldap search result which provides first reference in the chain.
     * @param LdapResultReferenceInterface|null $reference
     *      The reference currently pointed to by the iterator (``null`` to
     *      create an invalid/past the end iterator).
     * @param int $offset
     *      The offset of the $reference in the chain.
     */
    public function __construct(
        LdapResultInterface $ldapResult,
        LdapResultReferenceInterface $reference = null,
        int $offset = null
    ) {
        parent::__construct($ldapResult, $reference, $offset);
    }

    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

    /**
     * {@inheritdoc}
     */
    protected function first_item() : ?LdapResultReferenceInterface
    {
        return $this->getLdapResult()->first_reference() ?: null;
    }

    /**
     * {@inheritdoc}
     */
    protected function next_item(LdapResultItemInterface $current) : ?LdapResultEntryInterface
    {
        return $current->next_reference() ?: null;
    }

    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd

    /**
     * {@inheritdoc}
     */
    protected function wrap(LdapResultItemInterface $item) : ResultReference
    {
        return new ResultReference($item);
    }
}

// vim: syntax=php sw=4 ts=4 et:
