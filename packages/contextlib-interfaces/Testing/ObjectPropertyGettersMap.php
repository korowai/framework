<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\ContextlibInterfaces;

/**
 * @todo Write documentation
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ObjectPropertyGettersMap
{
    /**
     * Object property getters per class for korowai/contextlib-interfaces package.
     *
     * @var array
     */
    private static $objectPropertyGettersMap = [
        \Korowai\Lib\Context\ContextFactoryInterface::class => [
        ],

        \Korowai\Lib\Context\ContextFactoryStackInterface::class => [
            'top'                       => 'top',
            'size'                      => 'size',
        ],

        \Korowai\Lib\Context\ContextManagerInterface::class => [
        ],

        \Korowai\Lib\Context\ExecutorInterface::class => [
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

// vim: syntax=php sw=4 ts=4 et tw=120:
