<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Component\Ldif;

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
        $array = Util\imFromPieces($pieces);
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
        return Util\imApply($this->getArray(), $i, $this->getIncrement(), $index);
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
        $this->array = Util\imCombine($this->getArray(), $array);
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
