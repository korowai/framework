<?php
/**
 * @file src/Traits/MaintainsParserState.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\ParserStateInterface;
use Korowai\Lib\Ldif\LocationInterface;
use Korowai\Lib\Ldif\ParserError;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait MaintainsParserState
{
    /**
     * Moves *$state*'s cursor to *$offset* position and appends new error to
     * *$state*. The appended error points at the same input character as the
     * updated cursor does. If *$offset* is null (or absent), the cursor remains
     * unchanged.
     *
     * @param  ParserStateInterface $state State to be updated.
     * @param  string $message Error message
     * @param  int|null $offset Target offset
     */
    public function errorAtOffset(ParserStateInterface $state, string $message, ?int $offset = null) : void
    {
        if ($offset != null) {
            $state->getCursor()->moveTo($offset);
        }
        $this->errorHere($state, $message);
    }

    /**
     * Appends new error to *$state*. The appended error points at the same
     * character as *$state*'s cursor.
     *
     * @param  ParserStateInterface $state State to be updated.
     * @param  string $message Error message
     */
    public function errorHere(ParserStateInterface $state, string $message) : void
    {
        $error = new ParserError($state->getCursor()->getClonedLocation(), $message);
        $state->appendError($error);
    }
}

// vim: syntax=php sw=4 ts=4 et:
