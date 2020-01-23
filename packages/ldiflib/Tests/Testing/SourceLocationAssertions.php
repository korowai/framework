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

namespace Korowai\Tests\Lib\Ldif\Assertions;

use Korowai\Lib\Ldif\SourceLocationInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait SourceLocationAssertions
{
    protected static $sourceLocationGetters = [
        'sourceFileName'            => 'getSourceFileName',
        'sourceString'              => 'getSourceString',
        'sourceOffset'              => 'getSourceOffset',
        'sourceCharOffset'          => 'getSourceCharOffset',
        'sourceLineIndex'           => 'getSourceLineIndex',
        'sourceLineIndex'           => 'getSourceLine',
        'sourceLineAndOffset'       => 'getSourceLineAndOffset',
        'sourceLineAndCharOffset'   => 'getSourceLineAndCharOffset',
    ];
}

// vim: syntax=php sw=4 ts=4 et:
