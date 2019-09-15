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

/**
 * LDIF parser.
 */
class ParsingState
{
    /**
     * @var Preprocessed
     */
    protected $cursor;

    /**
     * Concrete Syntax Tree - the main result of parsing.
     *
     * @var Cst
     */
    protected $cst;

    /**
     * @var array
     */
    protected $errors;

    /**
     * Initializes the parser object
     */
    public function __construct(PreprocessedCursor $cursor, array $errors = null)
    {
        $this->init($cursor, $errors);
    }

    /**
     * Returns the preprocessed input provided to the constructor as $cursor.
     *
     * @return Preprocessed
     */
    public function getCursor() : PreprocessedCursor
    {
        return $this->cursor;
    }

    /**
     * Returns the parsing errors.
     *
     * @return array
     */
    public function getErrors() : array
    {
        return $this->errors;
    }

    /**
     * Returns true if there are no errors.
     *
     * @return bool
     */
    public function isOk()
    {
        return count($this->errors) === 0;
    }


    protected function init(Preprocessed $cursor, array $errors = null)
    {
        $this->cursor = $cursor;
        $this->errors = $errors ?? [];
    }
}

// vim: syntax=php sw=4 ts=4 et:
