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

use Korowai\Lib\Ldap\Adapter\AbstractResult;
use Korowai\Lib\Ldap\Adapter\ResultEntryIteratorInterface;
use Korowai\Lib\Ldap\Adapter\ResultReferenceIteratorInterface;

/**
 * Wrapper for ldap result resource.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class Result extends AbstractResult implements LdapResultWrapperInterface
{
    use HasLdapResult;

    /**
     * Initializes the object
     *
     * @param  LdapResultInterface $ldapResult
     */
    public function __construct(LdapResultInterface $ldapResult)
    {
        $this->setLdapResult($ldapResult);
    }

    /**
     * {@inheritdoc}
     */
    public function getResultEntries() : array
    {
        return iterator_to_array($this->getResultEntryIterator(), false);
    }

    /**
     * {@inheritdoc}
     */
    public function getResultReferences() : array
    {
        return iterator_to_array($this->getResultReferenceIterator(), false);
    }

    /**
     * {@inheritdoc}
     */
    public function getResultEntryIterator() : ResultEntryIteratorInterface
    {
        $result = $this-getLdapResult();
        // FIXME: with(LdapLinkErrorHandler::...)
        $first = $result->first_entry();
        return new ResultEntryIterator($result, $first === false ? null : $first);
    }

    /**
     * {@inheritdoc}
     */
    public function getResultReferenceIterator() : ResultReferenceIteratorInterface
    {
        $result = $this-getLdapResult();
        // FIXME: with(LdapLinkErrorHandler::...)
        $first = $result->first_reference();
        return new ResultReferenceIterator($result, $first === false ? null : $first);
    }
}

// vim: syntax=php sw=4 ts=4 et:
