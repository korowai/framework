<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Core;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapResultEntryIterator extends AbstractLdapResultItemIterator implements LdapResultEntryIteratorInterface
{
    /**
     * Initializes the iterator.
     *
     * @param null|ldapResultEntryInterface $first
     *                                               The first result entry in the list (``null`` to create an
     *                                               iterator over empty sequence which is always invalid)
     * @param null|ldapResultEntryInterface $current
     *                                               The item currently pointed to by iterator (``null`` to create an
     *                                               invalid/past the end iterator)
     * @param int                           $offset
     *                                               The offset of the $current item in the chain
     */
    public function __construct(
        ?LdapResultEntryInterface $first,
        LdapResultEntryInterface $current = null,
        int $offset = null
    ) {
        parent::__construct($first, $current, $offset);
    }

    /**
     * @psalm-mutation-free
     * @psalm-suppress MoreSpecificReturnType
     */
    public function current(): ?LdapResultEntryInterface
    {
        // @var LdapResultEntryInterface|null
        return $this->getCurrent();
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
