<?php
/**
 * @file src/ParserState.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif;

/**
 * State object for Parser.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ParserState implements ParserStateInterface
{
    /**
     * @var CursorInterface
     */
    protected $cursor;

    /**
     * @var array
     */
    protected $records;

    /**
     * @var array
     */
    protected $errors;

    /**
     * Initializes the ParserState object.
     *
     * @param  CursorInterface $cursor
     * @param  array|null $errors
     * @param  array|null $records
     */
    public function __construct(CursorInterface $cursor, array $errors = null, array $records = null)
    {
        $this->initParserState($cursor, $errors, $records);
    }

    /**
     * {@inheritdocs}
     */
    public function getCursor() : CursorInterface
    {
        return $this->cursor;
    }

    /**
     * {@inheritdocs}
     */
    public function getErrors() : array
    {
        return $this->errors;
    }

    /**
     * {@inheritdocs}
     */
    public function getRecords() : array
    {
        return $this->records;
    }

    /**
     * {@inheritdoc}
     */
    public function isOk() : bool
    {
        return count($this->errors) === 0;
    }

    /**
     * Sets the instance of CursorInterface to this object.
     *
     * @param  CursorInterface $cursor
     * @return object $this
     */
    public function setCursor(CursorInterface $cursor)
    {
        $this->cursor = $cursor;
        return $this;
    }

    /**
     * Replaces the errors array with new one.
     *
     * @param  array $errors
     * @return object $this
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * Replaces the records array with new one.
     *
     * @param  array $records
     * @return object $this
     */
    public function setRecords(array $records)
    {
        $this->records = $records;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function appendError(ParserErrorInterface $error)
    {
        $this->errors[] = $error;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function errorHere(string $message, array $arguments = [])
    {
        $error = new ParserError($this->getCursor()->getClonedLocation(), $message, ...$arguments);
        return $this->appendError($error);
    }

    /**
     * {@inheritdoc}
     */
    public function errorAt(string $message, ?int $offset = null, array $arguments = [])
    {
        $this->getCursor()->moveTo($offset);
        $this->errorHere($message, $arguments);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function appendRecord(RecordInterface $record)
    {
        $this->records[] = $record;
        return $this;
    }

    /**
     * Initializes the ParserState object
     *
     * @param  CursorInterface $cursor
     * @param  array|null $errors
     * @param  array|null $records
     */
    protected function initParserState(CursorInterface $cursor, array $errors = null, array $records = null)
    {
        $this->setCursor($cursor);
        $this->setErrors($errors ?? []);
        $this->setRecords($records ?? []);
    }
}

// vim: syntax=php sw=4 ts=4 et:
