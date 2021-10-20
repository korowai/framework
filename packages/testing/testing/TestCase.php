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

use Korowai\Testing\Traits\PregUtilsTrait;
use Tailors\PHPUnit\ImplementsInterfaceTrait;
use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\UsesTraitTrait;
use Tailors\PHPUnit\HasPregCapturesTrait;
use Tailors\PHPUnit\ObjectPropertiesEqualToTrait;
use Tailors\PHPUnit\ObjectPropertiesIdenticalToTrait;
use Tailors\PHPUnit\ClassPropertiesEqualToTrait;
use Tailors\PHPUnit\ClassPropertiesIdenticalToTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    use PregUtilsTrait;
    use ImplementsInterfaceTrait;
    use ExtendsClassTrait;
    use UsesTraitTrait;
    use HasPregCapturesTrait;
    use ObjectPropertiesEqualToTrait;
    use ObjectPropertiesIdenticalToTrait;
    use ClassPropertiesEqualToTrait;
    use ClassPropertiesIdenticalToTrait;
}

// vim: syntax=php sw=4 ts=4 et:
