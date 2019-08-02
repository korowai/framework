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
 * LDIF parser.
 */
class Error
{
    /**
     * @var string
     */
    protected $message;

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
     * @param string $message Error message.
     * @param Preprocessed $input Preprocessed source code.
     * @param int $offset Character offset in the $input, where the error occurred.
     */
    public function __construct(string $message, Preprocessed $input, int $offset)
    {
        $this->message = $message;
        $this->input = $input;
        $this->offset = $offset;
    }

    /**
     * Returns the error message provided to constructor as $message
     *
     * @return string
     */
    public function getMessage() : string
    {
        return $this->message;
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
     * ``$err->getInput()->getSource()``,
     * where ``$err`` is an Error object.
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
     * ``$err->getInput()->getSourceIndex($err->getOffset())``,
     * where ``$err`` is an Error object.
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
     * ``$err->getInput()->getSourceLineIndex($err->getOffset())``,
     * where ``$err`` is an Error object.
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
     * ``$err->getInput()->getSourceLine($err->getSourceLineIndex())``,
     * where ``$err`` is an Error object.
     *
     * @return string
     */
    public function getSourceLine() : string
    {
        return $this->getInput()->getSourceLine($this->getSourceLineIndex());
    }

    /**
     * Returns input's name (file name) as string.
     *
     * Equivalent to
     * ``$err->getInput()->getInputName()``
     * where ``$err`` is an Error object.
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
    public function getLocation() : string
    {
        $file = $this->getInputName();
        $line = (string)(1 + $this->getSourceLineIndex());
        return implode(':', [$file, $line]);
    }

    public function getFullMessage()
    {
        $loc = $this->getLocation();
        $message = $this->getMessage();
        return implode(':', [$loc, $message]);
    }
}

// vim: syntax=php sw=4 ts=4 et:
