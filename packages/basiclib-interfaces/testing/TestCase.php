<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\BasiclibInterfaces;

use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * Abstract base class for korowai/basiclib-interfaces unit tests.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    use ImplementsInterfaceTrait;
    use ExtendsClassTrait;
}

// vim: syntax=php sw=4 ts=4 et:
