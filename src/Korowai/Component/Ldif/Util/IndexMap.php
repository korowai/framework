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
 * Generates "index map" array for a string made out of pieces of other string.
 *
 * Index map is used to map (byte) offsets in the resultant string onto
 * corresponding (byte) offsets in the original string.
 *
 * ``$pieces`` must be an array where every element is an array consisting of a
 * substring of the original string at offset 0 and its string offset into
 * original string at offset 1. Such an array is returned by
 * ``preg_split(..., PREG_SPLIT_OFFSET_CAPTURE)``.
 *
 * @param array $pieces Pieces of the original string that will form the
 *                      resultant string (see function description above)
 * @return array
 */
function imFromPieces(array $pieces) : array
{
    $im = [];
    $offset = 0;
    foreach($pieces as $piece) {
        $im[] = [$offset, $piece[1]];
        $offset += strlen($piece[0]);
    }
    return $im;
}

/**
 * Applies one index map array ($new) over another one ($old).
 *
 * This shall be used to implement consecutive string manipulations, where
 * each step produces index map array.
 *
 * @param array $old original index map array from previous steps
 * @param array $new a new index map array to be applied to $old
 *
 * @return array
 */
function imOverIm(array $old, array $new) : array
{
    return (new ImOverImAlgorithm($old, $new))();
}

/**
 * Applies index map $im to index value $i returning the mapped index
 * corresponding to $i.
 *
 * @param array $im Index map array.
 * @param int $i An offset to be mapped.
 * @param int $index Returns the index in $im used to compute the offset
 *
 * @return int The result of mapping.
 */
function imApply(array $im, int $i, int &$index=null) : int
{
    $cnt = count($im);

    $lo = 0;
    $hi = $cnt - 1;

    if($cnt === 0) {
        return $i;
    }

    if ($i < $im[0][0]) {
        return $i;
    } elseif($i >= $im[$hi][0]) {
        $index = $hi;
        $inc = $im[$hi][2] ?? 1;
        return $im[$hi][1] + ($i - $im[$hi][0]) * $inc;
    } else {
        $iter = 0;
        while($hi - $lo > 1) {
            if($iter > 2 * $cnt) {
                throw new \RuntimeException("internal error: iteration count exceeded");
            }
            $mid = floor(($lo + $hi) / 2);
            if($i < $im[$mid][0]) {
                $hi = $mid;
            } else {
                $lo = $mid;
            }
            $iter++;
        }
        $index = $lo;
        $inc = $im[$lo][2] ?? 1;
        return $im[$lo][1] + ($i - $im[$lo][0]) * $inc;
    }
}

// vim: syntax=php sw=4 ts=4 et:
