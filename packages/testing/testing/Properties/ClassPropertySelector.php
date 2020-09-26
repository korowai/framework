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
final class ClassPropertySelector extends AbstractPropertySelector
{
    /**
     * @psalm-assert-if-true class-string $subject
     */
    public function canSelectFrom($subject): bool
    {
        return is_string($subject) && class_exists($subject);
    }

    /**
     * @psalm-assert class $subject
     */
    protected function selectWithMethod($subject, string $method, &$retval = null): bool
    {
        if (!is_string($subject) || !class_exists($subject)) {
            throw InvalidArgumentException::create(1, 'class');
        }
        if (!method_exists($subject, $method)) {
            return false;
        }
        $retval = call_user_func([$subject, $method]);

        return true;
    }

    /**
     * @psalm-assert class $subject
     */
    protected function selectWithAttribute($subject, string $key, &$retval = null): bool
    {
        if (!is_string($subject) || !class_exists($subject)) {
            throw InvalidArgumentException::create(1, 'class');
        }
        if (!property_exists($subject, $key)) {
            return false;
        }
        $retval = $subject::${$key};

        return true;
    }
}

// vim: syntax=php sw=4 ts=4 et:
