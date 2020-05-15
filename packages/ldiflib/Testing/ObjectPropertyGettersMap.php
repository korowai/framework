<?php
/**
 * @file Testing/ObjectPropertyGettersMap.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Lib\Ldif;

/**
 * Defines getters map for properties of objects from korowai/ldiflib
 * package.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ObjectPropertyGettersMap
{
    /**
     * Object property getters per class for korowai/ldiflib package.
     *
     * @var array
     */
    protected static $ldiflibObjectPropertyGettersMap = [
        \Korowai\Lib\Ldif\AttrVal::class => [
        ],

        \Korowai\Lib\Ldif\Control::class => [
        ],

        \Korowai\Lib\Ldif\Cursor::class => [
        ],

        \Korowai\Lib\Ldif\Exception\InvalidChangeTypeException::class => [
        ],

        \Korowai\Lib\Ldif\Exception\InvalidModTypeException::class => [
        ],

        \Korowai\Lib\Ldif\Exception\InvalidRuleNameException::class => [
        ],

        \Korowai\Lib\Ldif\Input::class => [
            'indexMap'                  => 'getIndexMap',
            'sourceLinesMap'            => 'getSourceLinesMap',
        ],

        \Korowai\Lib\Ldif\Location::class => [
        ],

        \Korowai\Lib\Ldif\Parser::class => [
            /* TODO: */
        ],

        \Korowai\Lib\Ldif\ParserError::class => [
            'sourceLocationString'      => 'getSourceLocationString',
            'sourceLocationIndicator'   => 'getSourceLocationIndicator',
            'multilineMessageLines'     => 'getMultilineMessageLines',
        ],

        \Korowai\Lib\Ldif\ParserState::class => [
        ],

        \Korowai\Lib\Ldif\Preprocessor::class => [
        ],

        \Korowai\Lib\Ldif\Records\AbstractRecord::class => [
        ],

        \Korowai\Lib\Ldif\Records\AddRecord::class => [
        ],

        \Korowai\Lib\Ldif\Records\AttrValRecord::class => [
        ],

        \Korowai\Lib\Ldif\Records\DeleteRecord::class => [
        ],

        \Korowai\Lib\Ldif\Records\ModDnRecord::class => [
        ],

        \Korowai\Lib\Ldif\Records\ModSpec::class => [
        ],

        \Korowai\Lib\Ldif\Records\ModifyRecord::class => [
        ],

        \Korowai\Lib\Ldif\Rules\AbstractRfcRule::class => [
        ],

        \Korowai\Lib\Ldif\Rules\AttrValSpecRule::class => [
            'valueSpecRule'             => 'getValueSpecRule'
        ],

        \Korowai\Lib\Ldif\Rules\ControlRule::class => [
            'valueSpecRule'             => 'getValueSpecRule'
        ],

        \Korowai\Lib\Ldif\Rules\DnSpecRule::class => [
        ],

        \Korowai\Lib\Ldif\Rules\SepRule::class => [
        ],

        \Korowai\Lib\Ldif\Rules\Util::class => [
        ],

        \Korowai\Lib\Ldif\Rules\ValueSpecRule::class => [
        ],

        \Korowai\Lib\Ldif\Rules\VersionSpecRule::class => [
        ],

        \Korowai\Lib\Ldif\Scan::class => [
        ],

        \Korowai\Lib\Ldif\Snippet::class => [
        ],

        \Korowai\Lib\Ldif\Traits\DecoratesLocationInterface::class => [
            'location'                  => 'getLocation',
        ],

        \Korowai\Lib\Ldif\Traits\DecoratesSnippetInterface::class => [
            'snippet'                   => 'getSnippet',
        ],

        \Korowai\Lib\Ldif\Traits\DecoratesSourceLocationInterface::class => [
            'sourceLocation'            => 'getSourceLocation',
        ],

        \Korowai\Lib\Ldif\Traits\ExposesLocationInterface::class => [
            'sourceLocation'            => 'getSourceLocation',
            'string'                    => 'getString',
            'offset'                    => 'getOffset',
            'isValid'                   => 'isValid',
            'charOffset'                => 'getCharOffset',
            'input'                     => 'getInput',
            'clonedLocation'            => 'getClonedLocation',
        ],

        \Korowai\Lib\Ldif\Traits\ExposesSnippetInterface::class  => [
            'location'                  => 'getLocation',
            'length'                    => 'getLength',
            'endOffset'                 => 'getEndOffset',
            'sourceLength'              => 'getSourceLength',
            'sourceEndOffset'           => 'getSourceEndOffset',
            'sourceCharLength'          => 'getSourceCharLength',
            'sourceCharEndOffset'       => 'getSourceCharEndOffset'
        ],

        \Korowai\Lib\Ldif\Traits\ExposesSourceLocationInterface::class => [
            'fileName'                  => 'getSourceFileName',
            'sourceString'              => 'getSourceString',
            'sourceOffset'              => 'getSourceOffset',
            'sourceCharOffset'          => 'getSourceCharOffset',
            'sourceLineIndex'           => 'getSourceLineIndex',
            'sourceLine'                => 'getSourceLine',
            'sourceLineAndOffset'       => 'getSourceLineAndOffset',
            'sourceLineAndCharOffset'   => 'getSourceLineAndCharOffset',
        ],

        \Korowai\Lib\Ldif\Traits\HasAttrValSpecs::class => [
            'attrValSpecs'              => 'getAttrValSpecs'
        ],

        \Korowai\Lib\Ldif\Traits\HasParserRules::class => [
            'attrValSpecRule'           => 'attrValSpecRule',
            'dnSpecRule'                => 'dnSpecRule',
            'controlRule'               => 'controlRule',
            'sepRule'                   => 'sepRule',
            'versionSpecRule'           => 'versionSpecRule',
        ],

        \Korowai\Lib\Ldif\Util\IndexMap::class => [
            'array'                     => 'getArray',
            'increment'                 => 'getIncrement',
            'arrayCombineAlgorithm'     => 'getArrayCombineAlgorithm',
        ],

        \Korowai\Lib\Ldif\Util\IndexMapArrayCombineAlgorithm::class => [
        ],

        \Korowai\Lib\Ldif\Value::class => [
        ],

        \Korowai\Lib\Ldif\VersionSpec::class => [
        ],

        \League\Uri\Contracts\UriInterface::class => [
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
    ];

    /**
     * Returns the property getters map.
     *
     * @return array
     */
    public static function getObjectPropertyGettersMap() : array
    {
        return self::$ldiflibObjectPropertyGettersMap;
    }
}

// vim: syntax=php sw=4 ts=4 et:
