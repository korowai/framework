<?php
/**
 * @file tests/Korowai/Lib/Ldif/Nodes/LdifModifyRecordInterfaceTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Nodes;

use Korowai\Lib\Ldif\Nodes\LdifModifyRecordInterface;
use Korowai\Lib\Ldif\Nodes\LdifChangeRecordInterface;
use Korowai\Lib\Ldif\NodeInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LdifModifyRecordInterfaceTest extends TestCase
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
        $this->assertImplementsInterface($extends, LdifModifyRecordInterface::class);
    }

    public function test__dummyImplementation()
    {
        $dummy = new class implements LdifModifyRecordInterface {
            use LdifModifyRecordInterfaceTrait;
        };
        $this->assertImplementsInterface(LdifModifyRecordInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'modSpecs'      => 'getModSpecs'
        ];
        $this->assertObjectPropertyGetters($expect, LdifModifyRecordInterface::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
