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
use Korowai\Lib\Ldif\Records\VersionSpec;
use Korowai\Lib\Rfc\Rfc2849;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ParsesVersionSpec
{
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

        $begin = clone $cursor;

        $matches = $this->matchAhead('/\G'.Rfc2849::VERSION_SPEC_X.'/', $cursor, PREG_UNMATCHED_AS_NULL);
        if (count($matches) === 0) {
            if (!$tryOnly) {
                $error = new ParserError(clone $cursor, 'syntax error: expected "version:"');
                $state->appendError($error);
            }
            return false;
        }

        if (($version = $this->parseMatchedVersionSpec($state, $matches)) === null) {
            return false;
        }

        $length = $cursor->getOffset() - $begin->getOffset();
        $record = new VersionSpec(new Snippet($begin, $length), $version);
        $state->appendRecord($record);

        return true;
    }

    /**
     * Called when the version-spec is matched and its components are captured
     * by ``parseVersionSpec()``. Returns the captured version number on
     * success or null on parse error.
     *
     * @param  ParserStateInterface $state
     * @param  array $matches
     * @return int|null
     */
    protected function parseMatchedVersionSpec(ParserStateInterface $state, array $matches) : ?int
    {
        if (!array_key_exists('version_number', $matches)) {
            $error = new ParserError(clone ($state->getCursor()), 'syntax error: expected number');
            $state->appendError($error);
            return null;
        }

        return $this->parseMatchedVersionNumber($state, $matches);
    }

    /**
     * Called when the version-number is matched and its components are captured
     * by ``parseVersionSpec()``.
     *
     * @param  ParserStateInterface $state
     * @param  array $matches
     * @return int|null
     */
    protected function parseMatchedVersionNumber(ParserStateInterface $state, array $matches) : ?int
    {
        $cursor = $state->getCursor();

        $version = intval($matches['version_number'][0]);
        if ($version != 1) {
            $cursor->moveTo($matches['version_number'][1]);
            $error = new ParserError(clone $cursor, "syntax error: unsupported version number: $version");
            $state->appendError($error);
            return null;
        }
        return $version;
    }
}

// vim: syntax=php sw=4 ts=4 et:
