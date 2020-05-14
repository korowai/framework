<?php
/**
 * @file Testing/Contracts/ObjectPropertyGettersMap.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Contracts;

/**
 * Defines getters map for properties of interfaces from korowai/contracts
 * package.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ObjectPropertyGettersMap
{
    /**
     * Object property getters per class for korowai/contracts package.
     *
     * @var array
     */
    protected static $contractsObjectPropertyGettersMap = [
        \Iterator::class => [
            'current'                   => 'current'
        ],

        \Korowai\Lib\Basic\SingletonInterface::class => [
            'instance'                  => 'getInstance',
        ],

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

        \Korowai\Lib\Error\ErrorHandlerInterface::class => [
            'errorTypes'                => 'getErrorTypes',
        ],

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
            'entries'                   => 'getEntries'
        ],

        \Korowai\Lib\Ldap\Adapter\ResultRecordInterface::class => [
            'dn'                        => 'getDn'
        ],

        \Korowai\Lib\Ldap\Adapter\ResultReferenceInterface::class => [
            'referrals'                 => 'getReferrals',
        ],

        \Korowai\Lib\Ldap\Adapter\ResultReferenceIteratorInterface::class => [
        ],

        \Korowai\Lib\Ldap\Adapter\ResultReferralIteratorInterface::class => [
        ],

        \Korowai\Lib\Ldap\Adapter\SearchQueryInterface::class => [
            'result'                    => 'getResult',
        ],

        \Korowai\Lib\Ldap\EntryInterface::class => [
            'dn'                        => 'getDn',
            'attributes'                => 'getAttributes',
        ],

        \Korowai\Lib\Ldap\LdapInterface::class => [
            'adapter'                   => 'getAdapter',
        ],

        \Korowai\Lib\Ldif\AttrModInterface::class => [
            'attribute'                 => 'getAttribute',
            'valueObject'               => 'getValueObject'
        ],

        \Korowai\Lib\Ldif\AttrValInterface::class => [
            'attribute'                 => 'getAttribute',
            'valueObject'               => 'getValueObject',
        ],

        \Korowai\Lib\Ldif\ControlInterface::class => [
            'oid'                       => 'getOid',
            'criticality'               => 'getCriticality',
            'valueObject'               => 'getValueObject',
        ],

        \Korowai\Lib\Ldif\CursorInterface::class => [
        ],

        \Korowai\Lib\Ldif\InputInterface::class => [
            'sourceString'              => 'getSourceString',
            'string'                    => 'getString',
            'fileName'                  => 'getSourceFileName',
            'toString'                  => '__toString',
            'sourceLines'               => 'getSourceLines',
            'sourceLinesCount'          => 'getSourceLinesCount',
        ],

        \Korowai\Lib\Ldif\LocationInterface::class => [
            'string'                    => 'getString',
            'offset'                    => 'getOffset',
            'isValid'                   => 'isValid',
            'charOffset'                => 'getCharOffset',
            'input'                     => 'getInput',
            'clonedLocation'            => 'getClonedLocation',
        ],

        \Korowai\Lib\Ldif\ParserErrorInterface::class => [
            'multilineMessage'          => 'getMultilineMessage'
        ],

        \Korowai\Lib\Ldif\ParserInterface::class => [
            /* TODO: */
        ],

        \Korowai\Lib\Ldif\ParserStateInterface::class => [
            'cursor'                    => 'getCursor',
            'errors'                    => 'getErrors',
            'records'                   => 'getRecords',
            'isOk'                      => 'isOk',
        ],

        \Korowai\Lib\Ldif\PreprocessorInterface::class => [
        ],

        \Korowai\Lib\Ldif\RecordInterface::class => [
            'dn'                        => 'getDn',
        ],

        \Korowai\Lib\Ldif\RecordVisitorInterface::class => [
        ],

        \Korowai\Lib\Ldif\Records\AddRecordInterface::class => [
        ],

        \Korowai\Lib\Ldif\Records\AttrValRecordInterface::class => [
        ],

        \Korowai\Lib\Ldif\Records\AttrValSpecsInterface::class => [
            'attrValSpecs'              => 'getAttrValSpecs'
        ],

        \Korowai\Lib\Ldif\Records\ChangeRecordInterface::class => [
            'changeType'                => 'getChangeType'
        ],

        \Korowai\Lib\Ldif\Records\DeleteRecordInterface::class => [
        ],

        \Korowai\Lib\Ldif\Records\ModDnRecordInterface::class => [
            'newRdn'                    => 'getNewRdn',
            'deleteOldRdn'              => 'getDeleteOldRdn',
            'newSuperior'               => 'getNewSuperior',
        ],

        \Korowai\Lib\Ldif\Records\ModSpecInterface::class => [
            'modType'                   => 'getModType',
            'attribute'                 => 'getAttribute'
        ],

        \Korowai\Lib\Ldif\Records\ModifyRecordInterface::class => [
            'attribute'                 => 'getAttribute',
            'modSpecs'                  => 'getModSpecs'
        ],

        \Korowai\Lib\Ldif\RuleInterface::class => [
        ],

        \Korowai\Lib\Ldif\SnippetInterface::class => [
            'length'                    => 'getLength',
            'endOffset'                 => 'getEndOffset',
            'sourceLength'              => 'getSourceLength',
            'sourceEndOffset'           => 'getSourceEndOffset',
            'sourceCharLength'          => 'getSourceCharLength',
            'sourceCharEndOffset'       => 'getSourceCharEndOffset'
        ],

        \Korowai\Lib\Ldif\SourceLocationInterface::class => [
            'fileName'                  => 'getSourceFileName',
            'sourceString'              => 'getSourceString',
            'sourceOffset'              => 'getSourceOffset',
            'sourceCharOffset'          => 'getSourceCharOffset',
            'sourceLineIndex'           => 'getSourceLineIndex',
            'sourceLine'                => 'getSourceLine',
            'sourceLineAndOffset'       => 'getSourceLineAndOffset',
            'sourceLineAndCharOffset'   => 'getSourceLineAndCharOffset',
        ],

        \Korowai\Lib\Ldif\ValueInterface::class => [
            'type'                      => 'getType',
            'spec'                      => 'getSpec',
            'content'                   => 'getContent'
        ],

        \Korowai\Lib\Ldif\VersionSpecInterface::class => [
            'version'                   => 'getVersion',
        ],

        \Korowai\Lib\Rfc\RuleInterface::class => [
            'name'                      => 'name',
            'toString'                  => '__toString',
            'regex'                     => 'regexp',
            'captures'                  => 'captures',
            'errorCaptures'             => 'errorCaptures',
            'valueCaptures'             => 'valueCaptures',
            'isOptional'                => 'isOptional'
        ],

        \Korowai\Lib\Rfc\StaticRuleSetInterface::class => [
            'rules'                     => 'rules',
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
        return self::$contractsObjectPropertyGettersMap;
    }
}

// vim: syntax=php sw=4 ts=4 et: