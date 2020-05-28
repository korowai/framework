<?php
/**
 * @file tests/PhpIteratorTrait.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait PhpIteratorTrait
{
    public $current = null;
    public $key = null;
    public $next = null;
    public $rewind = null;
    public $valid = null;

    public function current()
    {
        return $this->current;
    }

    public function key()
    {
        return $this->key;
    }

    public function next()
    {
        return $this->next;
    }

    public function rewind()
    {
        return $this->rewind;
    }

    public function valid()
    {
        return $this->valid;
    }
}

// vim: syntax=php sw=4 ts=4 et:
