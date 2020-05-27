<?php
/**
 * @file tests/Korowai/Lib/Ldif/ParserStateInterfaceTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif;

use Korowai\Lib\Ldif\ParserStateInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ParserStateInterfaceTest extends TestCase
{
    public function test__dummyImplementation()
    {
        $dummy = new class implements ParserStateInterface {
            use ParserStateInterfaceTrait;
        };
        $this->assertImplementsInterface(ParserStateInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'cursor'    => 'getCursor',
            'errors'    => 'getErrors',
            'isOk'      => 'isOk',
        ];
        $this->assertObjectPropertyGetters($expect, ParserStateInterface::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
