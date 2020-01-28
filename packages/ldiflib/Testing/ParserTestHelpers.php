<?php
/**
 * @file Tests/ParsesTestHelpers.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Lib\Ldif;

use Korowai\Lib\Ldif\ParserState;
use Korowai\Lib\Ldif\Preprocessor;
use Korowai\Lib\Ldif\Cursor;
use Korowai\Lib\Ldif\Input;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ParserTestHelpers
{
    /**
     * Creates Input instance (a preprocessed source code) from a source string.
     *
     * @param  string $source
     * @param  int $posigion
     * @param  array $options
     *
     * @return Input
     */
    protected function getInputFromSource(string $source, array $options = []) : Input
    {
        $pp = new Preprocessor;

        $args = (($filename = $options['filename'] ?? null) !== null) ? [$filename] : [];
        return $pp->preprocess($source, ...$args);
    }

    /**
     * Creates Input instance (a preprocessed source code) from a source string.
     *
     * @param  string $source
     * @param  int $posigion
     * @param  array $options
     *
     * @return Input
     */
    protected function getCursorFromSource(string $source, int $position = 0, array $options = []) : Cursor
    {
        $input = $this->getInputFromSource($source, $options);
        return new Cursor($input, $position);
    }

    /**
     * Creates instance of ParserState from a source string.
     *
     * @param  string $source
     * @param  int $posigion
     * @param  array $options
     */
    protected function getParserStateFromSource(string $source, int $position = 0, array $options = []) : ParserState
    {
        $cursor = $this->getCursorFromSource($source, $position, $options);

        $args = [];
        if (($errors = $options['errors'] ?? null) !== null) {
            $args[] = $errors;
        }
        if (($records = $options['records'] ?? null) !== null) {
            $args[] = $records;
        }
        return new ParserState($cursor);
    }
}

// vim: syntax=php sw=4 ts=4 et: