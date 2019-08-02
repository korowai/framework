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
 * LDIF parser.
 */
class Parser
{
    /**
     * @param Preprocessed $input
     */
    public function parse(Preprocessed $input) : Parsed
    {
        $parsed = new Parsed($input);
        return $parsed;
    }
}

// vim: syntax=php sw=4 ts=4 et:
