<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Adapter;

use Korowai\Tests\PhpIteratorAggregateTrait;
use Korowai\Lib\Ldap\Adapter\ResultEntryIteratorInterface;
use Korowai\Lib\Ldap\Adapter\ResultReferenceIteratorInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ResultInterfaceTrait
{
    use PhpIteratorAggregateTrait;

    public $resultEntryIterator;
    public $resultReferenceIterator;
    public $resultEntries;
    public $resultReferences;
    public $entries;

    public function getResultEntryIterator() : ResultEntryIteratorInterface
    {
        return $this->resultEntryIterator;
    }

    public function getResultReferenceIterator() : ResultReferenceIteratorInterface
    {
        return $this->resultReferenceIterator;
    }

    public function getResultEntries() : array
    {
        return $this->resultEntries;
    }

    public function getResultReferences() : array
    {
        return $this->resultReferences;
    }

    public function getEntries(bool $use_keys = true) : array
    {
        return $this->entries;
    }
}

// vim: syntax=php sw=4 ts=4 et:
