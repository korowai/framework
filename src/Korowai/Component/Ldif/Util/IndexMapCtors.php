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
 * Functions that support LDIF text preprocessing.
 */
trait IndexMapCtors
{
    /**
     * Generates "index map" array for a string made of substrings of an
     * original string.
     *
     * Index map is used later to map character offset in the resultant string
     * onto corresponding offsets in the original string.
     *
     * $pieces must be an array where every element is an array consisting of a
     * substring of the original string at offset 0 and its string offset into
     * original string at offset 1. Such an array is returned, for example, by
     * ``preg_split(..., PREG_SPLIT_OFFSET_CAPTURE)``.
     *
     * @param array $pieces Pieces of the original string that will form the
     *                      resultant string (see function description above)
     * @return array
     */
    protected static function imForPieces(array $pieces) : array
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
     * @param array $old original jump array from previous steps
     * @param array $new a new jump array to be applied to $old
     *
     * @return array
     */
    protected static function imOverIm(array $old, array $new) : array
    {
        $im = [];
        $ns = 0; // new shrink (introduced by $new)
        $ts = 0; // total shrink (cumulation of $old and $new)
        for($i=0, $j=0; $i < count($old) || $j < count($new); ) {
            if( $j < count($new) &&
               ($i >= count($old) || $new[$j][1] < ($old[$i][0] - $ns))) {
                //
                // $new[$j] on the left side of $old[$i]
                //
                $ts += ($new[$j][1] - $new[$j][0]);
                $im[] = [$new[$j][0], $new[$j][0] + $ts];
                $ns += ($new[$j][1] - $new[$j][0]);
                $j++;
            } elseif($j < count($new) && $i < count($old) &&
                     $new[$j][0] <= $old[$i][0] - $ns) {
                //
                // $new[$j] encloses $old[$i] (and perhaps $old[$i+1], ...)
                //
                $ts += ($new[$j][1] - $new[$j][0]);
                do {
                    $ts += ($old[$i][1] - $old[$i][0]);
                    $i++;
                } while($i < count($old) && $new[$j][1] >= $old[$i][0] - $ns);
                $im[] = [$new[$j][0], $new[$j][0] + $ts];
                $ns += ($new[$j][1] - $new[$j][0]);
                $j++;
            } elseif($i < count($old)) {
                //
                // $new[$j] on the right side of $old[$i]
                //
                $im[] = [$old[$i][0] - $ns, $old[$i][1]];
                $ts += ($old[$i][1] - $old[$i][0]);
                $i++;
            } else {
                throw \RuntimeException("internal error");
            }
        }
        return $im;
    }
}

// vim: syntax=php sw=4 ts=4 et:
