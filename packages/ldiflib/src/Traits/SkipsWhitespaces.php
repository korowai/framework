<?php
/**
 * @file src/Traits/SkipsWhitespaces.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\CursorInterface;
use Korowai\Lib\Compat\Exception\PregException;
use Korowai\Lib\Rfc\Rfc2849;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait SkipsWhitespaces
{
    // The method is implemented in MatchesPatterns trait.
    abstract public function matchAhead(string $pattern, CursorInterface $cursor, int $flags = 0) : array;

    /**
     * Skip white spaces (including tabs and new-line characters).
     *
     * @param CursorInterface $cursor
     *
     * @return array
     * @throws PregException When an error occurs in ``preg_match()``.
     */
    public function skipWs(CursorInterface $cursor) : array
    {
        return $this->matchAhead('/\G\s+/', $cursor);
    }

    /**
     * Skip zero or more whitespaces (FILL in RFC2849).
     *
     * @param CursorInterface $cursor
     *
     * @return array
     * @throws PregException When an error occurs in ``preg_match()``.
     */
    public function skipFill(CursorInterface $cursor) : array
    {
        return $this->matchAhead('/\G'.Rfc2849::FILL.'/', $cursor);
    }
}

// vim: syntax=php sw=4 ts=4 et:
