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
        $this->setItem($current);
    }

    /**
     * Return the key of the current element, that is DN of the current entry
     */
    public function key()
    {
        // FIXME: DN's may be non-unique in result, while keys should be unique
        // FIXME: we do not support DNs on references
        return $this->current->getDn();
    }

    /**
     * Returns the current element.
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * Move forward to next element
     */
    public function next()
    {
        if (($current = $this->getItem()) === null) {
            return null;
        }
        /** @var object|false */
        $next = with(LdapLinkErrorHandler::fromLdapLinkWrapper($current))(function ($eh) {
            return $this->next_item();
            //return call_user_func([$current, $this->getMethodForNext()]);
        });
        $this->setItem($next);
    }

    /**
     * Rewind the iterator to the first element
     */
    public function rewind()
    {
        //$result = $this->getLdapResult();
        /** @var object|false */
        $first = with(LdapLinkErrorHandler::fromLdapLinkWrapper($result))(function ($eh) /*use ($result)*/ {
            return $this->first_item();
            //return call_user_func([$result, $this->getMethodForFirst()]);
        });
        $this->setItem($first);
    }

    /**
     * Checks if current position is valid
     */
    public function valid()
    {
        return $this->current !== null;
    }

    /**
     * @param LdapResultItemInterface $current
     */
    private function setItem(LdapResultItemInterface $current)
    {
        $this->current = $current ? $this->wrapItem($current) : null;
    }

    /**
     * @return LdapResultItemInterface
     */
    private function getItem() : LdapResultItemInterface
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
