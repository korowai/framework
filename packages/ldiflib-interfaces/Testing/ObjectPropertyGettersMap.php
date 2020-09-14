<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\LdiflibInterfaces;

/**
 * @todo Write documentation
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ObjectPropertyGettersMap
{
    /**
     * Object property getters per class for korowai/ldiflib-interfaces package.
     *
     * @var array
     */
    private static $objectPropertyGettersMap = [
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

        \Korowai\Lib\Ldif\NodeInterface::class => [
            'snippet'                   => 'getSnippet',
        ],

        \Korowai\Lib\Ldif\Nodes\LdifAddRecordInterface::class => [
        ],

        \Korowai\Lib\Ldif\Nodes\AttrValSpecInterface::class => [
            'attribute'                 => 'getAttribute',
            'valueSpec'                 => 'getValueSpec',
        ],

        \Korowai\Lib\Ldif\Nodes\ControlInterface::class => [
            'oid'                       => 'getOid',
            'criticality'               => 'getCriticality',
            'valueSpec'                 => 'getValueSpec',
        ],

        \Korowai\Lib\Ldif\Nodes\LdifDeleteRecordInterface::class => [
        ],

        \Korowai\Lib\Ldif\Nodes\DnSpecInterface::class => [
            'dn'                        => 'getDn',
        ],

        \Korowai\Lib\Ldif\Nodes\HasAttrValSpecsInterface::class => [
            'attrValSpecs'              => 'getAttrValSpecs',
        ],

        \Korowai\Lib\Ldif\Nodes\LdifAttrValRecordInterface::class => [
        ],

        \Korowai\Lib\Ldif\Nodes\LdifChangeRecordInterface::class => [
            'changeType'                => 'getChangeType',
            'controls'                  => 'getControls',
        ],

        \Korowai\Lib\Ldif\Nodes\LdifModDnRecordInterface::class => [
            'newRdn'                    => 'getNewRdn',
            'deleteOldRdn'              => 'getDeleteOldRdn',
            'newSuperior'               => 'getNewSuperior',
        ],

        \Korowai\Lib\Ldif\Nodes\LdifModifyRecordInterface::class => [
            'modSpecs'                  => 'getModSpecs',
        ],

        \Korowai\Lib\Ldif\Nodes\ModSpecInterface::class => [
            'modType'                   => 'getModType',
            'attribute'                 => 'getAttribute',
        ],

        \Korowai\Lib\Ldif\Nodes\ValueSpecInterface::class => [
            'type'                      => 'getType',
            'spec'                      => 'getSpec',
            'content'                   => 'getContent',
        ],

        \Korowai\Lib\Ldif\Nodes\VersionSpecInterface::class => [
            'version'                   => 'getVersion',
        ],

        \Korowai\Lib\Ldif\NodeVisitorInterface::class => [
        ],

        \Korowai\Lib\Ldif\ParserErrorInterface::class => [
            'multilineMessage'          => 'getMultilineMessage',
        ],

        \Korowai\Lib\Ldif\ParserInterface::class => [
            /* TODO: */
        ],

        \Korowai\Lib\Ldif\ParserStateInterface::class => [
            'cursor'                    => 'getCursor',
            'errors'                    => 'getErrors',
            'isOk'                      => 'isOk',
        ],

        \Korowai\Lib\Ldif\PreprocessorInterface::class => [
        ],

        \Korowai\Lib\Ldif\RecordInterface::class => [
            'dn'                        => 'getDn',
        ],

        \Korowai\Lib\Ldif\RecordVisitorInterface::class => [
        ],

        \Korowai\Lib\Ldif\RuleInterface::class => [
        ],

        \Korowai\Lib\Ldif\Rules\AttrValSpecRuleInterface::class => [
            'valueSpecRule'             => 'getValueSpecRule',
        ],

        \Korowai\Lib\Ldif\Rules\ChangeRecordInitRuleInterface::class => [
        ],

        \Korowai\Lib\Ldif\Rules\ControlRuleInterface::class => [
        ],

        \Korowai\Lib\Ldif\Rules\DnSpecRuleInterface::class => [
        ],

        \Korowai\Lib\Ldif\Rules\LdifAttrValRecordRuleInterface::class => [
            'dnSpecRule'                => 'getDnSpecRule',
            'sepRule'                   => 'getSepRule',
            'attrValSpecRule'           => 'getAttrValSpecRule',
        ],

        \Korowai\Lib\Ldif\Rules\LdifChangeRecordRuleInterface::class => [
            'dnSpecRule'                => 'getDnSpecRule',
            'sepRule'                   => 'getSepRule',
            'controlRule'               => 'getControlRule',
            'changeRecordInitRule'      => 'getChangeRecordInitRule',
        ],

        \Korowai\Lib\Ldif\Rules\LdifChangesRuleInterface::class => [
            'versionSpecRule'           => 'getVersionSpecRule',
            'sepRule'                   => 'getSepRule',
            'ldifChangeRecordRule'      => 'getLdifChangeRecordRule',
        ],

        \Korowai\Lib\Ldif\Rules\LdifContentRuleInterface::class => [
            'versionSpecRule'           => 'getVersionSpecRule',
            'sepRule'                   => 'getSepRule',
            'ldifAttrValRecordRule'     => 'getLdifAttrValRecordRule',
        ],

        \Korowai\Lib\Ldif\Rules\LdifFileRuleInterface::class => [
            'ldifContentRule'           => 'getLdifContentRule',
            'ldifChangesRule'           => 'getLdifChangesRule',
        ],

        \Korowai\Lib\Ldif\Rules\ModSpecRuleInterface::class => [
            'modSpecInitRule'           => 'getModSpecInitRule',
            'sepRule'                   => 'getSepRule',
            'attrValSpecRule'           => 'getAttrValSpecRule',
        ],

        \Korowai\Lib\Ldif\Rules\ModSpecInitRuleInterface::class => [
        ],

        \Korowai\Lib\Ldif\Rules\NewRdnRuleInterface::class => [
        ],

        \Korowai\Lib\Ldif\Rules\NewSuperiorRuleInterface::class => [
        ],

        \Korowai\Lib\Ldif\Rules\SepRuleInterface::class => [
        ],

        \Korowai\Lib\Ldif\Rules\ValueSpecRuleInterface::class => [
        ],

        \Korowai\Lib\Ldif\Rules\VersionSpecRuleInterface::class => [
        ],

        \Korowai\Lib\Ldif\SnippetInterface::class => [
            'length'                    => 'getLength',
            'endOffset'                 => 'getEndOffset',
            'sourceLength'              => 'getSourceLength',
            'sourceEndOffset'           => 'getSourceEndOffset',
            'sourceCharLength'          => 'getSourceCharLength',
            'sourceCharEndOffset'       => 'getSourceCharEndOffset',
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
