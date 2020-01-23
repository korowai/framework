<?php
/**
 * @file Tests/Testing/LocationGetters.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Assertions;

use Korowai\Lib\Ldif\LocationInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait LocationGetters
{
    protected static $locationGetters = [
        'string'              => 'getString',
        'offset'              => 'getOffset',
        'charOffset'          => 'getCharOffset',
    ];
}

// vim: syntax=php sw=4 ts=4 et:
