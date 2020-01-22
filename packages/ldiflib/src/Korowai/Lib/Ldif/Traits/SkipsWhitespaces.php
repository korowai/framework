<?php
/**
 * @file src/Korowai/Lib/Ldif/Traits/SkipsWhitespaces.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\CoupledCursorInterface;
use Korowai\Lib\Compat\Exception\PregException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait SkipsWhitespaces
{
    // The method is implemented in MatchesPatterns trait.
    abstract public function matchAhead(string $pattern, CoupledCursorInterface $cursor, int $flags = 0) : array;

    /**
     * Skip white spaces (including tabs and new-line characters).
     *
     * @param CoupledCursorInterface $cursor
     *
     * @return array
     * @throws PregException When an error occurs in ``preg_match()``.
     */
    public function skipWs(CoupledCursorInterface $cursor) : array
    {
        return $this->matchAhead('/\G\s+/', $cursor);
    }

    /**
     * Skip zero or more whitespaces (FILL in RFC2849).
     *
     * @param CoupledCursorInterface $cursor
     *
     * @return array
     * @throws PregException When an error occurs in ``preg_match()``.
     */
    public function skipFill(CoupledCursorInterface $cursor) : array
    {
        return $this->matchAhead('/\G */', $cursor);
    }
}

// vim: syntax=php sw=4 ts=4 et: