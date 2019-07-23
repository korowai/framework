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
 * Encapsulates a logical line of the LDIF source which may contain multiple
 * physical lines (pieces).
 */
abstract class LdifSnip
{
    /**
     * @var string
     *
     * The content of the logical line as given in original source.
     */
    protected $content;

    /**
     * @var int
     *
     * Zero-based index of the physical line where the logical line begins in
     * the original text.
     */
    protected $startLine;


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
    abstract public function getLinesCount() : int;


    /**
     * Returns the index of the first physical line after this logical line.
     *
     * @return int
     */
    public function getEndLine() : int
    {
        return $this->getStartLine() + $this->getLinesCount();
    }

    /**
     * Returns the index of the physical line containing char at $offset.
     *
     * @param int $offset refers a character in unfolded logical line string.
     *
     * @return int
     */
    abstract public function getLineAt(int $offset);

    /**
     * Same as getContent()
     */
    public function __toString()
    {
        return $this->getContent();
    }
}

// vim: syntax=php sw=4 ts=4 et:
