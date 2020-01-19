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
 * LDIF parser interface.
 */
interface ParserInterface
{
    /**
     * Parses preprocessed LDIF text.
     *
     * @param ParserStateInterface $state
     *
     * @return bool
     */
    public function parse(ParserStateInterface $state) : bool;
}

// vim: syntax=php sw=4 ts=4 et:
