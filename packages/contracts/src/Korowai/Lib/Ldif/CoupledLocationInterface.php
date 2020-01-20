<?php
/**
 * @file src/Korowai/Lib/Ldif/CoupledLocationInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif;

/**
 * Interface for cursor objects.
 */
interface CoupledLocationInterface extends SourceLocationInterface
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
    public function getByteOffset() : int;

    /**
     * Returns zero-based (multibyte) character offset in the input string of the location.
     *
     * @return int
     */
    public function getCharOffset(string $encoding = null) : int;
}

// vim: syntax=php sw=4 ts=4 et:
