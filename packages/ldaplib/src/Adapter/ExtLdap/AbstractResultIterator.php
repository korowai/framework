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

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class AbstractResultIterator implements LdapResultWrapperInterface
{
    use HasLdapResult;

    private $pointed;

    /**
     * Constructs ResultEntryIterator
     *
     * @param  LdapResultInterface $ldapResult
     *      The ldap search result which provides first entry in the entry chain
     * @param  object|null $pointed
     *      The element currently pointed to by iterator.
     *
     * The ``$ldapResult`` object is used by ``rewind()`` method.
     */
    public function __construct(LdapResultInterface $ldapResult, $pointed)
    {
        $this->setLdapResult($ldapResult);
        $this->pointed = $pointed;
    }

    /**
     * Returns the ``$pointed`` provided to ``__construct()`` at creation
     * @return mixed The ``$pointed`` provided to ``__construct()`` at creation
     */
    public function getPointed()
    {
        return $this->pointed;
    }

    /**
     * Return the current element, that is the current entry
     */
    public function current()
    {
        return $this->pointed;
    }

    /**
     * Return the key of the current element, that is DN of the current entry
     */
    public function key()
    {
        return $this->pointed->getDn();
    }

    /**
     * Move forward to next element
     */
    public function next()
    {
        $method = $this->getMethodForNext();
        $this->pointed = call_user_func([$this->pointed, $method]);
    }

    /**
     * Rewind the iterator to the first element
     */
    public function rewind()
    {
        $method = $this->getMethodForFirst();
        $this->pointed = call_user_func([$this->getLdapResult(), $method]);
    }

    /**
     * Checks if current position is valid
     */
    public function valid()
    {
        return is_object($this->pointed);
    }

    abstract protected function getMethodForNext();
    abstract protected function getMethodForFirst();
}

// vim: syntax=php sw=4 ts=4 et:
