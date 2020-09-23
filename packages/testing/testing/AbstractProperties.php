<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class AbstractProperties extends \ArrayObject implements PropertiesInterface
{
    final public function getComparableArray() : array
    {
        $array = $this->getArrayCopy();
        array_walk_recursive($array, [$this, 'makeValueComparable']);
        return $array;
    }

    protected function makeValueComparable(&$value) : void
    {
        if ($value instanceof static) {
            $value = $value->getComparableArray();
        }
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
