<?php
/**
 * @file tests/Korowai/Lib/Ldap/Adapter/ResultEntryInterfaceTrait.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Adapter;

use Korowai\Lib\Ldap\EntryInterface;
use Korowai\Lib\Ldap\Adapter\ResultAttributeIteratorInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ResultEntryInterfaceTrait
{
    use ResultRecordInterfaceTrait;

    public $attributes = null;
    public $entry = null;
    public $attributeIterator = null;

    public function getAttributes() : array
    {
        return $this->attributes;
    }

    public function toEntry() : EntryInterface
    {
        return $this->entry;
    }

    public function getAttributeIterator() : ResultAttributeIteratorInterface
    {
        return $this->attributeIterator;
    }
}

// vim: syntax=php sw=4 ts=4 et:
