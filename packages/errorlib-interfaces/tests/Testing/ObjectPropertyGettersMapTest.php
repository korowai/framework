<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\ErrorlibInterfaces;

use Korowai\Testing\ErrorlibInterfaces\ObjectPropertyGettersMap;
use Korowai\Testing\ErrorlibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\ErrorlibInterfaces\ObjectPropertyGettersMap
 */
final class ObjectPropertyGettersMapTest extends TestCase
{
    public function test__getObjectPropertyGettersMap()
    {
        $this->assertIsArray(ObjectPropertyGettersMap::getObjectPropertyGettersMap());
    }
}

// vim: syntax=php sw=4 ts=4 et:
