<?php
/**
 * @file tests/Korowai/PhpIteratorAggregateTrait.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait PhpIteratorAggregateTrait
{
    public $iterator = null;

    public function getIterator()
    {
        return $this->iterator;
    }
}

// vim: syntax=php sw=4 ts=4 et:
