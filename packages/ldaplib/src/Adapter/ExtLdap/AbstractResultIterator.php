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
    use LdapResultWrapperTrait;

    /**
     * @var int
     */
    private $offset;

    /**
     * @var LdapResultItemWrapperInterface|null
     */
    private $current;

    /**
     * Initializes the iterator
     *
     * @param  LdapResultInterface $ldapResult
     *      The ldap search result which provides first item in the chain.
     * @param  LdapResultItemInterface|null $current
     *      The item currently pointed to by iterator (``null`` to create an
     *      invalid/past the end iterator).
     * @param  int $offset
     *      The offset of the $current item in the chain.
     */
    public function __construct(
        LdapResultInterface $ldapResult,
        LdapResultItemInterface $current = null,
        int $offset = null
    ) {
        $this->setLdapResult($ldapResult);
        $this->setCurrentLdapResultItemAndOffset($current, $offset);
    }

    /**
     * Return the key of the current element, that is the offset of the current
     * item in the chain.
     */
    final public function key()
    {
        return $this->offset;
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
    final public function next() : void
    {
        if (($current = $this->getCurrentLdapResultItem()) === null) {
            return;
        }
        $next = with(LdapLinkErrorHandler::fromLdapLinkWrapper($current))(function ($eh) use ($current) {
            return $this->next_item($current);
        });
        $this->setCurrentLdapResultItemAndOffset($next, $this->offset+1);
    }

    /**
     * Rewind the iterator to the first element
     */
    final public function rewind() : void
    {
        $result = $this->getLdapResult();
        $first = with(LdapLinkErrorHandler::fromLdapLinkWrapper($result))(function ($eh) {
            return $this->first_item();
        });
        $this->setCurrentLdapResultItemAndOffset($first, 0);
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
     * @return LdapResultItemInterface|null
     */
    final private function getCurrentLdapResultItem() : ?LdapResultItemInterface
    {
        return $this->current ? $this->current->getLdapResultItem() : null;
    }

    /**
     * @param LdapResultItemInterface|null $current
     * @param int $offset
     */
    final private function setCurrentLdapResultItemAndOffset(?LdapResultItemInterface $current, ?int $offset)
    {
        if ($current !== null) {
            $this->current = $this->wrap($current);
            $this->offset = $offset ?? 0;
        } else {
            $this->current = null;
            $this->offset = -1;
        }
    }

    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

    /**
     * Returns first result item of the particular type (entry/reference) in
     * the result message chain.
     *
     * @return LdapResultItemInterface|null
     */
    abstract protected function first_item() : ?LdapResultItemInterface;

    /**
     * Returns next result item of the particular type (entry/reference) in
     * the result message chain.
     *
     * @return LdapResultItemInterface|null
     */
    abstract protected function next_item(LdapResultItemInterface $current) : ?LdapResultItemInterface;

    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd

    abstract protected function wrap(LdapResultItemInterface $item) : LdapResultItemWrapperInterface;
}

// vim: syntax=php sw=4 ts=4 et:
