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
 * Interface for cursor objects.
 */
interface LocationInterface extends SourceLocationInterface
{
    /**
     * Returns the whole input string.
     *
     * @return string
     */
    public function getString() : string;

    /**
     * Returns zero-based byte offset in the input string of the location.
     *
     * @return int
     */
    public function getOffset() : int;

    /**
     * Returns whether the offset points at a character within the string.
     *
     * The method shall return the value of the following expression
     *
     * ```
     *  (getOffset() >= 0 && getOffset() < strlen(getString()))
     * ```
     *
     * @return bool
     */
    public function isValid() : bool;

    /**
     * Returns zero-based (multibyte) character offset in the input string of the location.
     *
     * @return int
     */
    public function getCharOffset(string $encoding = null) : int;

    /**
     * Returns the InputInterface containing the character at location.
     *
     * @return InputInterface|null
     */
    public function getInput() : InputInterface;

    /**
     * Returns new LocationInterface instance made out of this one. The
     * returned object points to the same input at *$offset*. If *$offset* is
     * null or not given, then it's taken from this location.
     *
     * @param  int|null $offset
     *
     * @return LocationInterface
     */
    public function getClonedLocation(int $offset = null) : LocationInterface;
}

// vim: syntax=php sw=4 ts=4 et:
