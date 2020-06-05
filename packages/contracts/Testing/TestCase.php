<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Contracts;

/**
 * Abstract base class for korowai/ldiflib unit tests.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class TestCase extends \Korowai\Testing\TestCase
{
    /**
     * {@inheritdoc}
     */
    public static function objectPropertyGettersMap() : array
    {
        return array_merge_recursive(
            parent::objectPropertyGettersMap(),
            ObjectPropertyGettersMap::getObjectPropertyGettersMap()
        );
    }

    public static function assertObjectPropertyGetters(array $expect, string $class, string $message = '')
    {
        $getters = static::objectPropertyGettersMap();
        if (empty($message)) {
            $message = 'Failed asserting that '.static::class.'::objectPropertyGettersMap() '.
                       'has expected entry for \''.$class.'\'.';
        }
        static::assertArrayHasKey($class, $getters, $message);
        static::assertSame($expect, $getters[$class], $message);
    }
}

// vim: syntax=php sw=4 ts=4 et:
