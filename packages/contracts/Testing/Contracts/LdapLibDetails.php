<?php
/**
 * @file Testing/Contracts/LdapLibDetails.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Contracts;

use Korowai\Lib\Basic\Singleton;
use Korowai\Testing\PackageDetailsInterface;
use Korowai\Testing\Traits\PackageDetailsMemberArrays;

// Korowai\Lib\Ldap
use Korowai\Lib\Ldap\EntryInterface;
use Korowai\Lib\Ldap\LdapInterface;
use Korowai\Lib\Ldap\Adapter\AdapterFactoryInterface;
use Korowai\Lib\Ldap\Adapter\AdapterInterface;
use Korowai\Lib\Ldap\Adapter\BindingInterface;
use Korowai\Lib\Ldap\Adapter\CompareQueryInterface;
use Korowai\Lib\Ldap\Adapter\EntryManagerInterface;
use Korowai\Lib\Ldap\Adapter\ResultAttributeIteratorInterface;
use Korowai\Lib\Ldap\Adapter\ResultEntryInterface;
use Korowai\Lib\Ldap\Adapter\ResultEntryIteratorInterface;
use Korowai\Lib\Ldap\Adapter\ResultInterface;
use Korowai\Lib\Ldap\Adapter\ResultRecordInterface;
use Korowai\Lib\Ldap\Adapter\ResultReferenceInterface;
use Korowai\Lib\Ldap\Adapter\ResultReferenceIteratorInterface;
use Korowai\Lib\Ldap\Adapter\ResultReferralIteratorInterface;
use Korowai\Lib\Ldap\Adapter\SearchQueryInterface;

/**
 * Describes expected details or the korowai\contracts package.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LdapLibDetails implements PackageDetailsInterface
{
    use PackageDetailsMemberArrays;
    use Singleton;

    protected $classesDetails = [
        // Korowai\Lib\Ldap
        EntryInterface::class               => [
            'interfaces'                    => [],
            'properties'                    => [
                'dn'                        => 'getDn',
                'attributes'                => 'getAttributes',
            ],
        ],
        LdapInterface::class                => [
            'interfaces'                    => [
                BindingInterface::class,
                EntryManagerInterface::class,
                AdapterInterface::class,
            ],
            'properties'                    => [
                'adapter'                   => 'getAdapter',
            ],
        ],
        AdapterFactoryInterface::class      => [
            'interfaces'                    => [],
            'properties'                    => [],
        ],
        AdapterInterface::class             => [
            'interfaces'                    => [],
            'properties'                    => [
                'binding'                   => 'getBinding',
                'entryManager'              => 'getEntryManager',
            ],
        ],
        BindingInterface::class             => [
            'interfaces'                    => [],
            'properties'                    => [
                'isBound'                   => 'isBound',
            ],
        ],
        CompareQueryInterface::class        => [
            'interfaces'                    => [],
            'properties'                    => [
                'result'                    => 'getResult',
            ],
        ],
        EntryManagerInterface::class        => [
            'interfaces'                    => [],
            'properties'                    => [],
        ],
        ResultAttributeIteratorInterface::class => [
            'interfaces'                    => [\Iterator::class],
            'properties'                    => [],
        ],
        ResultEntryInterface::class         => [
            'interfaces'                    => [ResultRecordInterface::class],
            'properties'                    => [
                'attributes'                => 'getAttributes',
                'entry'                     => 'toEntry',
                'attributeIterator'         => 'getAttributeIterator',
            ],
        ],
        ResultEntryIteratorInterface::class => [
            'interfaces'                    => [\Iterator::class],
            'properties'                    => [],
        ],
        ResultInterface::class              => [
            'interfaces'                    => [\IteratorAggregate::class],
            'properties'                    => [
                'resultEntryIterator'       => 'getResultEntryIterator',
                'resultReferenceIterator'   => 'getResultReferenceIterator',
                'resultEntries'             => 'getResultEntries',
                'resultReferences'          => 'getResultReferences',
                'entries'                   => 'getEntries',
            ],
        ],
        ResultRecordInterface::class        => [
            'interfaces'                    => [],
            'properties'                    => [
                'dn'                        => 'getDn',
            ],
        ],
        ResultReferenceInterface::class     => [
            'interfaces'                    => [ResultRecordInterface::class],
            'properties'                    => [
                'referrals'                 => 'getReferrals',
            ],
        ],
        ResultReferenceIteratorInterface::class => [
            'interfaces'                    => [\Iterator::class],
            'properties'                    => [],
        ],
        ResultReferralIteratorInterface::class => [
            'interfaces'                    => [\Iterator::class],
            'properties'                    => [],
        ],
        SearchQueryInterface::class         => [
            'interfaces'                    => [],
            'properties'                    => [
                'result'                    => 'getResult'
            ],
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
