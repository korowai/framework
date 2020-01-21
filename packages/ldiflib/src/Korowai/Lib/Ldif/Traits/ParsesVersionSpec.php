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
use Korowai\Lib\Ldif\ParserStateInterface;
use Korowai\Lib\Ldif\ParserError;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ParsesVersionSpec
{
    abstract public function skipFill(CoupledCursorInterface $cursor) : array;

    /**
     * Parses version-spec as defined in [RFC 2849](https://tools.ietf.org/html/rfc2849).
     *
     * @param  ParserStateInterface $state
     * @param  bool $required
     * @return bool
     */
    public function parseVersionSpec(ParserStateInterface $state) : bool
    {
        $cursor = $state->getCursor();

        $begin = clone $cursor; // store beginning

        $this->matchAheadOrThrow('/\Gversion:/', $cursor, "syntax error: unexpected token (expected 'version:')");

        $this->skipFill($cursor);  // FILL

        $matches = $this->matchAtOrThrow('/\G\d+/', $cursor, "syntax error: unexpected token (expected number)");

        $version = intval($matches[0][0]);
        if ($version != 1) {
            throw new ParserError(clone $cursor, "syntax error: unsupported version number: $version");
        }

        $cursor->moveBy(strlen($matches[0][0]));

        $strlen = $cursor->getByteOffset() - $begin->getByteOffset();

        return true;
        //return new Cst\VersionSpec($begin, $strlen, $version);
    }
}

// vim: syntax=php sw=4 ts=4 et:
