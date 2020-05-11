<?php
/**
 * @file Testing/TestCase.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Lib\Ldif;

use Korowai\Testing\Lib\Ldif\Assertions\ObjectPropertiesAssertions;
use Korowai\Testing\Lib\Ldif\Traits\ObjectProperties;
use Korowai\Testing\Lib\Ldif\Traits\ParserTestHelpers;

/**
 * Abstract base class for korowai/ldiflib unit tests.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class TestCase extends \Korowai\Testing\TestCase
{
    use ParserTestHelpers;
    use ObjectProperties;
    use ObjectPropertiesAssertions;

    /**
     * {@inheritdoc}
     */
    public static function objectPropertyGettersMap() : array
    {
        return array_merge_recursive(
            parent::objectPropertyGettersMap(),
            self::$ldiflibObjectPropertyGettersMap
        );
    }
}

// vim: syntax=php sw=4 ts=4 et:
