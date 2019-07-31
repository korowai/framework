<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Component\Ldif\Preprocessor;

/**
 * Functions that support LDIF text preprocessing.
 */
class Preprocessor
{
    use PpFunctions;

    public function preprocess(string $source) : PpResult
    {
        $string = self::ppRmLnCont($source, $jumps);
        $string = self::ppRmComments($string, $jumps);
        return new PpResult($source, $string, $jumps);
    }
}

// vim: syntax=php sw=4 ts=4 et:
