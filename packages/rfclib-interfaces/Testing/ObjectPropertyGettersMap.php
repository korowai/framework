<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\RfclibInterfaces;

/**
 * @todo Write documentation.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ObjectPropertyGettersMap
{
    /**
     * Object property getters per class for korowai/rfclib-interfaces package.
     *
     * @var array
     */
    private static $objectPropertyGettersMap = [
        \Korowai\Lib\Rfc\RuleInterface::class => [
            'toString'                  => '__toString',
            'regexp'                    => 'regexp',
            'captures'                  => 'captures',
            'errorCaptures'             => 'errorCaptures',
            'valueCaptures'             => 'valueCaptures',
        ],

        \Korowai\Lib\Rfc\StaticRuleSetInterface::class => [
            'rules'                     => 'rules',
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
