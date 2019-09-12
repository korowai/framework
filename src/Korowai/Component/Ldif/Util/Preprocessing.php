<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Component\Ldif\Util;

/**
 * Assembles pieces produced by ppRmRe() and updates "index map" array.
 *
 * @param array $pieces
 * @param array $im
 *
 * @return string Imploded pieces
 */
function ppAsmPieces(array $pieces, array &$im=null) : string
{
    $new_im = imFromPieces($pieces);
    if($im === null) {
        $im = $new_im;
    } else {
        $im = imCombine($im, $new_im);
    }
    return implode(array_map(function ($p){return $p[0];}, $pieces));
}

/**
 * Removes parts of the string that match a regular expression $re.
 *
 * @param string $re the regular expression to be matched
 * @param string $src the original string
 * @param array $im a reference to "index map" array
 *
 * @return string new string with removed parts that matched $re.
 */
function ppRmRe(string $re, string $src, array &$im=null) : string
{
    $flags = PREG_SPLIT_OFFSET_CAPTURE | PREG_SPLIT_NO_EMPTY;
    $pieces = preg_split($re, $src, -1, $flags);
    return ppAsmPieces($pieces, $im);
}

/**
 * Removes line continuations from LDIF text (unfolds the lines).
 *
 * @param string $src input string to be unfolded
 * @param array &$im an (input/output) "index map" array
 * @return string the resultant string with line continuations removed
 */
function ppRmLnCont(string $src, array &$im=null) : string
{
    return ppRmRe('/(?:\r\n|\n) /mu', $src, $im);
}

/**
 * Removes comment lines from LDIF text. This should be used after line
 * unfolding (see ppRmLnCont()).
 *
 * @param string $src input string to be stripped out from comments
 * @param array &$im an (input/output) "index map" array
 * @return string the resultant string with comments removed
 */
function ppRmComments(string $src, array &$im=null) : string
{
    return ppRmRe('/^#(?:[^\r\n])*(?:\r\n|\n)?/mu', $src, $im);
}

// vim: syntax=php sw=4 ts=4 et:
