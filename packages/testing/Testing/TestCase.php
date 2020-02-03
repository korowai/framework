<?php
/**
 * @file Testing/TestCase.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing;

use Korowai\Testing\Assertions\ClassAssertions;
use Korowai\Testing\Assertions\ObjectPropertiesAssertions;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    use ClassAssertions;
    use ObjectPropertiesAssertions;

    public static function isHeavyTesting() : bool
    {
        $env = strtolower((string)getenv('KOROWAI_HEAVY_TESTING', true));
        return in_array($env, ['y', 'true', 'on']) || ((int)$env > 0);
    }
}

// vim: syntax=php sw=4 ts=4 et:
