<?php
/**
 * @file Tests/SourceLocationAssertions.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Lib\Ldif;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Korowai\Testing\Assertions\ObjectPropertiesAssertions;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class TestCase extends PHPUnitTestCase
{
    use ObjectProperties;
    use ObjectPropertiesAssertions;
}

// vim: syntax=php sw=4 ts=4 et:
