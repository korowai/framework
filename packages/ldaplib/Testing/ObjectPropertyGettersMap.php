<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Ldaplib;

/**
 * Defines getters map for properties of interfaces from korowai/ldaplib
 * package.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ObjectPropertyGettersMap
{
    /**
     * Object property getters per class for korowai/ldaplib package.
     *
     * @var array
     */
    protected static $ldaplibObjectPropertyGettersMap = [
        \Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperInterface::class => [
            'ldapLink' => 'getLdapLink'
        ],
    ];

    /**
     * Returns the property getters map.
     *
     * @return array
     */
    public static function getObjectPropertyGettersMap() : array
    {
        return self::$ldaplibObjectPropertyGettersMap;
    }
}

// vim: syntax=php sw=4 ts=4 et:
