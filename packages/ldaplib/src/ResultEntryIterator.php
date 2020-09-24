<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap;

use Korowai\Lib\Ldap\Core\LdapResultEntryIteratorInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ResultEntryIterator extends AbstractResultItemIterator implements ResultEntryIteratorInterface
{
    /**
     * Constructs ResultEntryIterator.
     */
    public function __construct(LdapResultEntryIteratorInterface $ldapIterator)
    {
        parent::__construct($ldapIterator);
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-mutation-free
     */
    public function current(): ?ResultEntryInterface
    {
        /** @var LdapResultEntryIteratorInterface $this->ldapIterator */
        $current = $this->ldapIterator->current();

        return null === $current ? null : new ResultEntry($current);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
