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
    public function canSelectFrom($subject): bool
    {
        return is_object($subject);
    }

    protected function selectWithMethod($object, $method, &$retval = null): bool
    {
        if (!is_object($object)) {
            throw InvalidArgumentException::create(1, 'object');
        }
        if (!method_exists($object, $method)) {
            return false;
        }
        $retval = call_user_func([$object, $method]);

        return true;
    }

    protected function selectWithAttribute($object, $key, &$retval = null): bool
    {
        if (!is_object($object)) {
            throw InvalidArgumentException::create(1, 'object');
        }
        if (!property_exists($object, $key)) {
            return false;
        }
        $retval = $object->{$key};

        return true;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
