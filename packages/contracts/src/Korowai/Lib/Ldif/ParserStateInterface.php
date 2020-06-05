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

    // /**
    //  * Returns records extracted from LDIF source so far.
    //  *
    //  * @return array an array of RecordInterface instances.
    //  */
    // public function getRecords() : array;

    // /**
    //  * Returns an object encapsulating version-spec as it is found from source
    //  * LDIF file, or null if there was no version-spec.
    //  *
    //  * @return VersionSpecInterface|null
    //  */
    // public function getVersionSpec() : ?VersionSpecInterface;

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
     * Appends new error created with *$message* and using *$this* object's
     * cursor as error's location.
     *
     * @param  string $message
     * @param  array $arguments Optional arguments passed to error's constructor.
     *
     * @return object $this
     */
    public function errorHere(string $message, array $arguments = []);

    /**
     * Appends new error created with *$message* and using *$offset* as error's
     * location. The cursor remains unchanged.
     *
     * @param  int $offset
     * @param  string $message
     * @param  array $arguments Optional arguments passed to error's constructor.
     *
     * @return object $this
     */
    public function errorAt(int $offset, string $message, array $arguments = []);

    // /**
    //  * Append new record.
    //  *
    //  * @param  RecordInterface $record
    //  *
    //  * @return object $this
    //  */
    // public function appendRecord(RecordInterface $record);

    // /**
    //  * Set version-spec.
    //  *
    //  * @param VersionSpecInterface|null $versionSpec
    //  *
    //  * @return object $this
    //  */
    // public function setVersionSpec(?VersionSpecInterface $versionSpec);
}

// vim: syntax=php sw=4 ts=4 et:
