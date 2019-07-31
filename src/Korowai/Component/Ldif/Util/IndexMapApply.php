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
trait IndexMapApply
{
    /**
     * Applies index map $im to index value $i returning the mapped index
     * corresponding to $i.
     *
     * @param array $im
     * @param int $i
     *
     * @return int
     */
    protected static function imApply(array $im, int $i) : int
    {
        $cnt = count($im);

        if($cnt === 0) {
            return $i;
        }

        $lo = 0;
        $hi = $cnt - 1;

        if ($i < $im[0][0]) {
            return $i;
        } elseif($i >= $im[$hi][0]) {
            $inc = $im[$hi][2] ?? 1;
            return $im[$hi][1] + ($i - $im[$hi][0]) * $inc;
        } else {
            $iter = 0;
            while($hi - $lo > 1) {
                if($iter > 2 * $cnt) {
                    throw new \RuntimeException("internal error");
                }
                $mid = floor(($lo + $hi) / 2);
                if($i < $im[$mid][0]) {
                    $hi = $mid;
                } else {
                    $lo = $mid;
                }
                $iter++;
            }
            $inc = $im[$lo][2] ?? 1;
            return $im[$lo][1] + ($i - $im[$lo][0]) * $inc;
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
