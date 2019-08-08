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
 * LDIF preprocessor.
 */
class Preprocessor
{
    use Util\PpFunctions;

    /**
     * @param string $source
     * @param string $filename
     *
     * @return CoupledInput
     */
    public function preprocess(string $source, string $filename=null) : CoupledInput
    {
        $string = self::ppRmLnCont($source, $im);
        $string = self::ppRmComments($string, $im);
        return new CoupledInput($source, $string, $im, $filename);
    }
}

// vim: syntax=php sw=4 ts=4 et:
