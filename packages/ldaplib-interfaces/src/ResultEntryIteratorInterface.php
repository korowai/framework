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
 * Iterates through entries returned by an ldap search query.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface ResultEntryIteratorInterface extends ResultItemIteratorInterface
{
    /**
     * Returns the current entry or null if the iterator is invalid.
     *
     * @return ResultEntryInterface|null
     *
     * @psalm-mutation-free
     */
    public function current() : ?ResultEntryInterface;
}

// vim: syntax=php sw=4 ts=4 et tw=119:
