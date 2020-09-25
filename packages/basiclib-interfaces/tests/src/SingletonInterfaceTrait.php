<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
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
