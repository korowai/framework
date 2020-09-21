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
        \Korowai\Lib\Ldap\AdapterFactoryInterface::class => [
        ],

        \Korowai\Lib\Ldap\AdapterInterface::class => [
            'binding'                   => 'getBinding',
            'entryManager'              => 'getEntryManager',
        ],

        \Korowai\Lib\Ldap\BindingInterface::class => [
            'isBound'                   => 'isBound',
        ],

        \Korowai\Lib\Ldap\CompareQueryInterface::class => [
            'result'                    => 'getResult',
        ],

        \Korowai\Lib\Ldap\ComparingInterface::class => [
        ],

        \Korowai\Lib\Ldap\EntryInterface::class => [
            'dn'                        => 'getDn',
            'attributes'                => 'getAttributes',
        ],

        \Korowai\Lib\Ldap\EntryManagerInterface::class => [
        ],

        \Korowai\Lib\Ldap\ErrorExceptionInterface::class => [
            'severity'                  => 'getSeverity',
        ],

        \Korowai\Lib\Ldap\ExceptionInterface::class => [
        ],

        \Korowai\Lib\Ldap\LdapExceptionInterface::class => [
        ],

        \Korowai\Lib\Ldap\LdapFactoryInterface::class => [
        ],

        \Korowai\Lib\Ldap\LdapInterface::class => [
        ],

        \Korowai\Lib\Ldap\ResultAttributeIteratorInterface::class => [
        ],

        \Korowai\Lib\Ldap\ResultEntryInterface::class => [
            'dn'                        => 'getDn',
            'attributes'                => 'getAttributes',
            'entry'                     => 'toEntry',
            'attributeIterator'         => 'getAttributeIterator',
        ],

        \Korowai\Lib\Ldap\ResultEntryIteratorInterface::class => [
        ],

        \Korowai\Lib\Ldap\ResultInterface::class => [
            'resultEntryIterator'       => 'getResultEntryIterator',
            'resultReferenceIterator'   => 'getResultReferenceIterator',
            'resultEntries'             => 'getResultEntries',
            'resultReferences'          => 'getResultReferences',
            'entries'                   => 'getEntries',
        ],

        \Korowai\Lib\Ldap\ResultItemIteratorInterface::class => [
        ],

        \Korowai\Lib\Ldap\ResultReferenceInterface::class => [
            'referrals'                 => 'getReferrals',
            'referralIterator'          => 'getReferralIterator',
        ],

        \Korowai\Lib\Ldap\ResultReferenceIteratorInterface::class => [
        ],

        \Korowai\Lib\Ldap\ResultReferralIteratorInterface::class => [
        ],

        \Korowai\Lib\Ldap\SearchingInterface::class => [
        ],

        \Korowai\Lib\Ldap\SearchQueryInterface::class => [
            'result'                    => 'getResult',
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
