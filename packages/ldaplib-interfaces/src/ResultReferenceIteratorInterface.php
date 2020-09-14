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
 * Iterates through references returned by an ldap search query.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface ResultReferenceIteratorInterface extends ResultItemIteratorInterface
{
    /**
     * Returns the current reference or null if the iterator is invalid.
     *
     * @return ResultReferenceInterface|null
     *
     * @psalm-mutation-free
     */
    public function current() : ?ResultReferenceInterface;
}

// vim: syntax=php sw=4 ts=4 et tw=119:
