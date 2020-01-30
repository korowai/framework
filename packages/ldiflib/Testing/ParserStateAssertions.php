<?php
/**
 * @file Testing/ParserStateAssertions.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Lib\Ldif;

//use Korowai\Lib\Ldif\ParserState;
//use Korowai\Lib\Ldif\Preprocessor;
//use Korowai\Lib\Ldif\Cursor;
//use Korowai\Lib\Ldif\Input;

use PHPUnit\Framework\Constraint;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ParserStateAssertions
{
    /**
     *
     */
    protected function assertParserStateSatisfies(array $expectations, ParserState $state)
    {
        // Checking $stte->isOk()
        $expIsOk = $expectations['isOk'] ?? ($checks['result'] ?? true);
        $this->assertSame($expIsOk, $state->isOk());

        // Checking current position.
        $cursor = $state->getCursor();
        if (($expOffset = $expectations['offset'] ?? null) !== null) {
            $this->assertSame($expOffset, $cursor->getOffset());
        }
        if (($expSourceOffset = $expectations['sourceOffset'] ?? null) !== null) {
            $this->assertSame($expSourceOffset, $cursor->getSourceOffset());
        }
        if (($expSourceCharOffset = $expectations['sourceCharOffset'] ?? null) !== null) {
            $this->assertSame($expSourceCharOffset, $cursor->getSourceCharOffset());
        }

        // Checking errors.
        $errors = $state->getErrors();
        if (($expError = $checks['error'] ?? null) === null) {
            $this->assertEmpty($errors);
        } else {
            $this->assertCount(1, $errors);
            $error = $errors[count($errors)-1];
            $this->assertSame($expError['message'], $error->getMessage());
            $this->assertSame($expError['sourceOffset'], $error->getSourceCharOffset());
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
