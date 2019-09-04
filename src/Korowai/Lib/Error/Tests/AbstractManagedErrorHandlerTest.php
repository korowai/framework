<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Error\Tests;

use PHPUnit\Framework\TestCase;

use Korowai\Lib\Error\AbstractManagedErrorHandler;
use Korowai\Lib\Error\ErrorHandlerInterface;
use Korowai\Lib\Context\ContextManagerInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @coversDefaultClass \Korowai\Lib\Error\AbstractManagedErrorHandler
 */
class AbstractManagedErrorHandlerTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    public function test__implements__ErrorHandlerInterface()
    {
        $interfaces = class_implements(AbstractManagedErrorHandler::class);
        $this->assertContains(ErrorHandlerInterface::class, $interfaces);
    }

    public function test__implements__ContextManagerInterface()
    {
        $interfaces = class_implements(AbstractManagedErrorHandler::class);
        $this->assertContains(ContextManagerInterface::class, $interfaces);
    }

    public function 
}

// vim: syntax=php sw=4 ts=4 et:
