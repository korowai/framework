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
    /**
     * @param mixed $subject
     * @param mixed $key
     * @param mixed $retval
     *
     * @psalm-param array-key $key
     */
    public function selectProperty($subject, $key, &$retval = null): bool
    {
        $method = ('()' === substr((string)$key, -2)) ? substr((string)$key, 0, -2) : null;
        if (null !== $method) {
            return $this->selectWithMethod($subject, $method, $retval);
        }

        return $this->selectWithAttribute($subject, $key, $retval);

        return false;
    }

    /**
     * @param mixed $subject
     * @param string $method
     * @param mixed $retval
     */
    abstract protected function selectWithMethod($subject, string $method, &$retval = null): bool;

    /**
     * @param mixed $subject
     * @param mixed $key
     * @param mixed $retval
     * @psalm-param array-key $key
     */
    abstract protected function selectWithAttribute($subject, $key, &$retval = null): bool;
}

// vim: syntax=php sw=4 ts=4 et:
