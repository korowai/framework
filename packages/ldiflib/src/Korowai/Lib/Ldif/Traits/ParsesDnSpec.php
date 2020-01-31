<?php
/**
 * @file src/Korowai/Lib/Ldif/Traits/ParsesDnSpec.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\CursorInterface;
use Korowai\Lib\Ldif\ParserStateInterface;
use Korowai\Lib\Ldif\ParserError;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ParsesDnSpec
{
    /**
     * Skip zero or more whitespaces (FILL in RFC2849).
     *
     * @param CursorInterface $cursor
     *
     * @return array
     * @throws PregException When an error occurs in ``preg_match()``.
     */
    abstract public function skipFill(CursorInterface $cursor) : array;
    /**
     * Matches the string (starting at $location's position) against $pattern.
     *
     * @param  string $pattern
     * @param  LocationInterface $location
     * @param  int $flags Flags passed to ``preg_match()``.
     *
     * @return array Array of matches as returned by ``preg_match()``
     * @throws PregException When error occurs in ``preg_match()``
     */
    abstract public function matchAt(string $pattern, LocationInterface $location, int $flags = 0) : array;
    /**
     * Matches the string starting at $cursor's position against $pattern and
     * skips the whole match (moves the cursor after the matched part of
     * string).
     *
     * @param  string $pattern
     * @param  CursorInterface $cursor
     * @param  int $flags Passed to ``preg_match()`` (note: ``PREG_OFFSET_CAPTURE`` is added unconditionally).
     *
     * @return array Array of matches as returned by ``preg_match()``
     * @throws PregException When error occurs in ``preg_match()``
     */
    abstract public function matchAhead(string $pattern, CursorInterface $cursor, int $flags = 0) : array;

    /**
     * @todo Write documentation.
     * @param ParserStateInterface $state
     */
    public function parseDnSpec(ParserStateInterface $state) : bool
    {
        $cursor = $state->getCursor();

        $begin = clone $cursor;

        $this->matchAheadOrThrow('/\Gdn:/', $cursor, "syntax error: unexpected token (expected 'dn:')");

        $matches = $this->matchAhead('/\G:/', $cursor);

        $this->skipFill($cursor);

        if (count($matches) === 0) {
            // SAFE-STRING
            $dn = $this->parseSafeString($cursor);
        } else {
            // BASE64-UTF8-STRING
            $dn = $this->parseBase64UtfString($cursor);
        }

        $strlen = $cursor->getOffset() - $begin->getOffset();

        return true;
    }
}

// vim: syntax=php sw=4 ts=4 et:
