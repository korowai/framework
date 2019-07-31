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
class PpString
{
    use \Korowai\Component\Ldif\Util\IndexMapApply;

    /**
     * @var string
     */
    protected $source;

    /**
     * @var string
     */
    protected $string;

    /**
     * @var array
     */
    protected $im;


    public function __construct(string $source, string $string, array $im)
    {
        $this->init($source, $string, $im);
    }

    public function init(string $source, string $string, array $im)
    {
        $this->source = $source;
        $this->string = $string;
        $this->im = $im;
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
     * parameters.
     *
     * @return array
     */
    public function getIm() : array
    {
        return $this->im;
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
     * Given the character $offset in the preprocessed string, this function
     * returns its corresponding character offset in the original $source
     * string.
     *
     * @pararm int $i character offset in the preprocessed string
     *
     * @return int the resultant offset of the corresponding character in
     *             $source string
     */
    public function getSourceIndex(int $i) : int
    {
        return self::imApply($this->getIm(), $i);
    }
}

// vim: syntax=php sw=4 ts=4 et:
