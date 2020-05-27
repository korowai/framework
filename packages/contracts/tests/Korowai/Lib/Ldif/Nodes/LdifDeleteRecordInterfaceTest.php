<?php
/**
 * @file tests/Korowai/Lib/Ldif/Nodes/LdifDeleteRecordInterfaceTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Nodes;

use Korowai\Lib\Ldif\Nodes\LdifDeleteRecordInterface;
use Korowai\Lib\Ldif\Nodes\LdifChangeRecordInterface;
use Korowai\Lib\Ldif\NodeInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LdifDeleteRecordInterfaceTest extends TestCase
{
    public static function extendsInterface__cases()
    {
        return [
            [LdifChangeRecordInterface::class],
            [NodeInterface::class],
        ];
    }

    /**
     * @dataProvider extendsInterface__cases
     */
    public function test__extendsInterface(string $extends)
    {
        $this->assertImplementsInterface($extends, LdifDeleteRecordInterface::class);
    }

    public function test__dummyImplementation()
    {
        $dummy = new class implements LdifDeleteRecordInterface {
            use LdifDeleteRecordInterfaceTrait;
        };
        $this->assertImplementsInterface(LdifDeleteRecordInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [];
        $this->assertObjectPropertyGetters($expect, LdifDeleteRecordInterface::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
