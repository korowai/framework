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

trait LdifSplitTrait
{
    public static function splitLdif(string $ldif)
    {
        // Split LDIF text into logical lines and separators.
        if(preg_match_all(LdifLine::RE_LINE, $ldif, $matches, PREG_OFFSET_CAPTURE) === false) {
            return false;
        }

        $snips = [];

        $lastOffset = 0;
        $lineOffset = 0;

        foreach($matches[0] as $match) {
            $currOffset = $match[1];
            $length = $currOffset - $lastOffset;
            $lineOffset += substr_count($ldif, "\n", $lastOffset, $length);

            if($match[0] === "\n" or $match[0] === "\r\n") {
                $snips[] = new LdifSep($match[0], $lineOffset);
            } else {
                $snips[] = new LdifLine($match[0], $lineOffset);
            }

            $lastOffset = $currOffset;
        }

        return $snips;
    }

    public static function joinLdif(array $pieces)
    {
        return implode(array_map(function ($p) {return (string)$p;}, $pieces));
    }
}

// vim: syntax=php sw=4 ts=4 et:
