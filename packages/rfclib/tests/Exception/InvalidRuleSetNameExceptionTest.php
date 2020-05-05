<?php
/**
 * @file tests/Exception/InvalidRuleSetNameExceptionTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldaplib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Rfc\Exception;

use Korowai\Testing\TestCase;
use Korowai\Lib\Rfc\Exception\InvalidRuleSetNameException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class InvalidRuleSetNameExceptionTest extends TestCase
{
    public function testBaseClass()
    {
        $this->assertInstanceOf(\InvalidArgumentException::class, new InvalidRuleSetNameException());
    }

    public function test_getMessage_DefaultMessage()
    {
        $e = new InvalidRuleSetNameException();
        $this->assertEquals("", $e->getMessage());
    }

    public function test_getMessage_CustomMessage()
    {
        $e = new InvalidRuleSetNameException("Custom message");
        $this->assertEquals("Custom message", $e->getMessage());
    }
}

// vim: syntax=php sw=4 ts=4 et:
