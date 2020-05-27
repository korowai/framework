<?php
/**
 * @file tests/Korowai/Lib/Ldif/ParserErrorInterfaceTrait.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif;

use Korowai\Tests\Lib\Ldif\SourceLocationInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ParserErrorInterfaceTrait
{
    use SourceLocationInterfaceTrait;

    public function getMultilineMessage() : string
    {
        return "";
    }
}

// vim: syntax=php sw=4 ts=4 et:
