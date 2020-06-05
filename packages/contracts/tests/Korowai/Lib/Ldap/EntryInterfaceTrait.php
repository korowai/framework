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

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait EntryInterfaceTrait
{
    public $dn = null;
    public $attributes = null;
    public $attribute = null;
    public $hasAttribute = null;
    public $setAttributes = null;
    public $setAttribute = null;

    public function getDn() : string
    {
        return $this->dn;
    }

    public function setDn(string $dn)
    {
        return $this->setDn;
    }

    public function getAttributes() : array
    {
        return $this->attributes;
    }

    public function getAttribute(string $name) : array
    {
        return $this->attribute;
    }

    public function hasAttribute(string $name) : bool
    {
        return $this->hasAttribute;
    }

    public function setAttributes(array $attribute)
    {
        return $this->setAttributes;
    }

    public function setAttribute(string $name, array $values)
    {
        return $this->setAttribute;
    }
}

// vim: syntax=php sw=4 ts=4 et:
