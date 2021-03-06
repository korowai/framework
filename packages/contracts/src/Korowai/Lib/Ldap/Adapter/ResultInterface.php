<?php
/**
 * @file src/Korowai/Lib/Ldap/Adapter/ResultInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\contracts
 * @license Distributed under MIT license.
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
     */
    public function getResultEntryIterator() : ResultEntryIteratorInterface;

    /**
     * Get iterator over result's references
     *
     * @return ResultReferenceIteratorInterface The iterator
     */
    public function getResultReferenceIterator() : ResultReferenceIteratorInterface;

    /**
     * Get an array of result entries.
     *
     * @return array
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
     */
    public function getEntries(bool $use_keys = true) : array;
}

// vim: syntax=php sw=4 ts=4 et:
