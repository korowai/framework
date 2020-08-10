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
        $this->ldapResult = $ldapResult;
    }

    /**
     * {@inheritdoc}
     */
    public function getResultEntryIterator() : ResultEntryIteratorInterface
    {
        return new ResultEntryIterator(
            $this->getResultItemIterator('first_entry', LdapResultEntryIterator::class)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getResultReferenceIterator() : ResultReferenceIteratorInterface
    {
        return new ResultReferenceIterator(
            $this->getResultItemIterator('first_reference', LdapResultReferenceIterator::class)
        );
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
     * @param string $method
     * @param string $class
     * @return object
     *
     * @psalm-template T
     * @psalm-param class-string<T> $class
     * @psalm-return T
     */
    private function getResultItemIterator(string $method, string $class) : object
    {
        $result = $this->getLdapResult();
        $first = with(LdapLinkErrorHandler::fromLdapLinkWrapper($result))(
            /** @return LdapResultItemInterface|false */
            function () use ($result, $method) {
                return $result->{$method}();
            }
        );
        return new $class($first ?: null, $first ?: null, 0);
    }
}

// vim: syntax=php sw=4 ts=4 et:
