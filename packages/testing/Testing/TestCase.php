<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing;

use Korowai\Testing\Assertions\ClassAssertionsTrait;
use Korowai\Testing\Assertions\ObjectPropertiesAssertionsTrait;
use Korowai\Testing\Assertions\ObjectPropertyGettersAssertionsTrait;
use Korowai\Testing\Assertions\PregAssertionsTrait;
use Korowai\Testing\Traits\ObjectPropertiesUtilsTrait;
use Korowai\Testing\Traits\PregUtilsTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    use ClassAssertionsTrait;
    use ObjectPropertiesAssertionsTrait;
    use ObjectPropertyGettersAssertionsTrait;
    use PregAssertionsTrait;
    use ObjectPropertiesUtilsTrait;
    use PregUtilsTrait;

    /**
     * Returns a key-value array which maps class names onto arrays of property
     * getters. Each array of property getters is a key-value array with keys
     * being property names and values being names of corresponding getter methods.
     *
     * @return array
     */
    public static function objectPropertyGettersMap() : array
    {
        return ObjectPropertyGettersMap::getObjectPropertyGettersMap();
    }
}

// vim: syntax=php sw=4 ts=4 et:
