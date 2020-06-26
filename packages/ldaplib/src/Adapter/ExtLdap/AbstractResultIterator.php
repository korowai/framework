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
    public function __construct(LdapResultInterface $ldapResult, $current)
    {
        $this->setLdapResult($ldapResult);
        $this->setCurrent($current);
    }

    /**
     * Return the key of the current element, that is DN of the current entry
     */
    public function key()
    {
        // FIXME: DN's may be non-unique in result, while keys should be unique)
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
        if (($current = $this->getCurrent()) === null) {
            return null;
        }
        /** @var object|false */
        $next = with(LdapLinkErrorHandler::fromLdapLinkWrapper($current))(function ($eh) use ($current) {
            return call_user_func([$current, $this->getMethodForNext()]);
        });
        $this->setCurrent($next);
    }

    /**
     * Rewind the iterator to the first element
     */
    public function rewind()
    {
        $result = $this->getLdapResult();
        /** @var object|false */
        $first = with(LdapLinkErrorHandler::fromLdapLinkWrapper($result))(function ($eh) use ($result) {
            return call_user_func([$result, $this->getMethodForFirst()]);
        });
        $this->setCurrent($first);
    }

    /**
     * Checks if current position is valid
     */
    public function valid()
    {
        return $this->current !== null;
    }

    private function setCurrent($current)
    {
        $this->current = $current ? $this->wrapElement($current) : null;
    }

    private function getCurrent()
    {
        return $this->current ? $this->unwrapElement($this->current) : null;
    }

    abstract protected function getMethodForNext();
    abstract protected function getMethodForFirst();
    abstract protected function wrapElement($unwrapped);
    abstract protected function unwrapElement($wrapped);
}

// vim: syntax=php sw=4 ts=4 et:
