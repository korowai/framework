<?php
/**
 * @file src/Korowai/Lib/Ldif/Parser.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif;

use Korowai\Lib\Ldif\Traits\ParsesLdifFile;
use Korowai\Lib\Ldif\Traits\ParsesStrings;
use Korowai\Lib\Ldif\Traits\MatchesPatterns;
use Korowai\Lib\Ldif\Traits\SkipsWhitespaces;

/**
 * LDIF parser.
 */
class Parser implements ParserInterface
{
    use ParsesLdifFile;
    use ParsesStrings;
    use MatchesPatterns;
    use SkipsWhitespaces;

    /**
     * {@inheritdoc}
     */
    public function parse(CoupledInputInterface $input) : ParserStateInterface
    {
        $cursor = new CoupledCursor($input);
        $state = new ParserState($cursor);

        $this->parseLdifFile($state);

        return $state;
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

    public function parseVersionSpec(CoupledCursorInterface $cursor) : Cst\VersionSpec
    {
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
        return new Cst\VersionSpec($begin, $strlen, $version);
    }
}

// vim: syntax=php sw=4 ts=4 et:
