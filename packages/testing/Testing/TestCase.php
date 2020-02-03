<?php
/**
 * @file Testing/TestCase.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/testing
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

    /**
     * Checks the *$KOROWAI_HEAVY_TESTING* OS environment variable and returns
     * true if the variable exists and is one of "y", "yes", "on", "true" or is
     * a non-zero integer. This is used to enable additional test cases.
     *
     * @return bool
     */
    public static function isHeavyTesting() : bool
    {
        $env = strtolower((string)getenv('KOROWAI_HEAVY_TESTING', true));
        return in_array($env, ['y', 'yes', 'true', 'on']) || ((int)$env > 0);
    }
}

// vim: syntax=php sw=4 ts=4 et:
