<?php
/**
 * @file src/Korowai/Lib/Context/functions.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\ContextLib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Error;

/**
 * A shortcut to EmptyErrorHandler::getInstance().
 *
 * @return EmptyErrorHandler
 */
function emptyErrorHandler() : EmptyErrorHandler
{
    return EmptyErrorHandler::getInstance();
}

// vim: syntax=php sw=4 ts=4 et:
