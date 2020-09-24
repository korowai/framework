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
    public function canSelectFrom($subject): bool
    {
        return is_string($subject) && class_exists($subject);
    }

    protected function selectWithMethod($class, $method, &$retval = null): bool
    {
        if (!is_string($class) || !class_exists($class)) {
            throw InvalidArgumentException::create(1, 'class');
        }
        if (!method_exists($class, $method)) {
            return false;
        }
        $retval = call_user_func([$class, $method]);

        return true;
    }

    protected function selectWithAttribute($class, $key, &$retval = null): bool
    {
        if (!is_string($class) || !class_exists($class)) {
            throw InvalidArgumentException::create(1, 'class');
        }
        if (!property_exists($class, $key)) {
            return false;
        }
        $retval = $class::${$key};

        return true;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
