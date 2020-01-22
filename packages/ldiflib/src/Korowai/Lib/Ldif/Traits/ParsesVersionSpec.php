<?php
/**
 * @file src/Korowai/Lib/Ldif/Traits/ParsesVersionSpec.php
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
    abstract public function skipFill(CursorInterface $cursor) : array;
    abstract public function matchAheadOrThrow(
        string $pattern,
        CursorInterface $cursor,
        string $msg,
        int $flags = 0
    ) : array;
    abstract public function matchAtOrThrow(
        string $pattern,
        LocationInterface $location,
        string $msg,
        int $flags = 0
    ) : array;

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

        try {
            $this->matchAheadOrThrow('/\Gversion:/', $cursor, "syntax error: unexpected token (expected 'version:')");
        } catch (ParserErrorInterface $error) {
            if (!$tryOnly) {
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
        try {
            $matches = $this->matchAtOrThrow('/\G\d+/', $cursor, "syntax error: unexpected token (expected number)");
        } catch (ParserErrorInterface $error) {
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
