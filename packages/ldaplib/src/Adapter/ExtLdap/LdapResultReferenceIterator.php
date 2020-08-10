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
     * @param  LdapResultReferenceInterface|null $first
     *      The first result reference in the list (``null`` to create an
     *      iterator over empty sequence which is always invalid).
     * @param  LdapResultReferenceInterface|null $current
     *      The item currently pointed to by iterator (``null`` to create an
     *      invalid/past the end iterator).
     * @param  int $offset
     *      The offset of the $current item in the chain.
     */
    public function __construct(
        ?LdapResultReferenceInterface $first,
        LdapResultReferenceInterface $current = null,
        int $offset = null
    ) {
        parent::__construct($first, $current, $offset);
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
        return $this->getCurrent();
    }
}

// vim: syntax=php sw=4 ts=4 et:
