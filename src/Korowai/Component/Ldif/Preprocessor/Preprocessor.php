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
 * An abstract base class for a piece of LDIF file.
 */
trait Preprocessor
{
    public static function preprocess(string $src, array &$offsets=null)
    {
        $re_comment = '^#(?:[^\r\n]|(?:(?:\r\n|\n) ))*';
        $re_lncont = '(?:\r\n|\n) ';

        $re = '/((?:' . $re_comment . ')|(?:'. $re_lncont . '))/mu';
        $pieces = preg_split($re, $src, -1, PREG_SPLIT_OFFSET_CAPTURE | PREG_SPLIT_NO_EMPTY);

        var_dump($pieces);

        $offsets = [];
        $offset = 0;
        foreach($pieces as $piece) {
            $offsets[] = [$offset, $piece[1]];
            $offset += strlen($piece[0]);
        }
        return implode(array_map(function ($p) {return $p[0];}, $pieces));
    }
}

// vim: syntax=php sw=4 ts=4 et:
