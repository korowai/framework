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
 * Cursor object. Points at a character of preprocessed input.
 */
class Cursor
{
    /**
     * @var Preprocessed
     */
    protected $input;

    /**
     * @var int
     */
    protected $offset;


    /**
     * Initializes the error object.
     *
     * @param Preprocessed $input Preprocessed source code.
     * @param int $offset Character offset (in bytes) the $input,
     */
    public function __construct(Preprocessed $input, int $offset)
    {
        $this->init($input, $offset);
    }

    public function init(Preprocessed $input, int $offset)
    {
        $this->input = $input;
        $this->offset = $offset;
    }

    /**
     * Returns the input provided to the constructor as $input
     *
     * @return Preprocessed
     */
    public function getInput() : Preprocessed
    {
        return $this->input;
    }

    /**
     * Returns the character offset provided to the constructor as $offset
     *
     * @return int
     */
    public function getOffset() : int
    {
        return $this->offset;
    }

    /**
     * Returns the source string of the $input.
     *
     * Equivalent to
     * ``$cur->getInput()->getSource()``,
     * where ``$cur`` is a Cursor object.
     *
     * @return string
     */
    public function getSource() : string
    {
        return $this->getInput()->getSource();
    }

    /**
     * Returns the character index in source string corresponding to $offset.
     *
     * Equivalent to
     * ``$cur->getInput()->getSourceIndex($cur->getOffset())``,
     * where ``$cur`` is a Cursor object.
     *
     * @return int
     */
    public function getSourceIndex() : int
    {
        return $this->getInput()->getSourceIndex($this->getOffset());
    }

    /**
     * Returns the source line index corresponding to $offset.
     *
     * Equivalent to
     * ``$cur->getInput()->getSourceLineIndex($cur->getOffset())``,
     * where ``$cur`` is a Cursor object.
     *
     * @return int
     */
    public function getSourceLineIndex() : int
    {
        return $this->getInput()->getSourceLineIndex($this->getOffset());
    }

    /**
     * Returns the whole source line as string where the error occurred.
     *
     * Equivalent to
     * ``$cur->getInput()->getSourceLine($cur->getSourceLineIndex())``,
     * where ``$cur`` is a Cursor object.
     *
     * @return string
     */
    public function getSourceLine() : string
    {
        return $this->getInput()->getSourceLine($this->getSourceLineIndex());
    }

    /**
     * Returns the line + char offset as string where the error occurred.
     *
     * Equivalent to
     * ``$cur->getInput()->getSourceLineAndCharIndex($cur->getOffset())``,
     * where ``$cur`` is a Cursor object.
     *
     * @return array
     */
    public function getSourceLineAndCharIndex() : array
    {
        return $this->getInput()->getSourceLineAndCharIndex($this->getOffset());
    }

    /**
     * Returns the line + char offset as string where the error occurred.
     *
     * Equivalent to
     * ``$cur->getInput()->getSourceLineAndMbCharIndex($cur->getOffset())``,
     * where ``$cur`` is a Cursor object.
     *
     * @return array
     */
    public function getSourceLineAndMbCharIndex() : array
    {
        return $this->getInput()->getSourceLineAndMbCharIndex($this->getOffset());
    }

    /**
     * Returns input's name (file name) as string.
     *
     * Equivalent to
     * ``$cur->getInput()->getInputName()``
     * where ``$cur`` is a Cursor object.
     *
     * @return string
     */
    public function getInputName() : string
    {
        return $this->getInput()->getInputName();
    }

    /**
     * Returns the error location as a string.
     *
     * @return string
     */
    public function getLocationString() : string
    {
        $file = $this->getInputName();
        [$line, $char] = $this->getSourceLineAndMbCharIndex();
        return implode(':', array_merge([$file, $line+1, $char+1]));
    }

    /**
     * Returns a string that includes source location and error message.
     */
    public function getFullMessage()
    {
        $loc = $this->getLocationString();
        $message = $this->getMessage();
        return implode(':', [$loc, $message]);
    }

    public function getIndicatorString()
    {
        [$line, $char] = $this->getSourceLineAndMbCharIndex();
        return str_repeat(' ', $char) . '^';
    }
}

// vim: syntax=php sw=4 ts=4 et:
