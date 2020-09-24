<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Core;

use InvalidArgumentException;
use function Korowai\Lib\Context\with;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class AbstractLdapResultItemIterator implements LdapResultItemIteratorInterface
{
    /**
     * @var null|LdapResultItemInterface
     *
     * @psalm-readonly
     */
    private $first;

    /**
     * @var null|LdapResultItemInterface
     */
    private $current;

    /**
     * @var null|int
     */
    private $offset;

    /**
     * Initializes the iterator.
     *
     * @param null|ldapResultItemInterface $first
     *                                              The first result item in the list (``null`` to create an
     *                                              iterator over empty sequence which is always invalid)
     * @param null|ldapResultItemInterface $current
     *                                              The item currently pointed to by iterator (``null`` to create an
     *                                              invalid/past the end iterator)
     * @param int                          $offset
     *                                              The offset of the $current item in the chain
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
     * @psalm-mutation-free
     */
    public function getFirst(): ?LdapResultItemInterface
    {
        return $this->first;
    }

    /**
     * Returns current item.
     *
     * @psalm-mutation-free
     */
    public function getCurrent(): ?LdapResultItemInterface
    {
        return $this->current;
    }

    /**
     * Return the key of the current element, that is the offset of the current
     * item in the chain.
     *
     * @psalm-mutation-free
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    final public function key(): ?int
    {
        return $this->offset;
    }

    /**
     * Move forward to next element.
     *
     * @throws \Korowai\Lib\Ldap\ExceptionInterface
     */
    final public function next(): void
    {
        if (null === ($current = $this->current) || null === ($offset = $this->offset)) {
            return;
        }
        $next = with(LdapLinkErrorHandler::fromLdapLinkWrapper($current))(function () use ($current) {
            return $current->next_item();
        });
        $this->setState($next, $offset + 1);
    }

    /**
     * Rewind the iterator to the first element.
     */
    final public function rewind(): void
    {
        $this->setState($this->first, 0);
    }

    /**
     * Checks if current position is valid.
     *
     * @psalm-mutation-free
     */
    final public function valid(): bool
    {
        return null !== $this->current && null !== $this->offset;
    }

    /**
     * @param mixed $current
     *
     * @throws InvalidArgumentException
     *
     * @psalm-external-mutation-free
     */
    final private function setState($current, ?int $offset): void
    {
        if (false === $current || null === $current) {
            $this->current = null;
            $this->offset = null;
        } else {
            $this->current = $current;
            $this->offset = $offset ?? 0;
        }
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
