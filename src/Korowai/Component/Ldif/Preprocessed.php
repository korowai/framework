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
 * Preprocessed string.
 *
 * Encapsulates preprocessed string together with its original (source) string.
 * The object is able to map character offsets pointing to characters of the
 * preprocessed string onto their corresponding character offsets in the source
 * string. It also provides a service of mapping character offsets in the
 * preprocessed string onto their corresponding line numbers in the source
 * string.
 */
class Preprocessed
{
    use Util\IndexMapApply;

    /**
     * @var string
     */
    protected $source;

    /**
     * @var string
     */
    protected $string;

    /**
     * Index map. An array that helps mapping preprocessed text's character
     * offsets onto corresponding offsets in the original string.
     *
     * @var array
     */
    protected $im;

    /**
     * Input name (file name).
     *
     * @var string
     */
    protected $input;

    /**
     * Line map. An array that helps mapping source text's character offsets
     * onto their corresponding line numbers.
     */
    protected $sourceLinesMap;

    /**
     * @var array
     */
    protected $sourceLines;

    /**
     * Initializes the object
     *
     * @param string $source Source string
     * @param string $string Preprocessed string
     * @param array $im Index map produced by preprocessor
     * @param string $input Input name (file name)
     */
    public function __construct(string $source, string $string, array $im, string $input=null)
    {
        $this->init($source, $string, $im, $input);
    }

    /**
     * Initializes the object
     *
     * @param string $source Source string
     * @param string $string Preprocessed string
     * @param array $im Index map produced by preprocessor
     * @param string $input Input name (file name)
     */
    public function init(string $source, string $string, array $im, string $input=null)
    {
        $this->source = $source;
        $this->string = $string;
        $this->im = $im;
        $this->input = $input ?? '-';
        $this->sourceLines = null;
        $this->sourceLinesMap = null;
    }

    /**
     * Returns the original source string, as provided to constructor via
     * $source parameter.
     *
     * @return string
     */
    public function getSource() : string
    {
        return $this->source;
    }

    /**
     * Returns the preprocessed string, as provided to constructor via $string
     * parameter.
     *
     * @return string
     */
    public function getString() : string
    {
        return $this->string;
    }

    /**
     * Returns the "array of im", as provided to constructor via $im
     * parameter.
     *
     * @return array
     */
    public function getIndexMap() : array
    {
        return $this->im;
    }

    /**
     * Returns the input name provided to constructor via $input parameter.
     *
     * @return string
     */
    public function getInputName() : string
    {
        return $this->input;
    }

    /**
     * Sets the input name (file name).
     *
     * @param string $input
     *
     * @return Preprocessed
     */
    public function setInputName(string $input)
    {
        $this->input = $input;
        return $this;
    }

    /**
     * Returns the preprocessed string, as provided to constructor via $string
     * parameter.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getString();
    }

    /**
     * Given a character offset $i in the preprocessed string, returns its
     * corresponding character offset in the original $source string.
     *
     * @pararm int $i character offset in the preprocessed string
     *
     * @return int the resultant offset of the corresponding character in
     *             $source string
     */
    public function getSourceIndex(int $i) : int
    {
        return self::imApply($this->getIndexMap(), $i);
    }

    /**
     * Returns array of strings resulted from splitting the $source into lines.
     *
     * @return array
     */
    public function getSourceLines() : array
    {
        if(!isset($this->sourceLines)) {
            $this->initSourceLines($this->getSource());
        }
        return $this->sourceLines;
    }

    /**
     * Returns $i'th line of the source string.
     *
     * @param int $i
     *
     * @return string
     */
    public function getSourceLine(int $i) : string
    {
        return ($this->getSourceLines())[$i];
    }

    /**
     * Returns the sourceLinesMap array.
     *
     * The sourceLinesMap array is used to map character offsets in the source
     * string onto their corresponding line numbers (zero based). The array is
     * internally used by ``getSrouceLineIndex()``.
     *
     * @return array
     */
    public function getSourceLinesMap() : array
    {
        if(!isset($this->sourceLinesMap)) {
            $this->initSourceLines($this->getSource());
        }
        return $this->sourceLinesMap;
    }

    /**
     * Given a character offset $i in the preprocessed string, returns its
     * corresponding line number (zero-based) in the original $source string.
     *
     * @param int $i Character offset in the preprocessed string
     *
     * @return int
     */
    public function getSourceLineIndex(int $i) : int
    {
        $j = $this->getSourceIndex($i);
        return self::imApply($this->getSourceLinesMap(), $j);
    }

    /**
     * Given a character offset $i in the preprocessed string, returns its
     * corresponding line number in the $source string (zero-based) and the
     * charcter offset relative to the beginning of the source line.
     *
     * @param int $i Character offset in the preprocessed string
     *
     * @return array 2-element array with line index at position 0 and
     *               character offset at position 1.
     */
    public function getSourceLocation(int $i) : array
    {
        $j = $this->getSourceIndex($i);
        $map = $this->getSourceLinesMap();
        $line = self::imApply($map, $j, $lo);
//        $arr = array_filter(function ($m) use ($line) {
//            return $m[1] == $line;
//        }, $map);
//        if(count($arr) == 1) {
//        }
//        if($line < 0) {
//            $offset = 0;
//        } elseif($line >= count($map) {
//            $lines = $this->getLines();
//            $count = count($lines);
//            if($count > 0) {
//                $offset = strlen($lines[$count-1]);
//            } else {
//                $offset = 0;
//            }
//        } else {
//            $offset = $j - $map[$line][0];
//        }
        return [$line, $offset];
    }

    protected function initSourceLines(string $source)
    {
        $re = '/(?:\r\n|\n)/m';
        $pieces = preg_split($re, $source, -1, PREG_SPLIT_OFFSET_CAPTURE);

        $cnt = count($pieces);

        $lm = [[-PHP_INT_MAX, -1, 0]];
        for($i = 0; $i < $cnt; $i++) {
            $lm[] = [$pieces[$i][1], $i, 0];
        }

        $this->sourceLines = array_map(function ($p) { return $p[0]; }, $pieces);
        $this->sourceLinesMap = $lm;
    }
}

// vim: syntax=php sw=4 ts=4 et:
