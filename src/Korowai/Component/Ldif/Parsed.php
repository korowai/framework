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
class Parsed
{
    /**
     * @var Preprocessed
     */
    protected $input;

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
    public function __construct(Preprocessed $input, array $errors=null)
    {
        $this->init($input, $errors);
    }

    /**
     * Returns the preprocessed input provided to the constructor as $input.
     *
     * @return Preprocessed
     */
    public function getInput() : Preprocessed
    {
        return $this->input;
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

    /**
     * Returns the parsing errors.
     *
     * @return array
     */
    public function getErrors() : array
    {
        return $this->errors;
    }

    protected function init(Preprocessed $input, array $errors=null)
    {
        $this->input = $input;
        $this->errors = $errors ?? [];
    }
}

// vim: syntax=php sw=4 ts=4 et:
