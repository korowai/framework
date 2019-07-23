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

trait LdifParserUtil
{
    public static function splitLdifString(string $ldif)
    {
        // Split into logical lines and preserve endlines
        $re = '/(?:[^\r\n]+(?:(?:\r\n |\n )[^\r\n]*)*|(?:\r\n|\n))/';
        if(preg_match_all($re, $ldif, $matches, PREG_OFFSET_CAPTURE) === false) {
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
                $line = new LdifLine($match[0], $lineOffset);
                $snips[] = self::morphTrivialLine($line);
            }

            $lastOffset = $currOffset;
        }

        return $snips;
    }


    public static function morphTrivialLine($piece)
    {
        if(is_a($piece, LdifLine::class)) {
            $content = $piece->getContent();
            $start = $piece->getStartLine();
            $end = $piece->getEndLine();

            if($content[0] === '#') {
                $text = preg_replace('/^#\s*/', '', $piece->unfolded());
                return new Ast\Comment($text, $content, $start, $end);
            }
        }
        return $piece;
    }
}

// vim: syntax=php sw=4 ts=4 et:
