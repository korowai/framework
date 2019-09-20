<?php
/**
 * @file src/Korowai/Lib/Error/CallerErrorHandler.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\ErrorLib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Error;

/**
 * Context-managed error handler that calls user-provided function with predefined $file and $line arguments.
 */
class CallerErrorHandler extends CustomErrorHandler
{
    /**
     * @var string
     */
    protected $callerFile;

    /**
     * @var int
     */
    protected $callerLine;

    /**
     * Creates and returns new instance of CallerErrorHandler which throws an exception.
     *
     * @param mixed $arg Either a callable or a class name. If it's a callable
     *                   then it gets returned as is, if it is a class name
     *                   then a new callable is returned which creates and
     *                   returns new instance of this class. The constructor of
     *                   the class must have interface compatible with the
     *                   constructor of PHP's ``\ErrorException`` class. If
     *                   $arg is null, then ``\ErrorException`` is used as a
     *                   class.
     * @param int $distance
     * @param int $errorTypes
     *
     * @return callable
     */
    public static function throwing($arg, int $distance = 1, int $errorTypes = E_ALL | E_STRICT)
    {
        $handler = ExceptionErrorHandler::create($arg, $errorTypes);
        return new self($handler, 1 + $distance, $errorTypes);
    }

    /**
     * Initializes the object.
     *
     * @param callable $errorHandler
     * @param int $distance
     * @param int $errorTypes
     */
    public function __construct(callable $errorHandler, int $distance = 1, int $errorTypes = E_ALL | E_STRICT)
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1 + $distance);
        $caller = end($trace);

        $this->callerFile = $caller['file'];
        $this->callerLine = $caller['line'];
        parent::__construct($errorHandler, $errorTypes);
    }

    /**
     * Returns the caller's file name as determined by the constructor.
     * @return string
     */
    public function getCallerFile() : string
    {
        return $this->callerFile;
    }

    /**
     * Returns the caller's line number as determined by the constructor.
     * @return string
     */
    public function getCallerLine()
    {
        return $this->callerLine;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(int $severity, string $message, string $file, int $line) : bool
    {
        return parent::__invoke($severity, $message, $this->callerFile, $this->callerLine);
    }
}

// vim: syntax=php sw=4 ts=4 et:
