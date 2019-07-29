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
    public static function ppMkJumps(array $pieces) : array
    {
        $jumps = [];
        $offset = 0;
        foreach($pieces as $piece) {
            $jumps[] = [$offset, $piece[1]];
            $offset += strlen($piece[0]);
        }
        return $jumps;
    }

    public static function ppCascadeJumps(array $old, array $new) : array
    {
        $jumps = [];
        $ns = 0; // new shrink (introduced by $new)
        $ts = 0; // total shrink (cumulation of $old and $new)
        for($i=0, $j=0; $i < count($old) || $j < count($new); ) {
            if(     $j < count($new)
                && ($i >= count($old) || $new[$j][1] < ($old[$i][0] - $ns))) {
                //
                // $new[$j] on the left side of $old[$i]
                //
                echo "1: $ns, $ts\n";
                $ns += ($new[$j][1] - $new[$j][0]);
                $ts += ($new[$j][1] - $new[$j][0]);
                $jumps[] = [$new[$j][0], $new[$j][0] + $ts];
                $j++;
            } elseif($j < count($new) && $i < count($old) &&
                     $new[$j][0] <= $old[$i][0] - $ns) {
                //
                // $new[$j] encloses $old[$i] (and perhaps $old[$i+1], ...)
                //
                echo "2: $ns, $ts\n";
                $ns += ($new[$j][1] - $new[$j][0]);
                $ts += ($new[$j][1] - $new[$j][0]);
                do {
                    $ts += ($old[$i][1] - $old[$i][0]);
                    $i++;
                } while($i < count($old) && $new[$j][1] >= $old[$i][0] - $ns);
                $jumps[] = [$new[$j][0], $new[$j][0] + $ts];
                $j++;
            } elseif($i < count($old)) {
                //
                // $new[$j] on the right side of $old[$i]
                //
                echo "3: $ns, $ts\n";
                $ts += ($old[$i][1] - $old[$i][0]);
                $jumps[] = [$old[$i][0] - $ns, $old[$i][0] - $ns + $ts];
                $i++;
            } else {
                throw \RuntimeException("internal error");
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
    public static function ppAsmPieces(array $pieces, array &$jumps=null) : string
    {
        if($jumps === null) {
            $jumps = self::ppMkJumps($pieces);
        } else {
            $jumps = self::ppCascadeJumps($jumps, self::ppMkJumps($pieces));
        }
        return implode(array_map(function ($p){return $p[0];}, $pieces));
    }

    public static function ppRmRe(string $re, string $src, array &$jumps=null) : string
    {
        $flags = PREG_SPLIT_OFFSET_CAPTURE | PREG_SPLIT_NO_EMPTY;
        return self::ppAsmPieces(preg_split($re, $src, -1, $flags), $jumps);
    }

    /**
     * Removes line continuations from LDIF text (unfolds the lines).
     *
     * @param string $src
     * @param array $jumps
     * @return string
     */
    public static function ppRmLnCont(string $src, array &$jumps=null) : string
    {
        return self::ppRmRe('/(?:\r\n|\n) /mu', $src, $jumps);
    }

    /**
     * Removes comment lines from LDIF text.
     *
     * @param string $src
     * @param array $jumps
     * @return string
     */
    public static function ppRmComments(string $src, array &$jumps=null) : string
    {
        return self::ppRmRe('/^#(?:[^\r\n])*(?:\r\n|\n)?/mu', $src, $jumps);
    }
}

// vim: syntax=php sw=4 ts=4 et:
