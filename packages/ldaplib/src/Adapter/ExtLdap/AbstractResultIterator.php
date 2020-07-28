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

use function Korowai\Lib\Context\with;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class AbstractResultIterator implements LdapResultWrapperInterface
{
    use HasLdapResult;

    /**
     * @var LdapResultItemWrapper|null
     */
    private $current;

    /**
     * Initializes the iterator
     *
     * @param  LdapResultInterface $ldapResult
     *      The ldap search result which provides first entry in the entry chain
     * @param  object|null $current
     *      The element currently pointed to by iterator.
     *
     * The ``$ldapResult`` object is used by ``rewind()`` method.
     */
    public function __construct(LdapResultInterface $ldapResult, ?LdapResultItemInterface $current)
    {
        $this->setLdapResult($ldapResult);
        $this->setCurrentLdapResultItem($current);
    }

    /**
     * Returns the current element.
     */
    final public function current()
    {
        return $this->current;
    }

    /**
     * Move forward to next element
     */
    final public function next()
    {
        if (($current = $this->getCurrentLdapResultItem()) === null) {
            return null;
        }
        /** @var object|false */
        $next = with(LdapLinkErrorHandler::fromLdapLinkWrapper($current))(function ($eh) {
            return $this->next_item();
        });
        $this->setCurrentLdapResultItem($next ? null);
    }

    /**
     * Rewind the iterator to the first element
     */
    final public function rewind()
    {
        /** @var object|false */
        $first = with(LdapLinkErrorHandler::fromLdapLinkWrapper($result))(function ($eh) {
            return $this->first_item();
        });
        $this->setCurrentLdapResultItem($first ? null);
    }

    /**
     * Checks if current position is valid
     *
     * @return bool
     */
    final public function valid() : bool
    {
        return $this->current !== null;
    }

    /**
     * @param LdapResultItemInterface|null $current
     */
    final private function setCurrentLdapResultItem(?LdapResultItemInterface $current)
    {
        $this->current = $current ? $this->wrap($current) : null;
    }

    /**
     * @return LdapResultItemInterface|null
     */
    final private function getCurrentLdapResultItem() : ?LdapResultItemInterface
    {
        return $this->current ? $this->current->getLdapResultItem() : null;
    }

    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

    /**
     * Returns first result item of the particular type (entry/reference) in
     * the result message chain.
     *
     * @return LdapResultItemInterface|false
     */
    abstract protected function first_item();

    /**
     * Returns next result item of the particular type (entry/reference) in
     * the result message chain.
     *
     * @return LdapResultItemInterface|false
     */
    abstract protected function next_item();

    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd

    abstract protected function wrap(LdapResultItemInterface $item);
}

// vim: syntax=php sw=4 ts=4 et:
