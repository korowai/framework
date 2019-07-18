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

class LogicalLine
{
    /**
     * @var string
     *
     * The content of the logical line as given in file.
     */
    protected $content;

    /**
     * @var int
     *
     * The number of the physical line where the logical line begins in the original text.
     */
    protected $startLine;

    /**
     * @var int
     *
     * The number of physical lines of this text.
     */
    protected $linesCount;

    /**
     * Initializes the object
     *
     * @param string $content
     * @param int $startLine
     */
    public function __construct(string $content, int $startLine=0)
    {
        $this->init($content, $startLine);
    }

    /**
     * Initializes the object
     *
     * @param string $content
     * @param int $startLine
     */
    public function init(string $content, int $startLine=0)
    {
        $this->content = $content;
        $this->startLine = $startLine;
        $this->linesCount = count(preg_split('/(\r\n|\n)/', $content));
    }

    /**
     * Returns the $content provided to __construct()
     *
     * @return string
     */
    public function getContent() : string
    {
        return $this->content;
    }

    /**
     * Returns the $startLine provided to __construct()
     *
     * @return int
     */
    public function getStartLine() : int
    {
        return $this->startLine;
    }

    /**
     * Returns the lines count of the $content
     *
     * @return int
     */
    public function getLinesCount() : int
    {
        return $this->linesCount;
    }

    /**
     * Returns the index of the first physical line after this logical line.
     *
     * @return int
     */
    public function getEndLine() : int
    {
        return $this->getStartLine() + $this->getLinesCount();
    }

    public function __toString() : string
    {
        return $this->content;
    }

    /**
     * Returns the logical line's content unfolded
     *
     * @return string
     */
    public function unfolded() : string
    {
        return preg_replace('/(\r\n|\n) /', '', $this->getContent());
    }
}

// vim: syntax=php sw=4 ts=4 et:
