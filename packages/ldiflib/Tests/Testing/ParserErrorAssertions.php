<?php
/**
 * @file Tests/ParserErrorAssertions.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif;

use Korowai\Lib\Ldif\ParserError;
//use Korowai\Lib\Ldif\Preprocessor;
//use Korowai\Lib\Ldif\Cursor;
//use Korowai\Lib\Ldif\Input;

use PHPUnit\Framework\Constraint;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ParserErrorAssertions
{
    public static $parserErrorChecks = [
        'multilineMessage' => 'getMultilineMessage'
    ];
    /**
     *
     */
    public static function assertParserErrorMeets(array $expectations, ParserError $error)
    {
        //static::assertSame($expectations['message'], $error->getMessage());
        //static::assertSame($expectations['sourceOffset'], $error->getSourceCharOffset());
        'multilineMessage';
    }
}

// vim: syntax=php sw=4 ts=4 et:
