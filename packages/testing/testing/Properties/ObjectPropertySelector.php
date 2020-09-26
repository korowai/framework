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

use PHPUnit\Framework\InvalidArgumentException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ObjectPropertySelector extends AbstractPropertySelector
{
    /**
     * @psalm-assert-if-true object $subject
     */
    public function canSelectFrom($subject): bool
    {
        return is_object($subject);
    }

    /**
     * @psalm-assert object $subject
     */
    protected function selectWithMethod($subject, $method, &$retval = null): bool
    {
        if (!is_object($subject)) {
            throw InvalidArgumentException::create(1, 'object');
        }
        if (!method_exists($subject, $method)) {
            return false;
        }
        $retval = call_user_func([$subject, $method]);

        return true;
    }

    /**
     * @psalm-assert object $subject
     */
    protected function selectWithAttribute($subject, $key, &$retval = null): bool
    {
        if (!is_object($subject)) {
            throw InvalidArgumentException::create(1, 'object');
        }
        if (!property_exists($subject, $key)) {
            return false;
        }
        $retval = $subject->{$key};

        return true;
    }
}

// vim: syntax=php sw=4 ts=4 et:
