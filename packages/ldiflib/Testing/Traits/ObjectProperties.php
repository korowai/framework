<?php
/**
 * @file Testing/Traits/ObjectProperties.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\CursorInterface;
use Korowai\Lib\Ldif\LocationInterface;
use Korowai\Lib\Ldif\ParserErrorInterface;
use Korowai\Lib\Ldif\ParserStateInterface;
use Korowai\Lib\Ldif\RecordInterface;
use Korowai\Lib\Ldif\Records\AttrValSpecsInterface;
use Korowai\Lib\Ldif\Records\AttrValRecordInterface;
use Korowai\Lib\Ldif\Records\ChangeRecordInterface;
use Korowai\Lib\Ldif\Records\AddRecordInterface;
use Korowai\Lib\Ldif\Records\DeleteRecordInterface;
use Korowai\Lib\Ldif\Records\ModDnRecordInterface;
use Korowai\Lib\Ldif\Records\ModifyRecordInterface;
use Korowai\Lib\Ldif\Records\ModSpecInterface;
use Korowai\Lib\Ldif\Traits\DecoratesLocationInterface;
use Korowai\Lib\Ldif\Traits\DecoratesSnippetInterface;
use Korowai\Lib\Ldif\Traits\DecoratesSourceInterface;
use Korowai\Lib\Ldif\Traits\ExposesLocationInterface;
use Korowai\Lib\Ldif\Traits\ExposesSnippetInterface;
use Korowai\Lib\Ldif\Traits\ExposesSourceInterface;
use Korowai\Lib\Ldif\Traits\HasAttrValSpecs;
use Korowai\Lib\Ldif\Traits\HasParserRules;
use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Lib\Ldif\ValueInterface;
use Korowai\Lib\Ldif\AttrValInterface;
use Korowai\Lib\Ldif\ControlInterface;
use Korowai\Lib\Ldif\SourceLocationInterface;
use League\Uri\Contracts\UriInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ObjectProperties
{
    /**
     * Object property getters per class for korowai/ldiflib package.
     *
     * @var array
     */
    protected static $ldiflibObjectPropertyGettersMap = [
        SourceLocationInterface::class  => [
            'fileName'                  => 'getSourceFileName',
            'sourceString'              => 'getSourceString',
            'sourceOffset'              => 'getSourceOffset',
            'sourceCharOffset'          => 'getSourceCharOffset',
            'sourceLineIndex'           => 'getSourceLineIndex',
            'sourceLine'                => 'getSourceLine',
            'sourceLineAndOffset'       => 'getSourceLineAndOffset',
            'sourceLineAndCharOffset'   => 'getSourceLineAndCharOffset',
        ],

        LocationInterface::class        => [
            'string'                    => 'getString',
            'offset'                    => 'getOffset',
            'isValid'                   => 'isValid',
            'charOffset'                => 'getCharOffset',
            'input'                     => 'getInput',
            'clonedLocation'            => 'getClonedLocation',
        ],

        CursorInterface::class          => [
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

        ParserErrorInterface::class     => [
            'multilineMessage'          => 'getMultilineMessage'
        ],

        ParserStateInterface::class     => [
            'cursor'                    => 'getCursor',
            'errors'                    => 'getErrors',
            'records'                   => 'getRecords',
            'isOk'                      => 'isOk',
        ],

        SnippetInterface::class         => [
            'length'                    => 'getLength',
            'endOffset'                 => 'getEndOffset',
            'sourceLength'              => 'getSourceLength',
            'sourceEndOffset'           => 'getSourceEndOffset',
            'sourceCharLength'          => 'getSourceCharLength',
            'sourceCharEndOffset'       => 'getSourceCharEndOffset'
        ],

        AttrValInterface::class         => [
            'attribute'                 => 'getAttribute',
            'valueObject'               => 'getValueObject',
        ],

        ControlInterface::class         => [
            'oid'                       => 'getOid',
            'criticality'               => 'getCriticality',
            'valueObject'               => 'getValueObject',
        ],

        ValueInterface::class           => [
            'type'                      => 'getType',
            'spec'                      => 'getSpec',
            'content'                   => 'getContent'
        ],

        UriInterface::class             => [
            'string'                    => '__toString',
            'scheme'                    => 'getScheme',
            'authority'                 => 'getAuthority',
            'userinfo'                  => 'getUserInfo',
            'host'                      => 'getHost',
            'port'                      => 'getPort',
            'path'                      => 'getPath',
            'query'                     => 'getQuery',
            'fragment'                  => 'getFragment',
        ],

        RecordInterface::class          => [
            'dn'                        => 'getDn',
        ],

        AttrValSpecsInterface::class    => [
            'attrValSpecs'              => 'getAttrValSpecs'
        ],

        ChangeRecordInterface::class    => [
            'changeType'                => 'getChangeType'
        ],

        AddRecordInterface::class       => [
        ],

        ModDnRecordInterface::class     => [
            'newRdn'                    => 'getNewRdn',
            'deleteOldRdn'              => 'getDeleteOldRdn',
            'newSuperior'               => 'getNewSuperior',
        ],

        ModifyRecordInterface::class    => [
            'attribute'                 => 'getAttribute',
            'modSpecs'                  => 'getModSpecs'
        ],

        ModSpecInterface::class         => [
            'modType'                   => 'getModType',
            'attribute'                 => 'getAttribute'
        ],

        ExposesSourceLocationInterface::class => [
            'fileName'                  => 'getSourceFileName',
            'sourceString'              => 'getSourceString',
            'sourceOffset'              => 'getSourceOffset',
            'sourceCharOffset'          => 'getSourceCharOffset',
            'sourceLineIndex'           => 'getSourceLineIndex',
            'sourceLine'                => 'getSourceLine',
            'sourceLineAndOffset'       => 'getSourceLineAndOffset',
            'sourceLineAndCharOffset'   => 'getSourceLineAndCharOffset',
        ],

        ExposesLocationInterface::class => [
            'sourceLocation'            => 'getSourceLocation',
            'string'                    => 'getString',
            'offset'                    => 'getOffset',
            'isValid'                   => 'isValid',
            'charOffset'                => 'getCharOffset',
            'input'                     => 'getInput',
            'clonedLocation'            => 'getClonedLocation',
        ],

        ExposesSnippetInterface::class  => [
            'length'                    => 'getLength',
            'endOffset'                 => 'getEndOffset',
            'sourceLength'              => 'getSourceLength',
            'sourceEndOffset'           => 'getSourceEndOffset',
            'sourceCharLength'          => 'getSourceCharLength',
            'sourceCharEndOffset'       => 'getSourceCharEndOffset'
        ],

        DecoratesSourceLocationInterface::class => [
            'sourceLocation'            => 'getSourceLocation',
        ],

        DecoratesLocationInterface::class => [
            'location'                  => 'getLocation',
        ],

        DecoratesSnippetInterface::class => [
            'snippet'                   => 'getSnippet',
        ],

        HasAttrValSpecs::class          => [
            'attrValSpecs'              => 'getAttrValSpecs'
        ],

        HasParserRules::class           => [
            'attrValSpecRule'           => 'attrValSpecRule',
            'dnSpecRule'                => 'dnSpecRule',
            'sepRule'                   => 'sepRule',
            'versionSpecRule'           => 'versionSpecRule',
        ],
    ];
}

// vim: syntax=php sw=4 ts=4 et:
