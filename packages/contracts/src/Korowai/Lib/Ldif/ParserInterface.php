<?php
/**
 * @file src/Korowai/Lib/Ldif/ParserInterface.php
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
 * LDIF parser interface.
 */
interface ParserInterface
{
    /**
     * Parses preprocessed LDIF text.
     *
     * @param CoupledInputInterface $input
     *
     * @return ParserStateInterface
     */
    public function parse(ParserStateInterface $state) : bool;
}

// vim: syntax=php sw=4 ts=4 et: