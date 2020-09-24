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
use Korowai\Testing\Assertions\PropertiesAssertionsTrait;
use Korowai\Testing\Assertions\PregAssertionsTrait;
use Korowai\Testing\Traits\PregUtilsTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    use ClassAssertionsTrait;
    use PropertiesAssertionsTrait;
    use PregAssertionsTrait;
    use PregUtilsTrait;
}

// vim: syntax=php sw=4 ts=4 et tw=119:
