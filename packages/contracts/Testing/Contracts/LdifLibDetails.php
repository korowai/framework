<?php
/**
 * @file Testing/Contracts/LdifLibDetails.php
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

/**
 * Describes expected details or the korowai\contracts package.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LdifLibDetails implements PackageDetailsInterface
{
    use PackageDetailsMemberArrays;
    use Singleton;

    protected $classesDetails = [
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
