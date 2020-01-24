<?php
/**
 * @file src/Korowai/Testing/Classes/ExampleClassUsingTrait.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\testing
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Classes;

use Korowai\Testing\Traits\ExampleTrait;

/**
 * Example class for testing purposes.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ExampleClassUsingTrait
{
    use ExampleTrait;
}

// vim: syntax=php sw=4 ts=4 et:
