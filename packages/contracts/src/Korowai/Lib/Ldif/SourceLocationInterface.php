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
 * Interface for objects representing location in a source code.
 */
interface SourceLocationInterface
{
    /**
     * Returns the source file name as string.
     *
     * @return string
     */
    public function getSourceFileName() : string;

    /**
     * Returns the whole source string.
     *
     * @return string
     */
    public function getSourceString() : string;

    /**
     * Returns zero-based byte offset in the source string at the location.
     *
     * @return int
     */
    public function getSourceOffset() : int;

    /**
     * Returns zero-based (multibyte) character offset of the source character
     * at the location.
     *
     * @return int
     */
    public function getSourceCharOffset(string $encoding = null) : int;

    /**
     * Returns zero-based source line index of the line at location.
     *
     * @return int
     */
    public function getSourceLineIndex() : int;

    /**
     * Returns the source line at location as string.
     *
     * @param  int $index Zero-based line index of the line to be returned. If
     *                   not given, an implementation should use the value
     *                   returned by ``getSourceLineIndex()`` instead.
     *
     * @return string
     */
    public function getSourceLine(int $index = null) : string;

    /**
     * Returns the line index and byte offset (relative to the beginning of the
     * line) for the location.
     *
     * ```php
     *  [$line, $byte] = $obj->getSourceLineAndOffset();
     * ```
     *
     * @return array Two-element array with line number stored at position 0
     *               and byte offset at position 1.
     */
    public function getSourceLineAndOffset() : array;

    /**
     * Returns the line index and (multibyte) character offset (relative to the
     * beginning of the line) for the location.
     *
     * ```php
     *  [$line, $char] = $obj->getSourceLineAndCharOffset();
     * ```
     *
     * @return array Two-element array with line number stored at position 0
     *               and character offset at position 1.
     */
    public function getSourceLineAndCharOffset(string $encoding = null) : array;
}

// vim: syntax=php sw=4 ts=4 et:
