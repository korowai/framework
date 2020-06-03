<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Exception;

/**
 * An exception thrown when a caller was expected to provide supported rule
 * name, but it failed to do so.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class InvalidRuleNameException extends \InvalidArgumentException
{
}

// vim: syntax=php sw=4 ts=4 et:
