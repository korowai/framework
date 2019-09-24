<?php
/**
 * @file src/Korowai/Lib/Ldif/Parser.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif;

/**
 * LDIF parser.
 */
class Parser
{
    const RE_BASE64_CHAR = '[\+\/0-9=A-Za-z]';
    const RE_SAFE_INIT_CHAR = '[\x01-\x09\x0B-\x0C\x0E-\x1F\x21-\x39\x3B\x3D-\x7F]';
    const RE_SAFE_CHAR = '[\x01-\x09\x0B-\x0C\x0E-\x7F]';

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

        try {
            $begin = $cursor->getByteOffset();
            $versionSpec = $this->parseVersionSpec($cursor);
        } catch (ParseError $err) {
            if ($cursor->getByteOffset() != $begin) {
                throw $err;
            }
            $versionSpec = null; // version-spec is optional
        }
    }

    public function parseLdifContent()
    {
    }

    public function parseLdifChanges()
    {
    }

    public function parseAttrvalRecord()
    {
    }

    public function parseChangeRecord()
    {
    }

    public function parseDnSpec(CoupledCursorInterface $cursor) : Cst\DnSpec
    {
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

        $strlen = $cursor->getByteOffset() - $begin->getByteOffset();
        return new Cst\DnSpec($begin, $strlen, $dn);
    }

    public function parseSafeString(CoupledCursorInterface $cursor) : string
    {
        $re = '/\G'.self::RE_SAFE_INIT_CHAR.self::RE_SAFE_CHAR.'*/';
        $matches = $this->matchAheadOrThrow($re, $cursor, "syntax error: unexpected token (expected SAFE-STRING)");
        return $matches[0][0];
    }

    public function parseBase64String(CoupledCursorInterface $cursor, callable $validate = null) : string
    {
        $re = '/\G'.self::RE_BASE64_CHAR.'+/';
        $matches = $this->matchAtOrThrow($re, $cursor, "syntax error: unexpected token (expected BASE64-STRING)");
        $str = base64_decode($matches[0], true);
        if ($str === false) {
            throw new ParseError($cursor, "syntax error: invalid BASE64 string");
        }
        if (is_callable($validate)) {
            call_user_func($validate, $str);
        }
        $cursor->moveBy(strlen($matches[0]));

        return $matches[0];
    }

    public function parseBase64UtfString(CoupledCursorInterface $cursor) : string
    {
        return $this->parseBase64String($cursor, function ($string) {
            if (mb_check_encoding($string, 'utf-8') === false) {
                throw new ParseError($cursor, "syntax error: the encoded string is not a valid UTF8");
            }
        });
    }

    public function parseVersionSpec(CoupledCursorInterface $cursor) : Cst\VersionSpec
    {
        $begin = clone $cursor; // store beginning

        $this->matchAheadOrThrow('/\Gversion:/', $cursor, "syntax error: unexpected token (expected 'version:')");

        $this->skipFill($cursor);  // FILL

        $matches = $this->matchAtOrThrow('/\G\d+/', $cursor, "syntax error: unexpected token (expected number)");

        $version = intval($matches[0][0]);
        if ($version != 1) {
            throw new ParseError(clone $cursor, "syntax error: unsupported version number: $version");
        }

        $cursor->moveBy(strlen($matches[0][0]));

        $strlen = $cursor->getByteOffset() - $begin->getByteOffset();
        return new Cst\VersionSpec($begin, $strlen, $version);
    }


    /**
     * Skip white spaces (including tabs and new-line characters).
     *
     * @param CoupledCursorInterface $cursor
     */
    public function skipWs(CoupledCursorInterface $cursor) : array
    {
        return $this->matchAhead('/\G\s+/', $cursor);
    }

    /**
     * Skip zero or more whitespaces (FILL in RFC2849).
     *
     * @param CoupledCursorInterface $cursor
     */
    public function skipFill(CoupledCursorInterface $cursor) : array
    {
        return $this->matchAhead('/\G */', $cursor);
    }

    /**
     * Matches the string (starting at $location's position) against $pattern.
     *
     * @param string $pattern
     * @param CoupledLocationInterface $location
     * @param int $flags Flags passed to ``preg_match()``.
     *
     * @return array Array of matches as returned by ``preg_match()``
     */
    public function matchAt(string $pattern, CoupledLocationInterface $location, int $flags = 0) : array
    {
        $subject = $location->getString();
        $offset = $location->getByteOffset();
        return $this->matchString($pattern, $subject, $flags, $offset);
    }

    /**
     * Matches the string starting at $cursor's position against $pattern and
     * skips the whole match (moves the cursor after the matched part of
     * string).
     *
     * @param string $pattern
     * @param CoupledCursorInterface $cursor
     * @param int $flags Flags passed to ``preg_match()`` (note:
     *        ``PREG_OFFSET_CAPTURE`` is added unconditionally).
     *
     * @return array Array of matches as returned by ``preg_match()``
     */
    public function matchAhead(string $pattern, CoupledCursorInterface $cursor, int $flags = 0) : array
    {
        $matches = $this->matchAt($pattern, $cursor, PREG_OFFSET_CAPTURE | $flags);
        if (count($matches) > 0) {
            $cursor->moveTo($matches[0][1] + strlen($matches[0][0]));
        }
        return $matches;
    }

    /**
     * Matches the string starting at $location's position against $pattern as
     * in ``matchAt()`` but throws ParseError if the string does not mach.
     *
     * @param string $pattern
     * @param CoupledLocationInterface $location
     * @param string $msg Error message for the exception
     * @param int $flags Flags passed to ``preg_match()`` (note:
     *        ``PREG_OFFSET_CAPTURE`` is added unconditionally).
     *
     */
    public function matchAtOrThrow(string $pattern, CoupledLocationInterface $location, string $msg, int $flags = 0)
    {
        $matches = $this->matchAt($pattern, $location, $flags);
        if (count($matches) === 0) {
            throw new ParseError(clone $location, $msg);
        }
        return $matches;
    }

    /**
     * Matches the string starting at $cursor's position against $pattern as
     * in ``matchAhead()`` but throws ParseError if the string does not mach.
     *
     * @param string $pattern
     * @param CoupledCursorInterface $cursor
     * @param string $msg Error message for the exception
     * @param int $flags Flags passed to ``preg_match()`` (note:
     *        ``PREG_OFFSET_CAPTURE`` is added unconditionally).
     *
     */
    public function matchAheadOrThrow(string $pattern, CoupledCursorInterface $cursor, string $msg, int $flags = 0)
    {
        $matches = $this->matchAhead($pattern, $cursor, $flags);
        if (count($matches) === 0) {
            throw new ParseError(clone $cursor, $msg);
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
    protected function matchString(string $pattern, string $subject, int $flags = 0, int $offset = 0) : array
    {
        $tail = array_slice(func_get_args(), 2);
        if (Util\preg_match($pattern, $subject, $matches, ...$tail) === false) {
            throw new \RuntimeException("internal error: preg_match returned false");
        }
        return $matches;
    }
}

// vim: syntax=php sw=4 ts=4 et:
