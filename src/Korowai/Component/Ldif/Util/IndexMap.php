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

class ImCombineState
{
    public $im = [];
    public $i = 0;
    public $j = 0;
    public $ns = 0; // new shrink (introduced by $new)
    public $ts = 0; // total shrink (accumulation of $old and $new)

    public function finished(array $old, array $new)
    {
        return $this->i >= count($old) && $this->j >= count($new);
    }

    public function isBefore(array $old, array $new)
    {
        if($this->j >= count($new)) {
            return false;
        }
        if($this->i >= count($old)) {
            return true;
        }
        return $new[$this->j][1] < ($old[$this->i][0] - $this->ns);
    }

    public function isNotAfter(array $old, array $new)
    {
        if($this->j >= count($new) || $this->i >= count($old)) {
            return false;
        }

       return $new[$this->j][0] <= ($old[$this->i][0] - $this->ns);
    }

    public function stepBefore(array $old, array $new)
    {
        $this->ts += ($new[$this->j][1] - $new[$this->j][0]);
        $this->im[] = [$new[$this->j][0], $new[$this->j][0] + $this->ts];
        $this->ns += ($new[$this->j][1] - $new[$this->j][0]);
        $this->j++;
    }

    public function stepEnclosing(array $old, array $new)
    {
        $this->ts += ($new[$this->j][1] - $new[$this->j][0]);
        do {
            $this->ts += ($old[$this->i][1] - $old[$this->i][0]);
            $this->i++;
        } while($this->i < count($old) && ($old[$this->i][0] - $this->ns) <= $new[$this->j][1]);
        $this->im[] = [$new[$this->j][0], $new[$this->j][0] + $this->ts];
        $this->ns += ($new[$this->j][1] - $new[$this->j][0]);
        $this->j++;
    }

    public function stepAfter(array $old, array $new)
    {
        if($this->i < count($old)) {
            $this->im[] = [$old[$this->i][0] - $this->ns, $old[$this->i][1]];
            $this->ts += ($old[$this->i][1] - $old[$this->i][0]);
            $this->i++;
        } else {
            throw \RuntimeException("internal error");
        }
    }
};

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
    $indexMap = [];
    $offset = 0;
    foreach($pieces as $piece) {
        $indexMap[] = [$offset, $piece[1]];
        $offset += strlen($piece[0]);
    }
    return $indexMap;
}


/**
 * Combines one index map array ($new) with another one ($old).
 *
 * This shall be used to implement consecutive string manipulations, where
 * each step produces index map array.
 *
 * @param array $old original index map array from previous steps
 * @param array $new a new index map array to be applied to $old
 *
 * @return array
 */
function imCombine(array $old, array $new) : array
{
    $s = new ImCombineState;
    while(!$s->finished($old, $new)) {
        if($s->isBefore($old, $new)) {
            //
            // $new[$s->j] on the left side of $old[$s->i]
            //
            $s->stepBefore($old, $new);
        } elseif($s->isNotAfter($old, $new)) {
            //
            // $new[$s->j] encloses $old[$s->i] (and perhaps $old[$s->i+1], ...)
            //
            $s->stepEnclosing($old, $new);
        } else {
            //
            // $new[$s->j] on the right side of $old[$s->i]
            //
            $s->stepAfter($old, $new);
        }
    }
    return $s->im;
}


/**
 * Applies index map $im to index value $i returning the mapped index
 * corresponding to $i.
 *
 * @param array $im Index map array.
 * @param int $i An offset to be mapped.
 * @param int $inc Increment. Typically ``$inc=1``, but there are cases when ``$inc=0``.
 * @param int $index Returns the index in $im used to compute the offset
 *
 * @return int The result of mapping.
 */
function imApply(array $im, int $i, int $inc = 1, int &$index = null) : int
{
    $cnt = count($im);

    if ($cnt === 0) {
        $index = null;
        return $i;
    } elseif ($i < $im[0][0]) {
        $index = 0;
    } else {
        $index = imSearch($im, $i);
    }

    return $im[$index][1] + ($i - $im[$index][0]) * $inc;
}

/**
 * Run binary search to find an integer ``$index`` such that
 * ``$im[$index][0] <= $i < $im[$index+1][0]``.
 *
 * The index map array ``$im`` is assumed to be sorted such that ``$im[$j][0] <
 * $im[$j+1][0]`` for every ``$j``. The integer ``$i`` must satisfy
 * ``$im[0][0] <= $i < $im[count($im)-1][0]``.
 *
 * @param $im array
 * @param $i int
 *
 * @return int
 */
function imSearch(array $im, int $i) : int
{
    $l = 0;
    $r = count($im) - 1;

    while ($l <= $r) {
        $m = (int)floor(($l + $r) / 2);
        if($im[$m][0] > $i) {
            $r = $m - 1;
        } elseif(($im[$m+1][0] ?? PHP_INT_MAX) <= $i) {
            $l = $m + 1;
        } else {
            return $m;
        }
    }

    throw new \RuntimeException("internal error: imSearch() failed");
}

// vim: syntax=php sw=4 ts=4 et:
