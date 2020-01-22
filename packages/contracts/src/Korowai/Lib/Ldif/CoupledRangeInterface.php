<?php
/**
 * @file src/Korowai/Lib/Ldif/CoupledRangeInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif;

/**
 * CoupledLocationInterface plus length.
 */
interface CoupledRangeInterface extends CoupledLocationInterface
{
    /**
     * Returns the span length in bytes.
     *
     * @return int
     */
    public function getByteLength() : int;

    /**
     * Returns the end offset of the span in bytes.
     *
     * @return int
     */
    public function getByteEndOffset() : int;

    /**
     * Returns the length in bytes of the span mapped to source string.
     *
     * @return int
     */
    public function getSourceByteLength() : int;

    /**
     * Returns the end offset in bytes of the span mapped to source string.
     *
     * @return int
     */
    public function getSourceByteEndOffset() : int;

    /**
     * Returns the length in characters of the span mapped to source string.
     *
     * @return int
     */
    public function getSourceCharLength(string $encoding = null) : int;

    /**
     * Returns the end offset in characters of the span mapped to source string.
     *
     * @return int
     */
    public function getSourceCharEndOffset(string $encoding = null) : int;
}

// vim: syntax=php sw=4 ts=4 et:
