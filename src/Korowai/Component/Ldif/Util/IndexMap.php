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

///**
// * Index map is used to map (byte) offsets in a preprocessed string onto
// * corresponding (byte) offsets in the original string. The preprocessing is
// * assumed to be a process of removing certain parts from source string or,
// * in other words, assembling resultant string from pieces of the source
// * string.
// */
//class IndexMap
//{
//    /**
//     * @var array
//     */
//    protected $indexMap;
//
//    /**
//     * @var int
//     */
//    protected $increment;
//
//    /**
//     * Creates IndexMap for a string made out of pieces of other string.
//     *
//     * ``$pieces`` must be an array where every element is an array consisting
//     * of a substring of the original string at offset 0 and its string offset
//     * into original string at offset 1. Such an array is returned by
//     * ``preg_split(..., PREG_SPLIT_OFFSET_CAPTURE)``.
//     *
//     * @param array $pieces Pieces of the original string that will form the
//     *                      resultant string (see function description above)
//     * @return array
//     */
//    public static function createFromPieces(array $pieces, int $increment = 1)
//    {
//        $offset = 0;
//        $indexMap = array_map(
//            function ($piece) use (&$offset) {
//                $entry = [$offset, $piece[1]];
//                $offset += strlen($piece[0]);
//                return $entry;
//            },
//            $pieces
//        );
//        return new self($indexMap, $increment);
//    }
//
//    /**
//     * Initializes the object.
//     */
//    public function __construct(array $indexMap, int $increment = 1)
//    {
//        $this->indexMap = $indexMap;
//        $this->increment = $increment;
//    }
//
//    /**
//     * Returns index map array maintained by the IndexMap object.
//     *
//     * @return array
//     */
//    public function getIndexMap() : array
//    {
//        return $this->indexMap;
//    }
//
//    /**
//     * Returns the default increment encapsulated by the IndexMap object.
//     *
//     * @return int
//     */
//    public function getIncrement() : int
//    {
//        return $this->increment;
//    }
//
//    /**
//     * Returns the mapped index corresponding to $i.
//     *
//     * @param int $i An offset to be mapped.
//     * @param int $index Returns the index of the entry in the internal index
//     *                   map array (getIndexMap()) used to compute the offset.
//     *
//     * @return int The result of mapping.
//     */
//    public function __invoke(int $i, int &$index = null) : int
//    {
//        $indexMap = $this->getIndexMap();
//        $cnt = count($indexMap);
//        if ($cnt === 0 || $i < $indexMap[0][0]) {
//            return $i;
//        } elseif($i >= $indexMap[$cnt-1][0]) {
//            $index = $cnt - 1;
//        } else {
//            $index = $this->bisect($i);
//        }
//
//        $increment = $indexMap[$index][2] ?? $this->getIncrement();
//        return $indexMap[$index][1] + ($i - $indexMap[$index][0]) * $increment;
//    }
//
//    /**
//     * Applies an index map array ($new) over this one.
//     *
//     * This shall be used to implement consecutive string manipulations, where
//     * each step produces index map array.
//     *
//     * @param array $new a new index map array to be applied to $this
//     *
//     */
//    public function combine(array $new)
//    {
//        $im = [];
//        $old = $this->indexMap;
//        $ns = 0; // new shrink (introduced by $new)
//        $ts = 0; // total shrink (cumulation of $old and $new)
//        for($i=0, $j=0; $i < count($old) || $j < count($new); ) {
//            if($j < count($new) && ($i >= count($old) || $new[$j][1] < ($old[$i][0] - $ns))) {
//                //
//                // $new[$j] on the left side of $old[$i]
//                //
//                $ts += ($new[$j][1] - $new[$j][0]);
//                $im[] = [$new[$j][0], $new[$j][0] + $ts];
//                $ns += ($new[$j][1] - $new[$j][0]);
//                $j++;
//            } elseif($j < count($new) && $i < count($old) && $new[$j][0] <= ($old[$i][0] - $ns)) {
//                //
//                // $new[$j] encloses $old[$i] (and perhaps $old[$i+1], ...)
//                //
//                $ts += ($new[$j][1] - $new[$j][0]);
//                do {
//                    $ts += ($old[$i][1] - $old[$i][0]);
//                    $i++;
//                } while($i < count($old) && ($old[$i][0] - $ns) <= $new[$j][1]);
//                $im[] = [$new[$j][0], $new[$j][0] + $ts];
//                $ns += ($new[$j][1] - $new[$j][0]);
//                $j++;
//            } elseif($i < count($old)) {
//                //
//                // $new[$j] on the right side of $old[$i]
//                //
//                $im[] = [$old[$i][0] - $ns, $old[$i][1]];
//                $ts += ($old[$i][1] - $old[$i][0]);
//                $i++;
//            } else {
//                throw \RuntimeException("internal error");
//            }
//        }
//        $this->indexMap = $im;
//
//        return $this;
//    }
//
//    protected function bisect(int $i)
//    {
//        $cnt = count($this->indexMap);
//
//        $lo = 0;
//        $hi = $cnt - 1;
//
//        $iter = 0;
//        while($hi - $lo > 1) {
//            if($iter > 2 * $cnt) {
//                throw new \RuntimeException("internal error: iteration count exceeded");
//            }
//            $mid = floor(($lo + $hi) / 2);
//            if($i < $this->indexMap[$mid][0]) {
//                $hi = $mid;
//            } else {
//                $lo = $mid;
//            }
//            $iter++;
//        }
//
//        return $lo;
//    }
//
//    protected static composeIndexMaps(array $old, array $new) : array
//    {
//        $im = [];
//        $ns = 0; // new shrink (introduced by $new)
//        $ts = 0; // total shrink (cumulation of $old and $new)
//        for($i=0, $j=0; $i < count($old) || $j < count($new); ) {
//            if($j < count($new) && ($i >= count($old) || $new[$j][1] < ($old[$i][0] - $ns))) {
//                //
//                // $new[$j] on the left side of $old[$i]
//                //
//                $ts += ($new[$j][1] - $new[$j][0]);
//                $im[] = [$new[$j][0], $new[$j][0] + $ts];
//                $ns += ($new[$j][1] - $new[$j][0]);
//                $j++;
//            } elseif($j < count($new) && $i < count($old) && $new[$j][0] <= ($old[$i][0] - $ns)) {
//                //
//                // $new[$j] encloses $old[$i] (and perhaps $old[$i+1], ...)
//                //
//                $ts += ($new[$j][1] - $new[$j][0]);
//                do {
//                    $ts += ($old[$i][1] - $old[$i][0]);
//                    $i++;
//                } while($i < count($old) && ($old[$i][0] - $ns) <= $new[$j][1]);
//                $im[] = [$new[$j][0], $new[$j][0] + $ts];
//                $ns += ($new[$j][1] - $new[$j][0]);
//                $j++;
//            } elseif($i < count($old)) {
//                //
//                // $new[$j] on the right side of $old[$i]
//                //
//                $im[] = [$old[$i][0] - $ns, $old[$i][1]];
//                $ts += ($old[$i][1] - $old[$i][0]);
//                $i++;
//            } else {
//                throw \RuntimeException("internal error");
//            }
//        }
//    }
//
//    protected static foo(array $old)
//}

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

class ImCombineState
{
    public $im = [];
    public $i = 0;
    public $j = 0;
    public $ns = 0; // new shrink (introduced by $new)
    public $ts = 0; // total shrink (accumulation of $old and $new)
};

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
    for($s->i=0, $s->j=0; $s->i < count($old) || $s->j < count($new); ) {
        if(imCombineIsBefore($s, $old, $new)) {
            //
            // $new[$s->j] on the left side of $old[$s->i]
            //
            imCombineWhenBefore($s, $old, $new);
        } elseif(imCombineIsNotAfter($s, $old, $new)) {
            //
            // $new[$s->j] encloses $old[$s->i] (and perhaps $old[$s->i+1], ...)
            //
            imCombineWhenEnclosing($s, $old, $new);
        } elseif($s->i < count($old)) {
            //
            // $new[$s->j] on the right side of $old[$s->i]
            //
            imCombineWhenAfter($s, $old, $new);
        } else {
            throw \RuntimeException("internal error");
        }
    }
    return $s->im;
}

function imCombineIsBefore(object $s, array $old, array $new)
{
    if($s->j >= count($new)) {
        return false;
    }
    if($s->i >= count($old)) {
        return true;
    }
    return $new[$s->j][1] < ($old[$s->i][0] - $s->ns);
}

function imCombineIsNotAfter(object $s, array $old, array $new)
{
    if($s->j >= count($new) || $s->i >= count($old)) {
        return false;
    }

   return $new[$s->j][0] <= ($old[$s->i][0] - $s->ns);
}

function imCombineWhenBefore(object $s, array $old, array $new)
{
    $s->ts += ($new[$s->j][1] - $new[$s->j][0]);
    $s->im[] = [$new[$s->j][0], $new[$s->j][0] + $s->ts];
    $s->ns += ($new[$s->j][1] - $new[$s->j][0]);
    $s->j++;
}

function imCombineWhenEnclosing(object $s, array $old, array $new)
{
    $s->ts += ($new[$s->j][1] - $new[$s->j][0]);
    do {
        $s->ts += ($old[$s->i][1] - $old[$s->i][0]);
        $s->i++;
    } while($s->i < count($old) && ($old[$s->i][0] - $s->ns) <= $new[$s->j][1]);
    $s->im[] = [$new[$s->j][0], $new[$s->j][0] + $s->ts];
    $s->ns += ($new[$s->j][1] - $new[$s->j][0]);
    $s->j++;
}

function imCombineWhenAfter(object $s, array $old, array $new)
{
    $s->im[] = [$old[$s->i][0] - $s->ns, $old[$s->i][1]];
    $s->ts += ($old[$s->i][1] - $old[$s->i][0]);
    $s->i++;
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

    if ($cnt === 0 || $i < $im[0][0]) {
        return $i;
    } elseif($i >= $im[$cnt-1][0]) {
        $index = $cnt -1 ;
    } else {
        $index = imSearch($old, $i);
    }

    $inc = $im[$index][2] ?? 1;
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
    $cnt = count($im);

    $lo = 0;
    $hi = $cnt - 1;

    if($i < $im[0][0] || $i >= $im[$hi][0]) {
        throw new \RuntimeException("argument 2 to " . __FUNCTION__ . " is out of range");
    }

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

    return $lo;
}

// vim: syntax=php sw=4 ts=4 et:
