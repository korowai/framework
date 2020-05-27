<?php
/**
 * @file tests/Korowai/Lib/Ldif/Nodes/LdifChangeRecordInterfaceTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Nodes;

use Korowai\Lib\Ldif\Nodes\LdifChangeRecordInterface;
use Korowai\Lib\Ldif\RecordInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LdifChangeRecordInterfaceTest extends TestCase
{
    public static function extendsInterface__cases()
    {
        return [
            [RecordInterface::class],
        ];
    }

    /**
     * @dataProvider extendsInterface__cases
     */
    public function test__extendsInterface(string $extends)
    {
        $this->assertImplementsInterface($extends, LdifChangeRecordInterface::class);
    }

    public function test__dummyImplementation()
    {
        $dummy = new class implements LdifChangeRecordInterface {
            use LdifChangeRecordInterfaceTrait;
        };
        $this->assertImplementsInterface(LdifChangeRecordInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'changeType'    => 'getChangeType',
            'controls'      => 'getControls',
        ];
        $this->assertObjectPropertyGetters($expect, LdifChangeRecordInterface::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
