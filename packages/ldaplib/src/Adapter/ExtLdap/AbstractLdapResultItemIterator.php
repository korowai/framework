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
use InvalidArgumentException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class AbstractLdapResultItemIterator implements LdapResultItemIteratorInterface
{
    /**
     * @var LdapResultItemInterface|null
     *
     * @psalm-readonly
     */
    private $first;

    /**
     * @var LdapResultItemInterface|null
     */
    protected $current;

    /**
     * @var int|null
     */
    private $offset;

    /**
     * Initializes the iterator
     *
     * @param  LdapResultItemInterface|null $first
     *      The first result item in the list (``null`` to create an
     *      iterator over empty sequence which is always invalid).
     * @param  LdapResultItemInterface|null $current
     *      The item currently pointed to by iterator (``null`` to create an
     *      invalid/past the end iterator).
     * @param  int $offset
     *      The offset of the $current item in the chain.
     */
    public function __construct(
        ?LdapResultItemInterface $first,
        LdapResultItemInterface $current = null,
        int $offset = null
    ) {
        $this->first = $first;
        $this->setState($current, $offset);
    }

    /**
     * Returns first item from the list.
     *
     * @return LdapResultItemInterface|null
     *
     * @psalm-mutation-free
     */
    public function getFirst() : ?LdapResultItemInterface
    {
        return $this->first;
    }

    /**
     * Returns current item.
     *
     * @return LdapResultItemInterface|null
     *
     * @psalm-mutation-free
     */
    public function getCurrent() : ?LdapResultItemInterface
    {
        return $this->current;
    }

    /**
     * Return the key of the current element, that is the offset of the current
     * item in the chain.
     *
     * @return int|null
     *
     * @psalm-mutation-free
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    final public function key() : ?int
    {
        return $this->offset;
    }

    /**
     * Move forward to next element
     */
    final public function next() : void
    {
        if (($current = $this->current) === null || ($offset = $this->offset) === null) {
            return;
        }
        $next = $current->next_item();
        $this->setState($next, $offset + 1);
    }

    /**
     * Rewind the iterator to the first element
     */
    final public function rewind() : void
    {
        $this->setState($this->first, 0);
    }

    /**
     * Checks if current position is valid
     *
     * @return bool
     *
     * @psalm-mutation-free
     */
    final public function valid() : bool
    {
        return $this->current !== null && $this->offset !== null;
    }

    /**
     * @param mixed $current
     * @throws InvalidArgumentException
     *
     * @psalm-external-mutation-free
     */
    final private function setState($current, ?int $offset) : void
    {
        if ($current === false || $current === null) {
            $this->current = null;
            $this->offset = null;
        } else {
            $this->current = $current;
            $this->offset = $offset ?? 0;
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
