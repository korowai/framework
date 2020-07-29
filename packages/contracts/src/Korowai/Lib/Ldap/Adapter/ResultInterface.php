<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface ResultInterface extends \IteratorAggregate
{
    /**
     * Get iterator over result's entries
     *
     * @return ResultEntryIteratorInterface The iterator
     *
     * @psalm-mutation-free
     */
    public function getResultEntryIterator() : ResultEntryIteratorInterface;

    /**
     * Get iterator over result's references
     *
     * @return ResultReferenceIteratorInterface The iterator
     *
     * @psalm-mutation-free
     */
    public function getResultReferenceIterator() : ResultReferenceIteratorInterface;

    /**
     * Get an array of result entries.
     *
     * @return array
     *
     * @psalm-mutation-free
     */
    public function getResultEntries() : array;

    /**
     * Get an array of result references.
     *
     * @return array
     */
    public function getResultReferences() : array;

    /**
     * Get an array of Entries from ldap result
     *
     * @return array Entries
     *
     * @psalm-mutation-free
     */
    public function getEntries(bool $use_keys = true) : array;
}

// vim: syntax=php sw=4 ts=4 et:
