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

/**
 * Describes expected details or the korowai\contracts package.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class PackageDetails implements PackageDetailsInterface
{
    protected static $objectProperties = [
        // Korowai\Lib\Context
        ContextFactoryInterface::class  => [
        ],
        ContextFactoryStackInterface::class => [
            'size'                      => 'size',
            'top'                       => 'top',
        ],
        ContextManagerInterface::class  => [
        ],
        ExecutorInterface::class        => [
        ],

        // Korowai\Lib\Error
        ErrorHandlerInterface::class    => [
            'errorTypes'                => 'getErrorTypes'
        ],

        // Korowai\Lib\Ldap
        EntryInterface::class           => [
            'dn'                        => 'getDn',
            'attributes'                => 'getAttributes',
        ],
        LdapInterface::class            => [
            'adapter'                   => 'getAdapter',
        ],
        AdapterFactoryInterface::class  => [
        ],
        AdapterInterface::class         => [
            'binding'                   => 'getBinding',
            'entryManager'              => 'getEntryManager',
        ],
        BindingInterface::class         => [
            'isBound'                   => 'isBound',
        ],
        CompareQueryInterface::class    => [
            'result'                    => 'getResult',
        ],
        EntryManagerInterface::class    => [
        ],
        ResultAttributeIteratorInterface::class => [
        ],
        ResultEntryInterface::class     => [
            'attributes'                => 'getAttributes',
            'entry'                     => 'toEntry',
            'attributeIterator'         => 'getAttributeIterator',
        ],
        ResultEntryIteratorInterface::class => [
        ],
        ResultInterface::class          => [
            'resultEntryIterator'       => 'getResultEntryIterator',
            'resultReferenceIterator'   => 'getResultReferenceIterator',
            'resultEntries'             => 'getResultEntries',
            'resultReferences'          => 'getResultReferences',
            'entries'                   => 'getEntries',
        ],
        ResultRecordInterface::class    => [
            'dn'                        => 'getDn',
        ],
        ResultReferenceInterface::class => [
            'referrals'                 => 'getReferrals',
        ],
        ResultReferenceIteratorInterface::class => [
        ],
        ResultReferralIteratorInterface::class => [
        ],
        SearchQueryInterface::class     => [
            'result'                    => 'getResult'
        ],

        // Korowai\Lib\Ldif
        CursorInterface::class => [
        ],
        InputInterface::class           => [
            'sourceFileName'            => 'getSourceFileName',
            'sourceString'              => 'getSourceString',
            'string'                    => 'getString',
            'sourceLines'               => 'getSourceLines',
            'sourceLinesCount'          => 'getSourceLinesCount',
        ],
        LocationInterface::class        => [
            'string'                    => 'getString',
            'offset'                    => 'getOffset',
            'charOffset'                => 'getCharOffset',
        ],
        ParserErrorInterface::class     => [
            'multilineMessage'          => 'getMultilineMessage'
        ],
        ParserInterface::class          => [
        ],
        ParserStateInterface::class     => [
            'cursor'                    => 'getCursor',
            'errors'                    => 'getErrors',
            'records'                   => 'getRecords',
            'isOk'                      => 'isOk'
        ],
        PreprocessorInterface::class    => [
        ],
        RecordInterface::class          => [
        ],
        RecordVisitorInterface::class   => [
        ],
        SnippetInterface::class         => [
            'length'                    => 'getLength',
            'endOffset'                 => 'getEndOffset',
            'sourceLength'              => 'getSourceLength',
            'sourceEndOffset'           => 'getSourceEndOffset',
            'sourceCharLength'          => 'getSourceCharLength',
            'sourceCharEndOffset'       => 'getSourceCharEndOffset',
        ],
        SourceLocationInterface::class  => [
            'sourceFileName'            => 'getSourceFileName',
            'sourceString'              => 'getSourceString',
            'sourceOffset'              => 'getSourceOffset',
            'sourceCharOffset'          => 'getSourceCharOffset',
            'sourceLineIndex'           => 'getSourceLineIndex',
            'sourceLineIndex'           => 'getSourceLine',
            'sourceLineAndOffset'       => 'getSourceLineAndOffset',
            'sourceLineAndCharOffset'   => 'getSourceLineAndCharOffset',
        ],
        VersionSpecInterface::class     => [
            'version'                   => 'getVersion'
        ],
    ];

    protected static $interfaceInheritance = [
        // Korowai\Lib\Context
        ContextFactoryInterface::class  => [],
        ContextFactoryStackInterface::class => [],
        ContextManagerInterface::class  => [],
        ExecutorInterface::class        => [],

        // Korowai\Lib\Error
        ErrorHandlerInterface::class    => [],

        // Korowai\Lib\Ldap
        EntryInterface::class           => [],
        LdapInterface::class            => [
            BindingInterface::class,
            EntryManagerInterface::class,
            AdapterInterface::class,
        ],
        AdapterFactoryInterface::class  => [],
        AdapterInterface::class         => [],
        BindingInterface::class         => [],
        CompareQueryInterface::class    => [],
        EntryManagerInterface::class    => [],
        ResultAttributeIteratorInterface::class => [\Iterator::class],
        ResultEntryInterface::class     => [ResultRecordInterface::class],
        ResultEntryIteratorInterface::class => [\Iterator::class],
        ResultInterface::class          => [\IteratorAggregate::class],
        ResultRecordInterface::class    => [],
        ResultReferenceInterface::class => [ResultRecordInterface::class],
        ResultReferenceIteratorInterface::class => [\Iterator::class],
        ResultReferralIteratorInterface::class => [\Iterator::class],
        SearchQueryInterface::class     => [],

        // Korowai\Lib\Ldif
        CursorInterface::class          => [LocationInterface::class],
        InputInterface::class           => [],
        LocationInterface::class        => [SourceLocationInterface::class],
        ParserErrorInterface::class     => [SourceLocationInterface::class, \Throwable::class],
        ParserInterface::class          => [],
        ParserStateInterface::class     => [],
        PreprocessorInterface::class    => [],
        RecordInterface::class          => [SnippetInterface::class],
        RecordVisitorInterface::class   => [],
        SnippetInterface::class         => [LocationInterface::class],
        SourceLocationInterface::class  => [],
        VersionSpecInterface::class     => [RecordInterface::class],
    ];

    /**
     * {@inheritdoc}
     */
    public static function objectProperties() : array
    {
        return static::$objectProperties;
    }

    /**
     * {@inheritdoc}
     */
    public static function interfaceInheritance() : array
    {
        return static::$interfaceInheritance;
    }
}

// vim: syntax=php sw=4 ts=4 et:
