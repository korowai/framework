<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap;

/**
 * Iterates through items (entries or references) returned by an ldap search query.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface ResultItemIteratorInterface extends \Iterator
{
    /**
     * Returns the current offset or null if the iterator is invalid.
     *
     * @psalm-mutation-free
     */
    public function key(): ?int;

    /**
     * Moves the iterator to the next position.
     */
    public function next(): void;

    /**
     * Moves the iterator to the begining of the sequence.
     */
    public function rewind(): void;

    /**
     * Returns true if the iterator is valid or false otherwise.
     *
     * @psalm-mutation-free
     */
    public function valid(): bool;
}

// vim: syntax=php sw=4 ts=4 et tw=119:
