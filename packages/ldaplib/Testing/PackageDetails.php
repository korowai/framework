<?php
/**
 * @file Testing/PackageDetails.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldaplib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Lib\Ldap;

use Korowai\Lib\Basic\Singleton;
use Korowai\Testing\PackageDetailsInterface;
use Korowai\Testing\Traits\PackageDetailsMemberArrays;

//  korowai\ldaplib
//
//  Korowai\Lib\Ldap
//
use Korowai\Lib\Ldap\AbstractLdap;
use Korowai\Lib\Ldap\Entry;
use Korowai\Lib\Ldap\Ldap;
//
//  Korowai\Lib\Ldap\Exception
//
use Korowai\Lib\Ldap\Exception\AttributeException;
use Korowai\Lib\Ldap\Exception\LdapException;
//
//  Korowai\Lib\Ldap\Adapter
//
use Korowai\Lib\Ldap\Adapter\AbstractAdapterFactory;
use Korowai\Lib\Ldap\Adapter\AbstractCompareQuery;
use Korowai\Lib\Ldap\Adapter\AbstractResult;
use Korowai\Lib\Ldap\Adapter\AbstractSearchQuery;
use Korowai\Lib\Ldap\Adapter\ReferralsIterationInterface;
use Korowai\Lib\Ldap\Adapter\ResultEntryToEntry;
use Korowai\Lib\Ldap\Adapter\ResultReferralIterator;

//  korowai\contracts
use Korowai\Lib\Ldap\LdapInterface;
use Korowai\Lib\Ldap\Adapter\AdapterFactoryInterface;
use Korowai\Lib\Ldap\Adapter\AdapterInterface;
use Korowai\Lib\Ldap\Adapter\CompareQueryInterface;
use Korowai\Lib\Ldap\Adapter\EntryInterface;
use Korowai\Lib\Ldap\Adapter\ResultInterface;
use Korowai\Lib\Ldap\Adapter\ResultReferralIteratorInterface;
use Korowai\Lib\Ldap\Adapter\SearchQueryInterface;

/**
 * Describes expected details or the korowai\ldaplib package.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class PackageDetails implements PackageDetailsInterface
{
    use Singleton;
    use PackageDetailsMemberArrays;

    protected $classesDetails = [
        // Korowai\Lib\Ldap
        AbstractLdap::class                 => [
            'interfaces'                    => [LdapInterface::class],
            //'properties'                    => [],
        ],
        Entry::class                        => [
            'interfaces'                    => [EntryInterface::class],
        ],
        Ldap::class                         => [
            'parent'                        => AbstractLdap::class,
            //'interfaces'                    => [],
            //'properties'                    => [],
        ],

        // Korowai\Lib\Ldap\Exception
        AttributeException::class           => [
            'parent'                        => \OutOfRangeException::class,
        ],
        LdapException::class                => [
            'parent'                        => \ErrorException::class,
        ],

        // Korowai\Lib\Ldap\Adapter
        AbstractAdapterFactory::class       => [
            'interfaces'                    => [AdapterFactoryInterface::class],
            'propreties'                    => [
                'config'                    => 'getConfig',
            ],
        ],
        AbstractCompareQuery::class         => [
            'interfaces'                    => [CompareQueryInterface::class],
            'properties'                    => [
                'dn'                        => 'getDn',
                'attribute'                 => 'getAttribute',
                'value'                     => 'getValue',
            ],
        ],
        AbstractResult::class               => [
            'interfaces'                    => [ResultInterface::class],
            'properties'                    => [
                'iterator'                  => 'getIterator',
            ],
        ],
        AbstractSearchQuery::class          => [
            'interfaces'                    => [SearchQueryInterface::class],
            'properties'                    => [
                'baseDn'                    => 'getBaseDn',
                'filter'                    => 'getFilter',
                'options'                   => 'getOptions',
            ],
        ],
        ReferralsIterationInterface::class  => [
            // TODO:
        ],
        ResultEntryToEntry::class           => [
        ],
        ResultReferralIterator::class       => [
            'interfaces'                    => [ResultReferralIteratorInterface::class],
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function classesDetails() : array
    {
        return $this->classesDetails;
    }
}

// vim: syntax=php sw=4 ts=4 et:
