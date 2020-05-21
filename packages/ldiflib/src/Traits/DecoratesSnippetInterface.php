<?php
/**
 * @file src/Traits/DecoratesSnippetInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\SnippetInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait DecoratesSnippetInterface
{
    use ExposesSnippetInterface;
    use HasSnippet;
}

// vim: syntax=php sw=4 ts=4 et:
