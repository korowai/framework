<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Properties;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class AbstractPropertySelector implements PropertySelectorInterface
{
    public function selectProperty($subject, $key, &$retval = null): bool
    {
        $method = ('()' === substr($key, -2)) ? substr($key, 0, -2) : null;
        if (null !== $method) {
            return $this->selectWithMethod($subject, $method, $retval);
        }

        return $this->selectWithAttribute($subject, $key, $retval);

        return false;
    }

    /**
     * @param false|string $method
     */
    abstract protected function selectWithMethod($subject, string $method, array &$retval = null): bool;

    abstract protected function selectWithAttribute($subject, string $key, array &$retval = null): bool;
}

// vim: syntax=php sw=4 ts=4 et:
