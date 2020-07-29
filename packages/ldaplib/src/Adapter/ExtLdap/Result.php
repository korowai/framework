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
use function Korowai\Lib\Context\with;

/**
 * Wrapper for ldap result resource.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class Result extends AbstractResult implements LdapResultWrapperInterface
{
    use LdapResultWrapperTrait;

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
     *
     * @psalm-mutation-free
     */
    public function getResultEntryIterator() : ResultEntryIteratorInterface
    {
        return $this->getResultItemIterator('first_entry', ResultEntryIterator::class);
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-mutation-free
     */
    public function getResultReferenceIterator() : ResultReferenceIteratorInterface
    {
        return $this->getResultItemIterator('first_reference', ResultReferenceIterator::class);
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-mutation-free
     */
    public function getResultEntries() : array
    {
        return iterator_to_array($this->getResultEntryIterator(), false);
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-mutation-free
     */
    public function getResultReferences() : array
    {
        return iterator_to_array($this->getResultReferenceIterator(), false);
    }

    /**
     * @psalm-mutation-free
     */
    private function getResultItemIterator(string $method, string $class)
    {
        $result = $this->getLdapResult();
        /** @var LdapResultReference|false */
        $first = with(LdapLinkErrorHandler::fromLdapLinkWrapper($result))(function ($eh) use ($result, $method) {
            return $result->{$method}();
        });
        return new $class($result, $first === false ? null : $first);
    }
}

// vim: syntax=php sw=4 ts=4 et:
