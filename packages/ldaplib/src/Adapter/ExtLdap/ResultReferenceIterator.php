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

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ResultReferenceIterator extends AbstractResultIterator implements ResultReferenceIteratorInterface
{
    /**
     * Constructs ResultReferenceIterator
     *
     * @param Result $result                  The ldap search result which provides
     *                                        first entry in the entry chain
     * @param ResultReference|null $reference The current reference in the chain or
     *                                        ``null`` to create an invalid (past the
     *                                        end) iterator
     *
     * The ``$result`` object is used by ``rewind()`` method.
     */
    public function __construct(LdapResultInterface $ldapResult, ?ResultReference $reference)
    {
        parent::__construct($ldapResult, $reference);
    }

    protected function first_item()
    {
        return $this->getLdapResult()->first_reference();
    }

    protected function next_item()
    {
        return $this->getCurent()->next_reference();
    }

    protected function wrap(LdapResultReferenceInterface $item)
    {
        return new ResultReference($item);
    }
}

// vim: syntax=php sw=4 ts=4 et:
