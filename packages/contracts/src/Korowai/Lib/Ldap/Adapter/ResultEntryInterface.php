<?php
/**
 * @file src/Korowai/Lib/Ldap/Adapter/ResultEntryInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter;

use Korowai\Lib\Ldap\EntryInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface ResultEntryInterface extends ResultRecordInterface
{
    /**
     * Returns entry attributes as an array. The keys in array are lower-case.
     *
     * @return array Entry's attributes
     */
    public function getAttributes() : array;

    /**
     * Creates an actual ``Entry`` object from this object.
     *
     * @return EntryInterface A new instance of EntryInterface
     */
    public function toEntry() : EntryInterface;

    /**
     * Returns an iterator over entry's attributes.
     * @return ResultAttributeIteratorInterface Attribute iterator
     */
    public function getAttributeIterator() : ResultAttributeIteratorInterface;
}

// vim: syntax=php sw=4 ts=4 et:
