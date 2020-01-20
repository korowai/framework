<?php
/**
 * @file Tests/CoupledLocationTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Tests;

use Korowai\Lib\Ldif\CoupledLocation;
use Korowai\Lib\Ldif\CoupledLocationInterface;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class CoupledLocationTest extends TestCase
{
    public function test__implements__CoupledLocationInterface()
    {
        $interfaces = class_implements(CoupledLocation::class);
        $this->assertContains(CoupledLocationInterface::class, $interfaces);
    }
}

// vim: syntax=php sw=4 ts=4 et:
