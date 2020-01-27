<?php
/**
 * @file Testing/Contracts/PackageDetails.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Contracts;

// Korowai\Lib\Basic
use Korowai\Lib\Basic\Singleton;

//
// No interfaces so far.

// Korowai\Lib\Compat
//
// No interfaces so far.

// Korowai\Lib\Context
use Korowai\Lib\Context\ContextFactoryInterface;
use Korowai\Lib\Context\ContextFactoryStackInterface;
use Korowai\Lib\Context\ContextManagerInterface;
use Korowai\Lib\Context\ExecutorInterface;

// Korowai\Lib\Error
use Korowai\Lib\Error\ErrorHandlerInterface;

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

// Korowai\Lib\Ldif
use Korowai\Lib\Ldif\CursorInterface;
use Korowai\Lib\Ldif\InputInterface;
use Korowai\Lib\Ldif\LocationInterface;
use Korowai\Lib\Ldif\ParserErrorInterface;
use Korowai\Lib\Ldif\ParserInterface;
use Korowai\Lib\Ldif\ParserStateInterface;
use Korowai\Lib\Ldif\PreprocessorInterface;
use Korowai\Lib\Ldif\RecordInterface;
use Korowai\Lib\Ldif\RecordVisitorInterface;
use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Lib\Ldif\SourceLocationInterface;
use Korowai\Lib\Ldif\Records\VersionSpecInterface;

// Korowai\Lib\Testing
//
// No interfaces so far.

use Korowai\Testing\PackageDetailsInterface;
use Korowai\Testing\Traits\PackageDetailsMemberArrays;

/**
 * Describes expected details or the korowai\contracts package.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class PackageDetails implements PackageDetailsInterface
{
    use PackageDetailsMemberArrays;
    use Singleton;

    protected $classesDetails = [
        // Korowai\Lib\Context
        ContextFactoryInterface::class      => [
            'interfaces'                    => [],
            'properties'                    => [],
        ],
        ContextFactoryStackInterface::class => [
            'interfaces'                    => [],
            'properties'                    => [
                'size'                      => 'size',
                'top'                       => 'top',
            ],
        ],
        ContextManagerInterface::class      => [
            'interfaces'                    => [],
            'properties'                    => [],
        ],
        ExecutorInterface::class            => [
            'interfaces'                    => [],
            'properties'                    => [],
        ],

        // Korowai\Lib\Error
        ErrorHandlerInterface::class        => [
            'interfaces'                    => [],
            'properties'                    => [
                'errorTypes'                => 'getErrorTypes'
            ],
        ],

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

        // Korowai\Lib\Ldif
        CursorInterface::class              => [
            'interfaces'                    => [LocationInterface::class],
            'properties'                    => [],
        ],
        InputInterface::class               => [
            'interfaces'                    => [],
            'properties'                    => [
                'sourceFileName'            => 'getSourceFileName',
                'sourceString'              => 'getSourceString',
                'string'                    => 'getString',
                'sourceLines'               => 'getSourceLines',
                'sourceLinesCount'          => 'getSourceLinesCount',
            ],
        ],
        LocationInterface::class            => [
            'interfaces'                    => [SourceLocationInterface::class],
            'properties'                    => [
                'string'                    => 'getString',
                'offset'                    => 'getOffset',
                'charOffset'                => 'getCharOffset',
            ],
        ],
        ParserErrorInterface::class         => [
            'interfaces'                    => [SourceLocationInterface::class, \Throwable::class],
            'properties'                    => [
                'multilineMessage'          => 'getMultilineMessage'
            ],
        ],
        ParserInterface::class              => [
            'interfaces'                    => [],
            'properties'                    => [],
        ],
        ParserStateInterface::class         => [
            'interfaces'                    => [],
            'properties'                    => [
                'cursor'                    => 'getCursor',
                'errors'                    => 'getErrors',
                'records'                   => 'getRecords',
                'isOk'                      => 'isOk'
            ],
        ],
        PreprocessorInterface::class        => [
            'interfaces'                    => [],
            'properties'                    => [],
        ],
        RecordInterface::class              => [
            'interfaces'                    => [SnippetInterface::class],
            'properties'                    => [],
        ],
        RecordVisitorInterface::class       => [
            'interfaces'                    => [],
            'properties'                    => [],
        ],
        SnippetInterface::class             => [
            'interfaces'                    => [LocationInterface::class],
            'properties'                    => [
                'length'                    => 'getLength',
                'endOffset'                 => 'getEndOffset',
                'sourceLength'              => 'getSourceLength',
                'sourceEndOffset'           => 'getSourceEndOffset',
                'sourceCharLength'          => 'getSourceCharLength',
                'sourceCharEndOffset'       => 'getSourceCharEndOffset',
            ],
        ],
        SourceLocationInterface::class      => [
            'interfaces'                    => [],
            'properties'                    => [
                'sourceFileName'            => 'getSourceFileName',
                'sourceString'              => 'getSourceString',
                'sourceOffset'              => 'getSourceOffset',
                'sourceCharOffset'          => 'getSourceCharOffset',
                'sourceLineIndex'           => 'getSourceLineIndex',
                'sourceLineIndex'           => 'getSourceLine',
                'sourceLineAndOffset'       => 'getSourceLineAndOffset',
                'sourceLineAndCharOffset'   => 'getSourceLineAndCharOffset',
            ]
        ],
        VersionSpecInterface::class         => [
            'interfaces'                    => [RecordInterface::class],
            'properties'                    => [
                'version'                   => 'getVersion',
            ]
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
