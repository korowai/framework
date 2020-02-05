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
use Korowai\Lib\Ldif\ParserStateInterface as State;
use Korowai\Lib\Ldif\Snippet;
use Korowai\Lib\Ldif\Records\VersionSpec;
use Korowai\Lib\Rfc\Rfc2849x;

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
     * @param  State $state
     * @param  bool $tryOnly
     * @return bool
     */
    public function parseVersionSpec(State $state, bool $tryOnly = false) : bool
    {
        $cursor = $state->getCursor();

        $matches = $this->matchAhead('/\G'.Rfc2849x::VERSION_SPEC_X.'/', $cursor, PREG_UNMATCHED_AS_NULL);
        if (count($matches) === 0) {
            if (!$tryOnly) {
                $state->errorHere('syntax error: expected "version:"');
            }
            return false;
        }

        if (($version = $this->parseMatchedVersionSpec($state, $matches)) === null) {
            return false;
        }

        $beginOffset = $matches[0][1];
        $begin = $cursor->getClonedLocation($beginOffset);
        $length = $cursor->getOffset() - $beginOffset;
        $record = new VersionSpec(new Snippet($begin, $length), $version);
        $state->appendRecord($record);

        return true;
    }

    /**
     * Completes version-spec parsing assuming that the input matched
     * successfully the version-spec regular expression. Returns the captured
     * version number on success or null on parse error.
     *
     * @param  State $state
     * @param  array $matches
     * @return int|null
     */
    protected function parseMatchedVersionSpec(State $state, array $matches) : ?int
    {
        $cursor = $state->getCursor();
        if (($offset = $matches['version_error'][1] ?? -1) >= 0) {
            $state->errorAt('syntax error: expected number', $offset);
            return null;
        }
        return $this->parseMatchedVersionNumber($state, $matches);
    }

    /**
     * Called when the version-number is matched and its components are captured
     * by ``parseVersionSpec()``.
     *
     * @param  State $state
     * @param  array $matches
     * @return int|null
     */
    protected function parseMatchedVersionNumber(State $state, array $matches) : ?int
    {
        $cursor = $state->getCursor();

        $version = intval($matches['version_number'][0]);
        if ($version != 1) {
            $offset = $matches['version_number'][1];
            $state->errorAt("syntax error: unsupported version number: $version", $offset);
            return null;
        }
        return $version;
    }
}

// vim: syntax=php sw=4 ts=4 et:
