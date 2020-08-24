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
 * LDIF parse error. Encapsulates error message and the location of the error
 * in source code.
 */
interface ParserErrorInterface extends SourceLocationInterface, \Throwable
{
    /**
     * Returns multiline error message for detailed error reporting.
     *
     * @return string
     */
    public function getMultilineMessage() : string;
}

// vim: syntax=php sw=4 ts=4 et:
