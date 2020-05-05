<?php
/**
 * @file tests/Exception/InvalidRuleNameExceptionTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldaplib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Exception;

use Korowai\Testing\TestCase;
use Korowai\Lib\Ldif\Exception\InvalidRuleNameException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class InvalidRuleNameExceptionTest extends TestCase
{
    public function testBaseClass()
    {
        $this->assertInstanceOf(\InvalidArgumentException::class, new InvalidRuleNameException());
    }

    public function test_getMessage_DefaultMessage()
    {
        $e = new InvalidRuleNameException();
        $this->assertEquals("", $e->getMessage());
    }

    public function test_getMessage_CustomMessage()
    {
        $e = new InvalidRuleNameException("Custom message");
        $this->assertEquals("Custom message", $e->getMessage());
    }
}

// vim: syntax=php sw=4 ts=4 et:
