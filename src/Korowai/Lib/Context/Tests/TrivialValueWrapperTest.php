<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Context\Tests;

use PHPUnit\Framework\TestCase;

use Korowai\Lib\Context\TrivialValueWrapper;
use Korowai\Lib\Context\ContextManagerInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @coversDefaultClass \Korowai\Lib\Context\TrivialValueWrapper
 */
class TrivialValueWrapperTest extends TestCase
{
    public function test__implements__ContextManagerInterface()
    {
        $interfaces = class_implements(TrivialValueWrapper::class);
        $this->assertContains(ContextManagerInterface::class, $interfaces);
    }

    /**
     * @covers ::__construct
     * @covers ::getResource
     */
    public function test__construct()
    {
        $value = ['foo'];
        $cm = new TrivialValueWrapper($value);
        $this->assertSame($value, $cm->getValue());
    }

    /**
     * @covers ::enterContext
     */
    public function test__enterContext()
    {
        $value = ['foo'];
        $cm = new TrivialValueWrapper($value);
        $this->assertSame($value, $cm->enterContext());
    }

    /**
     * @covers ::exitContext
     */
    public function test__exitContext()
    {
        $value = ['foo'];
        $cm = new TrivialValueWrapper($value);
        $this->assertFalse($cm->exitContext(null));
    }
}

// vim: syntax=php sw=4 ts=4 et:
