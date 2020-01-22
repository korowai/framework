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

use Korowai\Lib\Ldif\CoupledCursorInterface;
use Korowai\Lib\Ldif\CoupledLocationInterface;
use Korowai\Lib\Ldif\ParserStateInterface;
use Korowai\Lib\Ldif\CoupledRange;
use Korowai\Lib\Ldif\ParserError;
use Korowai\Lib\Ldif\ParserErrorInterface;
use Korowai\Lib\Ldif\Records\VersionSpec;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ParsesVersionSpec
{
    abstract public function skipFill(CoupledCursorInterface $cursor) : array;
    abstract public function matchAheadOrThrow(
        string $pattern,
        CoupledCursorInterface $cursor,
        string $msg,
        int $flags = 0
    ) : array;
    abstract public function matchAtOrThrow(
        string $pattern,
        CoupledLocationInterface $location,
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

        $length = $cursor->getByteOffset() - $begin->getByteOffset();
        $record = new VersionSpec(new CoupledRange($begin, $length), $version);
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

        $version = intval($matches[0][0]);
        if ($version != 1) {
            $error = new ParserError(clone $cursor, "syntax error: unsupported version number: $version");
            $state->appendError($error);
            return false;
        }

        $cursor->moveBy(strlen($matches[0][0]));
        return true;
    }
}

// vim: syntax=php sw=4 ts=4 et:
