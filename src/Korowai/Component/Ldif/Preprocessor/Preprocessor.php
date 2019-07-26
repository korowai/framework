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
trait Preprocessor
{
    /**
     *
     */
    public static function preMkJumps(array $pieces) : array
    {
        $jumps = [];
        $offset = 0;
        foreach($pieces as $piece) {
            $jumps[] = [$offset, $piece[1]];
            $offset += strlen($piece[0]);
        }
        return $jumps;
    }

    public static function preCascadeJumps(array $old, array $new) : array
    {
        $jumps = [];
        $ls = 0; // left shrink
        $ts = 0; // total shrink
        for($i=0, $j=0; $i < count($old) || $j < count($new); ) {
            if( $j < count($new)
                && (    $i >= count($old)
                    || ($new[$j][0] < $old[$i][0] - $ls &&
                        $new[$j][1] < $old[$i][0] - $ls)
                   )
            ) {
                // $new[$j] is on the left side of $old[$i]
                $ls + =($new[$j][1] - $new[$j][0]);
                $ts + =($new[$j][1] - $new[$j][0]);
                $jumps[] = [$new[$j][0], $new[$j][0] + $ts];
                $j++;
            } elseif($j < count($new) && $i < count($old) &&
                     $new[$j][0] <= $old[$i][0] - $ls &&
                     $new[$j][1] >= $old[$i][0] - $ls) {
                // $new[$j] encloses $old[$i]
                $ls += ($new[$j][1] - $new[$j][0]);
                $ts += (($new[$j][1] - $new[$j][0]) + ($old[$i][1] - $old[$i][0]));
                $jumps[] = [$new[$j][0], $new[$j][0] + $ts];
                $i++;
                $j++;
            } elseif($i < count($old)) {
                $ts += ($old[$i][1] - $old[$i][0]);
                $jumps[] = [$old[$i][0] - $ls, $old[$i][0] - $ls + $ts];
                $i++;
            }
        }
        return $jumps;
    }

    /**
     * Updates the array of jumps.
     *
     * @param array $pieces
     * @param array $jumps
     */
    public static function preAsmPieces(array $pieces, array &$jumps=null) : string
    {
        if($jumps === null) {
            $jumps = self::preMkJumps($pieces);
        } else {
            $jumps = self::preCascadeJumps($jumps, self::preMkJumps($pieces));
        }
        return implode(array_map(function ($p){return $p[0];}, $pieces));
    }

    public static function preCutRe(string $re, string $src, array &$jumps=null) : string
    {
        $flags = PREG_SPLIT_OFFSET_CAPTURE | PREG_SPLIT_NO_EMPTY;
        return self::preAsmPieces(preg_split($re, $src, -1, $flags), $jumps);
    }

    /**
     * Removes line continuations from LDIF text (unfolds the lines).
     *
     * @param string $src
     * @param array $jumps
     * @return string
     */
    public static function preCutLnCont(string $src, array &$jumps=null) : string
    {
        return self::preCutRe('/(?:\r\n|\n) /mu', $src, $jumps);

    }

    /**
     * Removes comment lines from LDIF text.
     *
     * @param string $src
     * @param array $jumps
     * @return string
     */
    public static function preCutComments(string $src, array &$jumps=null) : string
    {
        return self::preCutRe('/^#(?:[^\r\n]|(?:(?:\r\n|\n) ))*/mu', $src, $jumps);
    }
}

// vim: syntax=php sw=4 ts=4 et:
