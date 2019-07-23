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
class LdifLine extends LdifSnip
{
    use PregWithExceptions;

    /**
     * Regular expression that matches a line-fold sequence.
     */
    const RE_FOLD = '/((?:\r\n|\n) )/';

    /**
     * Regular expression that matches a single LDIF line (possibly folded).
     */
    const RE_LINE = '/([^\r\n]+(?:(?:\r\n |\n )[^\r\n]*)*|(?:\r\n|\n))/';


    /**
     * @var string
     *
     * Pieces of the logical line.
     */
    protected $pieces;


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
        parent::init($content, $startLine);
        $this->pieces = self::preg_split(self::RE_FOLD, $content);
    }

    /**
     * Returns the pieces of logical line
     *
     * @return string[]
     */
    public function getPieces() : array
    {
        return $this->pieces;
    }


    /**
     * Returns the logical line's content unfolded
     *
     * @return string
     */
    public function getUnfolded() : string
    {
        return implode($this->pieces);
    }


    /**
     * Returns the lines count of the $content
     *
     * @return int
     */
    public function getLinesCount() : int
    {
        return count($this->pieces);
    }

    /**
     * Returns the index of the physical line containing char at $offset.
     *
     * @param int $offset refers a character in unfolded logical line string.
     *
     * @return int
     */
    public function getLineAt(int $offset)
    {
        $curr  = 0;
        for($i =0; $i < $this->getLinesCount(); $i++) {
            $piece = $this->pieces[$i];
            $next = $curr + strlen($piece);
            if($offset >= $curr && $offset < $next) {
                break;
            }
            $curr = $next;
        }
        return $i + $this->getStartLine();
    }
}

// vim: syntax=php sw=4 ts=4 et:
