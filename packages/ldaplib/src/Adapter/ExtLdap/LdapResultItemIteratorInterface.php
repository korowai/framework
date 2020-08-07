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
interface LdapResultItemIteratorInterface extends \Iterator
{
    /**
     * Returns current offset.
     *
     * @return int|null
     *
     * @psalm-suppress ImplementedReturnTypeMismatch
     * @psalm-mutation-free
     */
    public function key() : ?int;

    /**
     * Moves iterator to the next position.
     */
    public function next() : void;

    /**
     * Moves iterator to the first position.
     */
    public function rewind() : void;

    /**
     * Returns true if the iterator is valid.
     *
     * @return bool
     *
     * @psalm-mutation-free
     */
    public function valid() : bool;
}

// vim: syntax=php sw=4 ts=4 et:
