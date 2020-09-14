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
 * @todo Write documentation
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ObjectPropertyGettersMap
{
    /**
     * Object property getters per class for korowai/contracts package.
     *
     * @var array
     */
    private static $objectPropertyGettersMap = [
        \Iterator::class => [
            'current'                   => 'current',
            'key'                       => 'key',
            'valid'                     => 'valid',
        ],

        \IteratorAggregate::class => [
            'iterator'                  => 'getIterator',
        ],

        \Throwable::class               => [
            'message'                   => 'getMessage',
            'code'                      => 'getCode',
            'file'                      => 'getFile',
            'line'                      => 'getLine',
            'trace'                     => 'getTrace',
            'traceAsString'             => 'getTraceAsString',
            'previous'                  => 'getPrevious',
        ],
    ];

    /**
     * Returns the property getters map.
     *
     * @return array
     */
    public static function getObjectPropertyGettersMap() : array
    {
        return self::$objectPropertyGettersMap;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
