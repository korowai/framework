<?php
/**
 * @file src/Korowai/Lib/Error/Tests/EmptyErrorHandlerTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Error\Tests;

use PHPUnit\Framework\TestCase;

use Korowai\Lib\Error\EmptyErrorHandler;
//use Korowai\Lib\Error\ErrorHandlerInterface;
use Korowai\Lib\Error\AbstractManagedErrorHandler;
use Korowai\Lib\Context\ContextManagerInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class EmptyErrorHandlerTest extends TestCase
{
    use \Korowai\Lib\Basic\Tests\SingletonTestMethods;

    protected function getClassUnderTest()
    {
        return EmptyErrorHandler::class;
    }

//    public function test__implements__ErrorHandlerInterface()
//    {
//        $interfaces = class_implements(EmptyErrorHandler::class);
//        $this->assertContains(ErrorHandlerInterface::class, $interfaces);
//    }

    public function test__extends__AbstractManagedErrorHandler()
    {
        $parents = class_parents(EmptyErrorHandler::class);
        $this->assertContains(AbstractManagedErrorHandler::class, $parents);
    }

    public function test__getErrorTypes()
    {
        $this->assertEquals(E_ALL | E_STRICT, EmptyErrorHandler::getInstance()->getErrorTypes());
    }

    public function test__invoke()
    {
        $this->assertTrue((EmptyErrorHandler::getInstance())(0, '', 'foo.php', 123));
    }
}

// vim: syntax=php sw=4 ts=4 et:
