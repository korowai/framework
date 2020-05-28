<?php
/**
 * @file tests/Korowai/Lib/Ldif/NodeInterfaceTrait.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif;

use Korowai\Lib\Ldif\SnippetInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait NodeInterfaceTrait
{
    public function getSnippet() : ?SnippetInterface
    {
        return null;
    }
}

// vim: syntax=php sw=4 ts=4 et:
