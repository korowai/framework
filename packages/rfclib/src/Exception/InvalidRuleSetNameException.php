<?php
/**
 * @file src/Exception/InvalidRuleSetNameException.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/rfclib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Rfc\Exception;

/**
 * An exception thrown when a caller was expected to provide class name of a
 * class implementing StaticRuleSetInterface, but it failed to do so.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class InvalidRuleSetNameException extends \InvalidArgumentException
{
}

// vim: syntax=php sw=4 ts=4 et:
