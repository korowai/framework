<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Error;

/**
 * An error handler which raises custom exception.
 */
class ExceptionErrorHandler extends AbstractManagedErrorHandler
{
    /**
     * @var callable
     */
    protected $exceptionGenerator;

    /**
     * Converts argument $arg to an exception generator.
     *
     * An exception generator is a function (or callable object) which creates
     * and returns exception objects.
     *
     * @param  mixed $arg Either a callable or a class name. If it's a callable
     *                   then it gets returned as is, if it is a class name
     *                   then a new callable is returned which creates and
     *                   returns new instance of this class. The constructor of
     *                   the class must have interface compatible with the
     *                   constructor of PHP's ``\ErrorException`` class. If
     *                   $arg is null, then ``\ErrorException`` is used as a
     *                   class.
     *
     * @return callable
     * @throws \InvalidArgumentException
     */
    public static function makeExceptionGenerator($arg = null) : callable
    {
        if (is_callable($arg)) {
            return $arg;
        }

        if (is_null($arg)) {
            $class = \ErrorException::class;
        } else {
            $class = $arg;
        }

        if (is_string($class) && class_exists($class)) {
            return function (int $severity, string $message, string $file, int $line) use ($class) {
                return new $class($message, 0, $severity, $file, $line);
            };
        }

        throw new \InvalidArgumentException(
            "argument 1 to " . __METHOD__  . "() must be a callable, a class" .
            " name or null, " . gettype($arg) .  " given"
        );
    }

    /**
     * Initializes the object.
     *
     * @param  callable $exceptionGenerator
     * @param  int $errorTypes Error types to be handled by this handler.
     */
    public function __construct(callable $exceptionGenerator, int $errorTypes = E_ALL | E_STRICT)
    {
        $this->exceptionGenerator = $exceptionGenerator;
        parent::__construct($errorTypes);
    }

    /**
     * Returns the $exceptionGenerator provided to constructor.
     *
     * @return calable
     */
    public function getExceptionGenerator() : callable
    {
        return $this->exceptionGenerator;
    }

    /**
     * Creates and returns new exception using the encapsulated $exceptionGenerator.
     *
     * @param  int $severity The level of error raised
     * @param  string $message The error message, as a string
     * @param  string $file The file name that the error was raised in
     * @param  int $line The line number the error was raised at
     */
    public function getException(int $severity, string $message, string $file, int $line)
    {
        $generator = $this->getExceptionGenerator();
        return call_user_func($generator, $severity, $message, $file, $line);
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(int $severity, string $message, string $file, int $line) : bool
    {
        throw $this->getException($severity, $message, $file, $line);
    }
}

// vim: syntax=php sw=4 ts=4 et:
