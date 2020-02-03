<?php
/**
 * @file src/Korowai/Lib/Ldif/Traits/ParsesLdifFile.php
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
use Korowai\Lib\Ldif\ParserStateInterface;
use Korowai\Lib\Ldif\ParserError;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ParsesLdifFile
{
    abstract public function skipWs(CursorInterface $cursor) : array;

    /**
     * @todo Write documentation.
     * @param ParserStateInterface $state
     */
    public function parseLdifFile(ParserStateInterface $state) : bool
    {
        $cursor = $state->getCursor();
        $this->skipWs($cursor);

        try {
            $begin = $cursor->getOffset();
            $versionSpec = $this->parseVersionSpec($cursor);
        } catch (ParseError $err) {
            if ($cursor->getOffset() != $begin) {
                throw $err;
            }
            $versionSpec = null; // version-spec is optional
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
