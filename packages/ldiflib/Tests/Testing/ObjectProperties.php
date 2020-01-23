<?php
/**
 * @file Tests/SourceLocationAssertions.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Testing;

use Korowai\Lib\Ldif\CursorInterface;
use Korowai\Lib\Ldif\InputInterface;
use Korowai\Lib\Ldif\LocationInterface;
use Korowai\Lib\Ldif\ParserErrorInterface;
use Korowai\Lib\Ldif\ParserInterface;
use Korowai\Lib\Ldif\ParserStateInterface;
use Korowai\Lib\Ldif\PreprocessorInterface;
use Korowai\Lib\Ldif\RecordInterface;
use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Lib\Ldif\SourceLocationInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ObjectProperties
{
    protected static $ldiflibPropertyGetters = [
        CursorInterface::class => [
        ],
        InputInterface::class => [
            'sourceFileName'            => 'getSourceFileName',
            'sourceString'              => 'getSourceString',
            'string'                    => 'getString',
            'sourceLines'               => 'getSourceLines',
            'sourceLinesCount'          => 'getSourceLinesCount',
        ],
        LocationInterface::class => [
            'string'                    => 'getString',
            'offset'                    => 'getOffset',
            'charOffset'                => 'getCharOffset',
        ],
        ParserErrorInterface::class => [
            'multilineMessage'          => 'getMultilineMessage'
        ],
        ParserInterface::class => [
        ],
        ParserStateInterface::class => [
            'cursor'                    => 'getCursor',
            'errors'                    => 'getErrors',
            'records'                   => 'getRecords',
            'isOk'                      => 'isOk'
        ],
        PreprocessorInterface::class =>   [
        ],
        RecordInterface::class => [
        ],
        RecordVisistorInterface::class => [
        ],
        SnippetInterface::class => [
            'length'                    => 'getLength',
            'endOffset'                 => 'getEndOffset',
            'sourceLength'              => 'getSourceLength',
            'sourceEndOffset'           => 'getSourceEndOffset',
            'sourceCharLength'          => 'getSourceCharLength',
            'sourceCharEndOffset'       => 'getSourceCharEndOffset',
        ],
        SourceLocationInterface::class => [
            'sourceFileName'            => 'getSourceFileName',
            'sourceString'              => 'getSourceString',
            'sourceOffset'              => 'getSourceOffset',
            'sourceCharOffset'          => 'getSourceCharOffset',
            'sourceLineIndex'           => 'getSourceLineIndex',
            'sourceLineIndex'           => 'getSourceLine',
            'sourceLineAndOffset'       => 'getSourceLineAndOffset',
            'sourceLineAndCharOffset'   => 'getSourceLineAndCharOffset',
        ],
    ];

    protected static $ldiflibInheritedInterfaces = [
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
    ];

//    /**
//     * Returns plain array of intherited interface names.
//     *
//     * @param  string $class
//     * @return array
//     */
//    protected static function getLdifInheritedInterfaces(string $class) : array
//    {
//        $interfaces = static::$ldiflibInheritedInterfaces[$class] ?? [];
//        foreach ($interfaces as $interface) {
//            $nestedInterfaces = static::$ldiflibInheritedInterfaces[$interface] ?? [];
//        }
//        return $interfaces;
//    }
//
//    protected static function getLdifInheritedInterfaceGetters(string $class) : array
//    {
//        $getters = [];
//        $inheritedInterfaces = static::$ldiflibInheritedInterfaces[$class] ?? [];
//        foreach ($inheritedInterfaces as $inheritedInterface) {
//            $getters = array_merge($getters, static::getLdifInterfacePropertyGetters($inheritedInterface);
//        }
//        return $getters;
//    }
//
//    protected static function getLdifInterfacePropertyGetters(string $interface) : array
//    {
//        $getters = static::getLdifInterfaceInheritedGetters($interface);
//        if (($new = static::$ldiflibPropertyGetters[$interface]) !== null) {
//            $getters = array_merge($getters, $new);
//        }
//        return $getters;
//    }
//
//    protected static function getLdifClassPropertyGetters(string $class) : array
//    {
//        $interfaces = class_implements($class);
//        foreach ($interfaces as $interface) {
//            $getters = array_merge(static::getLdifInterfacePropertyGetters($interface));
//        }
//        return $getters;
//    }
//
//    /**
//     *
//     */
//    public static function assertLdifInterfaceProperties(array $expected)
//    {
//
//    }
}

// vim: syntax=php sw=4 ts=4 et:
