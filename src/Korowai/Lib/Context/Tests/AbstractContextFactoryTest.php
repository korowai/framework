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

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @coversDefaultClass \Korowai\Lib\Context\AbstractContextFactory
 */
class AbstractContextFactoryTest extends TestCase
{
    /**
     * @covers ::getInstance
     * @covers ::initializeSingleton
     */
    public function test__00()
    {
    }
}

// vim: syntax=php sw=4 ts=4 et:
