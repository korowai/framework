<?php
/**
 * @file src/Korowai/Lib/Error/Tests/FunctionTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Errorlib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Error\Tests;

use PHPUnit\Framework\TestCase;

use Korowai\Lib\Error\EmptyErrorHandler;
use function Korowai\Lib\Error\emptyErrorHandler;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class FunctionsTest extends TestCase
{
    public function test__emptyErrorHandler()
    {
        $this->assertInstanceof(EmptyErrorHandler::class, emptyErrorHandler());
    }
}

// vim: syntax=php sw=4 ts=4 et:
