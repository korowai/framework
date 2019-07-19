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
 * physical lines (line folding).
 */
class LogicalLine
{
    use PregWithExceptions;

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
        $this->linesCount = count(self::preg_split('/(\r\n|\n)/', $content));
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

    /**
     * Returns the index of the physical line containing char at $offset.
     *
     * @param int $offset refers a character in unfolded logical line string.
     *
     * @return int
     */
    public function getLineOf(int $offset, bool $unwrapped=true) : int
    {

        $content = $this->getContent();
        $status = self::preg_match_all('/((?:\r\n|\n) )/', $content, $matches, PREG_OFFSET_CAPTURE);
        if($status === 0) {
            return $this->getStartLine(); // $this is a single physical line
        }

        $i = 0;
        $eat = 0;
        $beg = 0;
        foreach($matches[1] as $match) {
            $end = $match[1] - $eat;

            if($offset >= $beg && $offset < $end) {
                break;
            }

            if($unwrapped) {
                $eat += strlen($match[0]); // compensate for line folds
            }
            $beg = $end;
            $i++;
        }
        return $i + $this->getStartLine();
    }


    /**
     * Same as getContent()
     */
    public function __toString()
    {
        return $this->getContent();
    }


    /**
     * Returns the logical line's content unfolded
     *
     * @return string
     */
    public function unfolded() : string
    {
        return self::preg_replace('/(\r\n|\n) /', '', $this->getContent());
    }
}

// vim: syntax=php sw=4 ts=4 et:
