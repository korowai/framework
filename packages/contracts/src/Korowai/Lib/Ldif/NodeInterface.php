<?php
/**
 * @file src/Korowai/Lib/Ldif/NodeInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif;

/**
 * Interface for parser rules.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface NodeInterface
{
    /**
     * Returns this node's snippet.
     *
     * @return SnippetInterface|null
     */
    public function getSnippet() : ?SnippetInterface;
}

// vim: syntax=php sw=4 ts=4 et:
