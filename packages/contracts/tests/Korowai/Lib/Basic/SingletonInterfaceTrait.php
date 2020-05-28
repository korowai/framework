<?php
/**
 * @file tests/Korowai/Lib/Basic/SingletonInterfaceTrait.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Basic;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait SingletonInterfaceTrait
{
    public static $instance;

    public static function getInstance()
    {
        return self::$instance;
    }
}

// vim: syntax=php sw=4 ts=4 et:
