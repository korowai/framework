<?php
/**
 * @file src/Korowai/Lib/Ldif/ParserStateInterface.php
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
 * State object returned by LDIF Parser.
 */
interface ParserStateInterface
{
    /**
     * Returns the cursor pointing to the current position within
     * source/preprocessed string.
     *
     * @return CursorInterface
     */
    public function getCursor() : CursorInterface;

    /**
     * Returns errors detected by parser so far.
     *
     * @return array an array of ParserErrorInterface instances.
     */
    public function getErrors() : array;

    /**
     * Returns records extracted from LDIF source so far.
     *
     * @return array an array of RecordInterface instances.
     */
    public function getRecords() : array;

    /**
     * Returns true if there are no errors.
     *
     * @return bool
     */
    public function isOk() : bool;

    /**
     * Append new error.
     *
     * @param  ParserErrorInterface $error
     *
     * @return object $this
     */
    public function appendError(ParserErrorInterface $error);

    /**
     * Append new record.
     *
     * @param  RecordInterface $record
     *
     * @return object $this
     */
    public function appendRecord(RecordInterface $record);
}

// vim: syntax=php sw=4 ts=4 et:
