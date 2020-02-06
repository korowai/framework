<?php
/**
 * @file Tests/ParserTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif;

use Korowai\Lib\Ldif\Parser;
use Korowai\Lib\Ldif\ParserInterface;
use Korowai\Lib\Ldif\Input;
use Korowai\Lib\Ldif\Util\IndexMap;

use Korowai\Testing\Lib\Ldif\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ParserTest extends TestCase
{
    public function test__implements__ParserInterface()
    {
        $this->assertImplementsInterface(ParserInterface::class, Parser::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
