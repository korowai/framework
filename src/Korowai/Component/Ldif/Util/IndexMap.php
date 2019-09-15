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
 * in other words, assembling resultant string from certain pieces of the
 * source string.
 */
class IndexMap
{
    /**
     * @var array
     */
    protected $array;

    /**
     * @var int
     */
    protected $increment;

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
    public static function arrayFromPieces(array $pieces) : array
    {
        $indexMap = [];
        $offset = 0;
        foreach ($pieces as $piece) {
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
    public static function arrayCombine(array $old, array $new) : array
    {
        return (new IndexMapArrayCombineAlgorithm)($old, $new);
    }


    /**
     * Applies index map array $im to index value $i returning the mapped index
     * corresponding to $i.
     *
     * @param array $im Index map array.
     * @param int $i An offset to be mapped.
     * @param int $inc Increment. Typically ``$inc=1``, but there are cases when ``$inc=0``.
     * @param int $index Returns the index in $im used to compute the offset
     *
     * @return int The result of mapping.
     */
    public static function arrayApply(array $im, int $i, int $inc = 1, int &$index = null) : int
    {
        $cnt = count($im);

        if ($cnt === 0) {
            $index = null;
            return $i;
        } elseif ($i < $im[0][0]) {
            $index = 0;
        } else {
            $index = self::arraySearch($im, $i);
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
    public static function arraySearch(array $im, int $i) : int
    {
        $l = 0;
        $r = count($im) - 1;

        while ($l <= $r) {
            $m = (int)floor(($l + $r) / 2);
            if ($im[$m][0] > $i) {
                $r = $m - 1;
            } elseif (($im[$m+1][0] ?? PHP_INT_MAX) <= $i) {
                $l = $m + 1;
            } else {
                return $m;
            }
        }

        throw new \RuntimeException("internal error: arraySearch() failed");
    }

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
     * @param int $increment
     *
     * @return array
     */
    public static function createFromPieces(array $pieces, int $increment = 1)
    {
        $array = self::arrayFromPieces($pieces);
        return new self($array, $increment);
    }

    /**
     * Initializes the object.
     */
    public function __construct(array $array, int $increment = 1)
    {
        $this->array = $array;
        $this->increment = $increment;
    }

    /**
     * Returns index map array maintained by the IndexMap object.
     *
     * @return array
     */
    public function getArray() : array
    {
        return $this->array;
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
     *                   map array (getArray()) used to compute the offset.
     *
     * @return int The result of mapping.
     */
    public function apply(int $i, int &$index = null) : int
    {
        return self::arrayApply($this->getArray(), $i, $this->getIncrement(), $index);
    }

    /**
     * Returns the mapped index corresponding to $i.
     *
     * @param int $i An offset to be mapped.
     * @param int $index Returns the index of the entry in the internal index
     *                   map array (getArray()) used to compute the offset.
     *
     * @return int The result of mapping.
     */
    public function __invoke(int $i, int &$index = null) : int
    {
        return $this->apply($i, $index);
    }

    /**
     * Combines this index map with the index map array $new.
     *
     * This shall be used to implement consecutive string manipulations, where
     * each step produces index map.
     *
     * @param array $new a new index map array to be applied to $this
     *
     */
    public function combineWithArray(array $array) : IndexMap
    {
        $this->array = self::arrayCombine($this->getArray(), $array);
        return $this;
    }

    /**
     * Combines this index map with the index map $new.
     *
     * This shall be used to implement consecutive string manipulations, where
     * each step produces index map array.
     *
     * @param array $new a new index map array to be applied to $this
     *
     */
    public function combineWith(IndexMap $im) : IndexMap
    {
        $this->combineWithArray($im->getArray());
        return $this;
    }
}

// vim: syntax=php sw=4 ts=4 et:
