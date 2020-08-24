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
 * @todo Write documentation
 */
interface InputInterface
{
    /**
     * Returns the original source string, as provided to constructor via
     * $source parameter.
     *
     * @return string
     */
    public function getSourceString() : string;

    /**
     * Returns the preprocessed string, as provided to constructor via $string
     * parameter.
     *
     * @return string
     */
    public function getString() : string;

    /**
     * Returns the source file name provided to constructor via $sourceFileName
     * parameter.
     *
     * @return string
     */
    public function getSourceFileName() : string;

    /**
     * Returns the preprocessed string, as provided to constructor via $string
     * parameter.
     *
     * @return string
     */
    public function __toString();

    /**
     * Given a byte offset $i in the preprocessed string, returns its
     * corresponding byte offset in the original $source string.
     *
     * @pararm int $i character offset in the preprocessed string
     *
     * @return int the resultant offset of the corresponding character in
     *             $source string
     */
    public function getSourceOffset(int $i) : int;

    /**
     * Given a byte offset $i in the preprocessed string, returns its
     * corresponding (multibyte) character offset in the original $source
     * string.
     *
     * @param  int $i character offset in the preprocessed string
     * @param  string $encoding
     *
     * @return int the resultant offset of the corresponding character in
     *             $source string
     */
    public function getSourceCharOffset(int $i, string $encoding = null) : int;

    /**
     * Returns array of strings resulted from splitting the $source into lines.
     *
     * @return array
     */
    public function getSourceLines() : array;

    /**
     * Returns the number of source lines.
     *
     * @return int
     */
    public function getSourceLinesCount() : int;

    /**
     * Returns $i'th line of the source string.
     *
     * @param  int $i
     *
     * @return string
     */
    public function getSourceLine(int $i) : string;

    /**
     * Given a character offset $i in the preprocessed string, returns its
     * corresponding line number (zero-based) in the original $source string.
     *
     * @param  int $i Character offset in the preprocessed string
     *
     * @return int
     */
    public function getSourceLineIndex(int $i) : int;

    /**
     * Given a character offset $i in the preprocessed string, returns its
     * corresponding line number in the $source string (zero-based) and the
     * character offset relative to the beginning of the source line.
     *
     * @param  int $i Character offset in the preprocessed string
     *
     * @return array 2-element array with line index at position 0 and
     *               character offset at position 1.
     */
    public function getSourceLineAndOffset(int $i) : array;

    /**
     * Given a character offset $i in the preprocessed string, returns its
     * corresponding line number in the $source string (zero-based) and the
     * character offset relative to the beginning of the source line.
     *
     * @param  int $i Character offset in the preprocessed string
     * @param  string $encoding
     *
     * @return array 2-element array with line index at position 0 and
     *               character offset at position 1.
     */
    public function getSourceLineAndCharOffset(int $i, string $encoding = null) : array;
}

// vim: syntax=php sw=4 ts=4 et:
