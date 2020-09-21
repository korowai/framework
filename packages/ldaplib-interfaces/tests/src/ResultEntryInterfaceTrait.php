<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap;

use Korowai\Lib\Ldap\EntryInterface;
use Korowai\Lib\Ldap\ResultAttributeIteratorInterface;
use Korowai\Testing\Dummies\PhpIteratorAggregateTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ResultEntryInterfaceTrait
{
    use PhpIteratorAggregateTrait;

    public $dn = null;
    public $attributes = null;
    public $entry = null;
    public $attributeIterator = null;

    public function getDn() : string
    {
        return $this->dn;
    }

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

// vim: syntax=php sw=4 ts=4 et tw=119:
