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
 * LDIF parser.
 */
class Parser
{
    /**
     * @param string $ldif
     *
     * @return ParsingState
     */
    public function parseString(string $ldif) : ParsingState
    {
        $input = (new Preprocessor())->preprocess($ldif);
        return $this->parsePreprocessed($input);
    }

    /**
     * @param Preprocessed $input
     *
     * @return ParsingState
     */
    public function parsePreprocessed(Preprocessed $input) : ParsingState
    {
        $cursor = new PreprocessedCursor($input);
        $state = new ParsingState($cursor);

        $this->parseLdifFile($state);

        return $state;
    }

    public function parseLdifFile(ParsingState $state)
    {
        $cursor = $state->getCursor();
        $this->skipWs($cursor);
        $versionSpec = $this->parseVersionSpec($cursor);
    }

    public function parseLdifContent()
    {
    }

    public function parseLdifChanges()
    {
    }

    public function parseVersionSpec(CursorInterface $cursor)
    {
        $begin = clone $cursor; // store beginning

        $matches = $this->matchAheadAndSkip('/\Gversion:/', $cursor);
        if(count($matches) === 0) {
            $msg = "syntax error: unexpected token (expected 'version:')";
            return new ParseError($msg, clone $cursor);
        }

        $this->skipFill($cursor);                            // FILL
        $matches = $this->matchAhead('/\G\d+/', $cursor);    // version-number
        if(count($matches) === 0) {
            $msg = "syntax error: unexpected token (expected version number)";
            return new ParseError($msg, clone $cursor);
        }

        $version = intval($matches[0][0]);
        if($version != 1) {
            $msg = "syntax error: unsupported version number: $version";
            return new ParseError($msg, clone $cursor);
        }

        $cursor->moveBy(strlen($matches[0][0]));

        return new Cst\VersionSpec($begin, $version);
    }

    /**
     * Skip white spaces (including tabs and new-line characters).
     *
     * @param CursorInterface $cursor
     */
    public function skipWs(CursorInterface $cursor) : array
    {
        return $this->matchAheadAndSkip('/\G\s+/', $cursor);
    }

    /**
     * Skip zero or more whitespaces (FILL in RFC2849).
     *
     * @param CursorInterface $cursor
     */
    public function skipFill(CursorInterface $cursor) : array
    {
        return $this->matchAheadAndSkip('/\G */', $cursor);
    }

    /**
     * Matches the $cursor's string (starting at $cursor's position) against
     * $pattern.
     *
     * @param string $pattern
     * @param CursorInterface $cursor
     * @param int $flags Flags passed to ``preg_match()``.
     *
     * @return array Array of matches as returned by ``preg_match()``
     */
    public function matchAhead(string $pattern, CursorInterface $cursor, int $flags=0) : array
    {
        $subject = $cursor->getString();
        $offset = $cursor->getPosition();
        return $this->matchString($pattern, $subject, $flags, $offset);
    }

    /**
     * Matches the $cursor's string against $pattern (starting at $cursor's
     * position) and skips the whole match (moves the cursor after the matched
     * part of string).
     *
     * @param string $pattern
     * @param CursorInterface $cursor
     * @param int $flags Flags passed to ``preg_match()`` (note:
     *        ``PREG_OFFSET_CAPTURE`` is added unconditionally).
     *
     * @return array Array of matches as returned by ``preg_match()``
     */
    public function matchAheadAndSkip(string $pattern, CursorInterface $cursor, int $flags=0) : array
    {
        $matches = $this->matchAhead($pattern, $cursor, PREG_OFFSET_CAPTURE | $flags);
        if(count($matches) > 0) {
            $cursor->moveTo($matches[0][1] + strlen($matches[0][0]));
        }
        return $matches;
    }

    /**
     * Matches $subject against $pattern with preg_match() and returns an array
     * of matches (including capture groups).
     *
     * @param string $pattern Regular expression passed to preg_match()
     * @param string $subject Subject string passed to preg_match()
     * @param int $flags Flags passed to preg_match()
     * @param int $offset Offset passed to preg_match()
     *
     * In case of error, null is returned.
     *
     * @return array|null
     */
    protected function matchString(string $pattern, string $subject, int $flags=0, int $offset=0) : array
    {
        $tail = array_slice(func_get_args(), 2);
        if(preg_match($pattern, $subject, $matches, ...$tail) === false) {
            throw new \RuntimeException("internal error: preg_match returned false");
        }
        return $matches;
    }
}

// vim: syntax=php sw=4 ts=4 et:
