<?php
/**
 * @file src/Korowai/Lib/Basic/SingletonInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Basic;

/**
 * An interface implemented by singleton classes.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface SingletonInterface
{
    /**
     * Fetch an instance of the class.
     *
     * @return object
     */
    public static function getInstance();
}

// vim: syntax=php sw=4 ts=4 et:
