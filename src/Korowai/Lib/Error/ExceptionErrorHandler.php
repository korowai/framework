<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\ErrorLib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Error;

use Korowai\Lib\Context\ContextManagerInterface;

/**
 * An error handler which raises custom exception.
 */
class ExceptionErrorHandler implements ContextManagerInterface
{
    /**
     * @var
     */
    protected $exceptionGenerator;

    /**
     * Initializes the object.
     *
     * @param $exceptionGenerator
     */
    public function __construct($exceptionGenerator = null)
    {
        $this->exceptionGenerator = $exceptionGenerator;
    }

    /**
     * Returns the $exceptionGenerator provided to constructor.
     *
     * @return callable
     */
    public function getExceptionGenerator()
    {
        return $this->exceptionGenerator;
    }

    /**
     * Creates and returns new exception using $exceptionGenerator and
     * parameters provided.
     *
     * @param int $severity The level of error raised
     * @param string $message The error message, as a string
     * @param string $file The file name that the error was raised in
     * @param int $line The line number the error was raised at
     */
    public function getException(int $severity, string $message, string $file, int $line)
    {
        $generator = $this->getExceptionGenerator();
        if(is_callable($generator)) {
            $args = [$severity, $message, $file, $line];
            $exception = call_user_func_array($this->get(), $args);
        } elseif(class_exists($generator)) {
            $exception = new $generator($message, 0, $severity, $file, $line);
        } else {
            $exception = new \ErrorException($message, 0, $severity, $file, $line);
        }
        return $exception;
    }

    /**
     * Actual error handler function which throws an exception.
     */
    public function __invoke(int $severity, string $message, string $file, int $line) : bool
    {
        if(!(error_reporting() & $severity)) {
            // This error code is not included in error_reporting
            return false;
        }

        throw $this->getException($severity, $message, $file, $line);
    }

    /**
     * {@inheritdoc}
     */
    public function enterContext()
    {
        set_error_handler($this);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function exitContext(?\Throwable $exception = null) : bool
    {
        restore_error_handler();
        return false;
    }
}

// vim: syntax=php sw=4 ts=4 et:
