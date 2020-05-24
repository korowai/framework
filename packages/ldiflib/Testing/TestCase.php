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

use Korowai\Testing\Lib\Ldif\Traits\ParserTestHelpers;

/**
 * Abstract base class for korowai/ldiflib unit tests.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class TestCase extends \Korowai\Testing\TestCase
{
    use ParserTestHelpers;

    /**
     * {@inheritdoc}
     */
    public static function objectPropertyGettersMap() : array
    {
        return array_merge_recursive(
            parent::objectPropertyGettersMap(),
            \Korowai\Testing\Contracts\ObjectPropertyGettersMap::getObjectPropertyGettersMap(),
            \Korowai\Testing\Lib\Rfc\ObjectPropertyGettersMap::getObjectPropertyGettersMap(),
            \Korowai\Testing\Lib\Ldif\ObjectPropertyGettersMap::getObjectPropertyGettersMap()
        );
    }
}

// vim: syntax=php sw=4 ts=4 et:
