<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\LdaplibInterfaces;

/**
 * @todo Write documentation
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ObjectPropertyGettersMap
{
    /**
     * Object property getters per class for korowai/ldaplib-interfaces package.
     *
     * @var array
     */
    private static $objectPropertyGettersMap = [
        \Korowai\Lib\Ldap\Adapter\AdapterFactoryInterface::class => [
        ],

        \Korowai\Lib\Ldap\Adapter\AdapterInterface::class => [
            'binding'                   => 'getBinding',
            'entryManager'              => 'getEntryManager',
        ],

        \Korowai\Lib\Ldap\Adapter\BindingInterface::class => [
            'isBound'                   => 'isBound',
        ],

        \Korowai\Lib\Ldap\Adapter\CompareQueryInterface::class => [
            'result'                    => 'getResult',
        ],

        \Korowai\Lib\Ldap\Adapter\EntryManagerInterface::class => [
        ],

        \Korowai\Lib\Ldap\Adapter\ResultAttributeIteratorInterface::class => [
        ],

        \Korowai\Lib\Ldap\Adapter\ResultEntryInterface::class => [
            'dn'                        => 'getDn',
            'attributes'                => 'getAttributes',
            'entry'                     => 'toEntry',
            'attributeIterator'         => 'getAttributeIterator',
        ],

        \Korowai\Lib\Ldap\Adapter\ResultEntryIteratorInterface::class => [
        ],

        \Korowai\Lib\Ldap\Adapter\ResultInterface::class => [
            'resultEntryIterator'       => 'getResultEntryIterator',
            'resultReferenceIterator'   => 'getResultReferenceIterator',
            'resultEntries'             => 'getResultEntries',
            'resultReferences'          => 'getResultReferences',
            'entries'                   => 'getEntries',
        ],

        \Korowai\Lib\Ldap\Adapter\ResultReferenceInterface::class => [
            'referrals'                 => 'getReferrals',
            'referralIterator'          => 'getReferralIterator',
        ],

        \Korowai\Lib\Ldap\Adapter\ResultReferenceIteratorInterface::class => [
        ],

        \Korowai\Lib\Ldap\Adapter\ResultReferralIteratorInterface::class => [
        ],

        \Korowai\Lib\Ldap\Adapter\SearchQueryInterface::class => [
            'result'                    => 'getResult',
        ],

        \Korowai\Lib\Ldap\ConnectionParametersInterface::class => [
            'host'                      => 'host',
            'port'                      => 'port',
            'encryption'                => 'encryption',
            'uri'                       => 'uri',
            'options'                   => 'options',
        ],

        \Korowai\Lib\Ldap\EntryInterface::class => [
            'dn'                        => 'getDn',
            'attributes'                => 'getAttributes',
        ],

        \Korowai\Lib\Ldap\LdapInterface::class => [
            'adapter'                   => 'getAdapter',
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

// vim: syntax=php sw=4 ts=4 et:
