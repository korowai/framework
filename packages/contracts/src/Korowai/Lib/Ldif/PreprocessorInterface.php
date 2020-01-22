<?php
/**
 * @file src/Korowai/Lib/Ldif/PreprocessorInterface.php
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
 * LDIF preprocessor interface.
 */
interface PreprocessorInterface
{
    /**
     * Runs preprocessor on source string.
     *
     * @param string $source
     * @param string $filename
     *
     * @return InputInterface
     */
    public function preprocess(string $source, string $filename = null) : InputInterface;
}

// vim: syntax=php sw=4 ts=4 et:
