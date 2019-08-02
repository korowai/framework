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
 * Provides imApply() static function.
 */
trait IndexMapApply
{
    /**
     * Applies index map $im to index value $i returning the mapped index
     * corresponding to $i.
     *
     * @param array $im
     * @param int $i
     * @param int $index Returns the index in $im used to compute the offset
     *
     * @return int
     */
    protected static function imApply(array $im, int $i, int &$index=null) : int
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
}

// vim: syntax=php sw=4 ts=4 et:
