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
    abstract public function skipWs(CursorInterface $cursor) : array;

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
        //return new Cst\DnSpec($begin, $strlen, $dn);
    }
}

// vim: syntax=php sw=4 ts=4 et:
