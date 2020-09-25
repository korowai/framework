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

use Korowai\Lib\Ldap\Core\LdapResultReferenceIteratorInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ResultReferenceIterator extends AbstractResultItemIterator implements ResultReferenceIteratorInterface
{
    /**
     * Constructs ResultReferenceIterator.
     */
    public function __construct(LdapResultReferenceIteratorInterface $ldapIterator)
    {
        parent::__construct($ldapIterator);
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-mutation-free
     */
    public function current(): ?ResultReferenceInterface
    {
        /** @var LdapResultReferenceIteratorInterface $this->ldapIterator */
        $current = $this->ldapIterator->current();

        return null === $current ? null : new ResultReference($current);
    }
}

// vim: syntax=php sw=4 ts=4 et:
