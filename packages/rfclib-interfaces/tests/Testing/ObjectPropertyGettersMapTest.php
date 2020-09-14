<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\RfclibInterfaces;

use Korowai\Testing\RfclibInterfaces\ObjectPropertyGettersMap;
use Korowai\Testing\RfclibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\RfclibInterfaces\ObjectPropertyGettersMap
 */
final class ObjectPropertyGettersMapTest extends TestCase
{
    public function test__getObjectPropertyGettersMap() : void
    {
        $this->assertIsArray(ObjectPropertyGettersMap::getObjectPropertyGettersMap());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
