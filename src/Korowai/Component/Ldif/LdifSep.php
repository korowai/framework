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
 * Encapsulates a separator between LDIF lines.
 */
class LdifSep extends LdifSnip
{
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
        if($conent !== "\n" && $content !== "\r\n") {
            throw new \RuntimeException('an argument 1 to LdifSep::init() must be either "\n" or "\r\n"');
        }
        parent::init($content, $startLine);
    }


    /**
     * Returns the lines count of the $content
     *
     * @return int
     */
    public function getLinesCount() : int
    {
        return 0;
    }


    /**
     * Returns the index of the physical line containing char at $offset.
     *
     * @param int $offset ignored
     *
     * @return int
     */
    public function getLineAt(int $offset)
    {
        return $this->getStartLine();
    }
}

// vim: syntax=php sw=4 ts=4 et:
