<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
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
