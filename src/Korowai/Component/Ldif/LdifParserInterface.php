<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Component\Ldif;

/**
 * Interface implemented by any LDIF parser.
 */
interface LdifParserInterface
{
    /**
     * Parse LDIF provided as string (file contents).
     *
     * @param string $ldif
     * @return bool TRUE on success or FALSE on error.
     */
    public function parse(string $ldif) : bool;

    /**
     * Returns errors found by parser.
     *
     * @return array
     */
    public function errors() : array;

    /**
     * Returns the result of LDIF parsing.
     *
     * @return array
     */
    public function ast() : array;
}

// vim: syntax=php sw=4 ts=4 et:
