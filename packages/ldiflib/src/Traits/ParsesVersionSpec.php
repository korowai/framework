<?php
/**
 * @file src/Traits/ParsesVersionSpec.php
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
use Korowai\Lib\Ldif\LocationInterface;
use Korowai\Lib\Ldif\ParserStateInterface;
use Korowai\Lib\Ldif\Snippet;
use Korowai\Lib\Ldif\ParserError;
use Korowai\Lib\Ldif\ParserErrorInterface;
use Korowai\Lib\Ldif\Records\VersionSpec;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ParsesVersionSpec
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
     * Parses version-spec as defined in [RFC 2849](https://tools.ietf.org/html/rfc2849).
     *
     * @param  ParserStateInterface $state
     * @param  bool $tryOnly
     * @return bool
     */
    public function parseVersionSpec(ParserStateInterface $state, bool $tryOnly = false) : bool
    {
        $cursor = $state->getCursor();

        $begin = clone $cursor; // store beginning

        if (count($matches = $this->matchAhead('/\Gversion:/', $cursor)) === 0) {
            if (!$tryOnly) {
                $error = new ParserError(clone $cursor, "syntax error: unexpected token (expected 'version:')");
                $state->appendError($error);
            }
            return false;
        }

        $this->skipFill($cursor);  // FILL

        if (!$this->parseVersionNumber($state, $version)) {
            return false;
        }

        $length = $cursor->getOffset() - $begin->getOffset();
        $record = new VersionSpec(new Snippet($begin, $length), $version);
        $state->appendRecord($record);

        return true;
    }

    /**
     * Parses version number of the version-spec.
     *
     * @param  ParserStateInterface $state
     * @param  VersionSpec $record
     *
     * @return bool
     */
    public function parseVersionNumber(ParserStateInterface $state, int &$version = null) : bool
    {
        $cursor = $state->getCursor();
        if (count($matches = $this->matchAt('/\G\d+/', $cursor)) === 0) {
            $error = new ParserError(clone $cursor, "syntax error: unexpected token (expected number)");
            $state->appendError($error);
            return false;
        }

        $val = intval($matches[0]);
        if ($val != 1) {
            $error = new ParserError(clone $cursor, "syntax error: unsupported version number: $val");
            $state->appendError($error);
            return false;
        }
        $version = $val;

        $cursor->moveBy(strlen($matches[0][0]));
        return true;
    }
}

// vim: syntax=php sw=4 ts=4 et:
