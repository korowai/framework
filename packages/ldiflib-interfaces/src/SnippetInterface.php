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
 * LocationInterface plus length.
 */
interface SnippetInterface extends LocationInterface
{
    /**
     * Returns the snippet length in bytes.
     */
    public function getLength(): int;

    /**
     * Returns the end offset of the snippet in bytes.
     */
    public function getEndOffset(): int;

    /**
     * Returns the length in bytes of the snippet mapped to source string.
     */
    public function getSourceLength(): int;

    /**
     * Returns the end offset in bytes of the snippet mapped to source string.
     */
    public function getSourceEndOffset(): int;

    /**
     * Returns the length in characters of the snippet mapped to source string.
     */
    public function getSourceCharLength(string $encoding = null): int;

    /**
     * Returns the end offset in characters of the snippet mapped to source string.
     */
    public function getSourceCharEndOffset(string $encoding = null): int;
}

// vim: syntax=php sw=4 ts=4 et tw=119:
