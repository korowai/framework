<?php
/**
 * @file src/Korowai/Lib/Ldif/ParserStateInterface.php
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
 * State object returned by LDIF Parser.
 */
interface ParserStateInterface
{
    /**
     * Returns the cursor pointing to the current position within
     * source/preprocessed string.
     *
     * @return CoupledCursorInterface
     */
    public function getCursor() : CoupledCursorInterface;

    /**
     * Returns the errors detected by parser so far.
     *
     * @return array an array of error objects, each one implementing ParseErrorInterface
     */
    public function getErrors() : array;

    /**
     * Returns true if there are no errors.
     *
     * @return bool
     */
    public function isOk() : bool;
}

// vim: syntax=php sw=4 ts=4 et:
