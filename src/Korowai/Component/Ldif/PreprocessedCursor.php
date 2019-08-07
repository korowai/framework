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
class PreprocessedCursor implements CursorInterface
{
    /**
     * @var Preprocessed
     */
    protected $input;

    /**
     * @var int
     */
    protected $position;


    /**
     * Initializes the error object.
     *
     * @param Preprocessed $input Preprocessed source code.
     * @param int $position Character offset (in bytes) the $input,
     */
    public function __construct(Preprocessed $input, int $position)
    {
        $this->init($input, $position);
    }

    /**
     * Initializes the error object.
     *
     * @param Preprocessed $input Preprocessed source code.
     * @param int $position Character offset (in bytes) the $input,
     */
    public function init(Preprocessed $input, int $position)
    {
        $this->input = $input;
        $this->moveTo($position);
    }

    /**
     * Returns the Preprocessed input provided to the constructor as $input
     *
     * @return Preprocessed
     */
    public function getInput() : Preprocessed
    {
        return $this->input;
    }

    /**
     * Returns the cursor position in the preprocessed string.
     *
     * @return int
     */
    public function getPosition() : int
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceFileName() : string
    {
        return $this->getInput()->getSourceFileName();
    }

    /**
     * {@inheritdoc}
     */
    public function getSource() : string
    {
        return $this->getInput()->getSource();
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceByteOffset() : int
    {
        return $this->getInput()->getSourceByteOffset($this->getPosition());
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceCharOffset(string $encoding=null) : int
    {
        return $this->getInput()->getSourceCharOffset($this->getPosition(), ...(func_get_args()));
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceLineIndex() : int
    {
        return $this->getInput()->getSourceLineIndex($this->getPosition());
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceLine(int $index=null) : string
    {
        return $this->getInput()->getSourceLine($index ?? $this->getSourceLineIndex());
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceLineAndByte() : array
    {
        return $this->getInput()->getSourceLineAndByte($this->getPosition());
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceLineAndChar(string $encoding=null) : array
    {
        return $this->getInput()->getSourceLineAndChar($this->getPosition(), ...(func_get_args()));
    }

    /**
     * {@inheritdoc}
     */
    public function moveBy(int $offset) : CursorInterface
    {
        $this->position += $offset;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function moveTo(int $position) : CursorInterface
    {
        $this->position = $position;
        return $this;
    }
}

// vim: syntax=php sw=4 ts=4 et:
