<?php
/**
 * @file tests/Korowai/Lib/Ldif/ParserInterfaceTrait.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif;

use Korowai\Lib\Ldif\ParserStateInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ParserInterfaceTrait
{
    public function parse(ParserStateInterface $state) : bool
    {
        return false;
    }
}

// vim: syntax=php sw=4 ts=4 et:
