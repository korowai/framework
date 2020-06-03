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
 * An exception thrown when a caller requested a nested rule, but no nested
 * rules are defined.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class NoRulesDefinedException extends \RuntimeException
{
}

// vim: syntax=php sw=4 ts=4 et:
