<?php
/**
 * @file src/Korowai/Lib/Ldif/Location.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif;

/**
 * Points at a character of preprocessed input.
 */
class Location implements LocationInterface
{
    /**
     * @var Input
     */
    protected $input;

    /**
     * @var int
     */
    protected $position;


    /**
     * Initializes the error object.
     *
     * @param InputInterface $input Preprocessed source code.
     * @param int $position Character offset (in bytes) the $input,
     */
    public function __construct(InputInterface $input, int $position = 0)
    {
        $this->init($input, $position);
    }

    /**
     * Initializes the error object.
     *
     * @param InputInterface $input Preprocessed source code.
     * @param int $position Character offset (in bytes) the $input,
     */
    public function init(InputInterface $input, int $position)
    {
        $this->input = $input;
        $this->position = $position;
    }

    /**
     * Returns the Preprocessed input provided to the constructor as $input
     *
     * @return InputInterface
     */
    public function getInput() : InputInterface
    {
        return $this->input;
    }

    /**
     * {@inheritdoc}
     */
    public function getString() : string
    {
        return $this->getInput()->getString();
    }

    /**
     * {@inheritdoc}
     */
    public function getOffset() : int
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function getCharOffset(string $encoding = null) : int
    {
        $substring = substr($this->getString(), 0, $this->getOffset());
        return mb_strlen($substring, ...(func_get_args()));
    }

//    /**
//     * Returns the substring on the right side of cursor.
//     *
//     * @param int $len substring length.
//     *
//     * @return string
//     */
//    public function getRightSubstr(int $len = null) : string
//    {
//        return substr($this->getString(), $this->getOffset(), ...(func_get_args()));
//    }
//
//    /**
//     * Returns the substring on the left side of cursor.
//     *
//     * @param int $len substring length.
//     *
//     * @return string
//     */
//    public function getLeftSubstr(int $len = null) : string
//    {
//        $pos = $this->getOffset();
//        if(isset($len)) {
//            $beg = max($pos - $len, 0);
//        } else {
//            $beg = 0;
//        }
//        $len = $pos - $beg;
//        return substr($this->getString(), $beg, $len);
//    }

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
    public function getSourceString() : string
    {
        return $this->getInput()->getSourceString();
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceOffset() : int
    {
        return $this->getInput()->getSourceOffset($this->getOffset());
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceCharOffset(string $encoding = null) : int
    {
        return $this->getInput()->getSourceCharOffset($this->getOffset(), ...(func_get_args()));
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceLineIndex() : int
    {
        return $this->getInput()->getSourceLineIndex($this->getOffset());
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceLine(int $index = null) : string
    {
        return $this->getInput()->getSourceLine($index ?? $this->getSourceLineIndex());
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceLineAndOffset() : array
    {
        return $this->getInput()->getSourceLineAndOffset($this->getOffset());
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceLineAndCharOffset(string $encoding = null) : array
    {
        return $this->getInput()->getSourceLineAndCharOffset($this->getOffset(), ...(func_get_args()));
    }
}

// vim: syntax=php sw=4 ts=4 et: