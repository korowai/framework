<?php
/**
 * @file Tests/Testing/LdifLibInterfaceProperties.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Contratcs\Testing;

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
trait LdifLibInterfaceProperties
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
}

// vim: syntax=php sw=4 ts=4 et:
