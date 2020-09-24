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
abstract class AbstractPropertySelector implements PropertySelectorInterface
{
    abstract protected function selectWithMethod($subject, $method, array &$retval = null) : bool;
    abstract protected function selectWithAttribute($subject, $key, array &$retval = null) : bool;

    public function selectProperty($subject, $key, &$retval = null) : bool
    {
        $method = (substr($key, -2) === '()') ? substr($key, 0, -2) : null;
        if ($method !== null) {
            return $this->selectWithMethod($subject, $method, $retval);
        } else {
            return $this->selectWithAttribute($subject, $key, $retval);
        }
        return false;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
