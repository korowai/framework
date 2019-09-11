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
 * Index map is used to map (byte) offsets in a preprocessed string onto
 * corresponding (byte) offsets in the original string. The preprocessing is
 * assumed to be a process of removing certain parts from source string or,
 * in other words, assembling resultant string from pieces of the source
 * string.
 */
class IndexMap
{
    /**
     * @var array
     */
    protected $indexMap;

    /**
     * @var int
     */
    protected $increment;

    /**
     * Creates IndexMap for a string made out of pieces of other string.
     *
     * ``$pieces`` must be an array where every element is an array consisting
     * of a substring of the original string at offset 0 and its string offset
     * into original string at offset 1. Such an array is returned by
     * ``preg_split(..., PREG_SPLIT_OFFSET_CAPTURE)``.
     *
     * @param array $pieces Pieces of the original string that will form the
     *                      resultant string (see function description above)
     * @return array
     */
    public static function createFromPieces(array $pieces, int $increment = 1)
    {
        $offset = 0;
        $indexMap = array_map(
            function ($piece) use (&$offset) {
                $entry = [$offset, $piece[1]];
                $offset += strlen($piece[0]);
                return $entry;
            },
            $pieces
        );
        return new self($indexMap, $increment);
    }

    /**
     * Initializes the object.
     */
    public function __construct(array $indexMap, int $increment = 1)
    {
        $this->indexMap = $indexMap;
        $this->increment = $increment;
    }

    /**
     * Returns index map array maintained by the IndexMap object.
     *
     * @return array
     */
    public function getIndexMap() : array
    {
        return $this->indexMap;
    }

    /**
     * Returns the default increment encapsulated by the IndexMap object.
     *
     * @return int
     */
    public function getIncrement() : int
    {
        return $this->increment;
    }

    /**
     * Returns the mapped index corresponding to $i.
     *
     * @param int $i An offset to be mapped.
     * @param int $index Returns the index of the entry in the internal index
     *                   map array (getIndexMap()) used to compute the offset.
     *
     * @return int The result of mapping.
     */
    public function __invoke(int $i, int &$index = null) : int
    {
        $indexMap = $this->getIndexMap();
        $cnt = count($indexMap);
        if ($cnt === 0 || $i < $indexMap[0][0]) {
            return $i;
        } elseif($i >= $indexMap[$cnt-1][0]) {
            $index = $cnt - 1;
        } else {
            $index = $this->bisect($i);
        }

        $increment = $indexMap[$index][2] ?? $this->getIncrement();
        return $indexMap[$index][1] + ($i - $indexMap[$index][0]) * $increment;
    }

    /**
     * Applies an index map array ($indexMap) over this one.
     *
     * This shall be used to implement consecutive string manipulations, where
     * each step produces index map array.
     *
     * @param array $indexMap a new index map array to be applied to $this
     *
     */
    public function apply(array $indexMap)
    {
        $new = [];
        $old = $this->indexMap;
        $ns = 0; // new shrink (introduced by $indexMap)
        $ts = 0; // total shrink (cumulation of $old and $indexMap)
        for($i=0, $j=0; $i < count($old) || $j < count($indexMap); ) {
            if($j < count($indexMap) && ($i >= count($old) || $indexMap[$j][1] < ($old[$i][0] - $ns))) {
                //
                // $indexMap[$j] on the left side of $old[$i]
                //
                $ts += ($indexMap[$j][1] - $indexMap[$j][0]);
                $new[] = [$indexMap[$j][0], $indexMap[$j][0] + $ts];
                $ns += ($indexMap[$j][1] - $indexMap[$j][0]);
                $j++;
            } elseif($j < count($indexMap) && $i < count($old) && $indexMap[$j][0] <= ($old[$i][0] - $ns)) {
                //
                // $indexMap[$j] encloses $old[$i] (and perhaps $old[$i+1], ...)
                //
                $ts += ($indexMap[$j][1] - $indexMap[$j][0]);
                do {
                    $ts += ($old[$i][1] - $old[$i][0]);
                    $i++;
                } while($i < count($old) && ($old[$i][0] - $ns) <= $indexMap[$j][1]);
                $new[] = [$indexMap[$j][0], $indexMap[$j][0] + $ts];
                $ns += ($indexMap[$j][1] - $indexMap[$j][0]);
                $j++;
            } elseif($i < count($old)) {
                //
                // $indexMap[$j] on the right side of $old[$i]
                //
                $new[] = [$old[$i][0] - $ns, $old[$i][1]];
                $ts += ($old[$i][1] - $old[$i][0]);
                $i++;
            } else {
                throw \RuntimeException("internal error");
            }
        }
        $this->indexMap = $new;

        return $this;
    }

    protected function bisect(int $i)
    {
        $cnt = count($this->indexMap);

        $lo = 0;
        $hi = $cnt - 1;

        $iter = 0;
        while($hi - $lo > 1) {
            if($iter > 2 * $cnt) {
                throw new \RuntimeException("internal error: iteration count exceeded");
            }
            $mid = floor(($lo + $hi) / 2);
            if($i < $this->indexMap[$mid][0]) {
                $hi = $mid;
            } else {
                $lo = $mid;
            }
            $iter++;
        }

        return $lo;
    }
}

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
 * Applies index map $indexMap to index value $i returning the mapped index
 * corresponding to $i.
 *
 * @param array $indexMap Index map array.
 * @param int $i An offset to be mapped.
 * @param int $index Returns the index in $indexMap used to compute the offset
 *
 * @return int The result of mapping.
 */
function imApply(array $indexMap, int $i, int &$index=null) : int
{
    $cnt = count($indexMap);

    $lo = 0;
    $hi = $cnt - 1;

    if($cnt === 0) {
        return $i;
    }

    if ($i < $indexMap[0][0]) {
        return $i;
    } elseif($i >= $indexMap[$hi][0]) {
        $index = $hi;
        $increment = $indexMap[$hi][2] ?? 1;
        return $indexMap[$hi][1] + ($i - $indexMap[$hi][0]) * $increment;
    } else {
        $iter = 0;
        while($hi - $lo > 1) {
            if($iter > 2 * $cnt) {
                throw new \RuntimeException("internal error: iteration count exceeded");
            }
            $mid = floor(($lo + $hi) / 2);
            if($i < $indexMap[$mid][0]) {
                $hi = $mid;
            } else {
                $lo = $mid;
            }
            $iter++;
        }
        $index = $lo;
        $increment = $indexMap[$lo][2] ?? 1;
        return $indexMap[$lo][1] + ($i - $indexMap[$lo][0]) * $increment;
    }
}

// vim: syntax=php sw=4 ts=4 et:
