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
    use \Korowai\Component\Ldif\Util\PpFunctions;

    /**
     * @param string $source
     *
     * @return PpString
     */
    public function preprocess(string $source) : PpString
    {
        $string = self::ppRmLnCont($source, $im);
        $string = self::ppRmComments($string, $im);
        return new PpString($source, $string, $im);
    }
}

// vim: syntax=php sw=4 ts=4 et:
