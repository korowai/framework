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
 * Context-managed error handler that calls user-provided function.
 */
class ErrorHandler extends AbstractManagedErrorHandler
{
    /**
     * @var callable
     */
    protected $errorHandler;

    /**
     * Initializes the object.
     *
     * @param  callable $errorHandler User-provided error handler function.
     * @param  int $errorTypes Can be used to mask the triggering of the error
     *                        handler function.
     */
    public function __construct(callable $errorHandler, int $errorTypes = E_ALL | E_STRICT)
    {
        $this->errorHandler = $errorHandler;
        parent::__construct($errorTypes);
    }

    /**
     * Returns the $errorHandler provided to constructor.
     *
     * @return callable
     */
    public function getErrorHandler() : callable
    {
        return $this->errorHandler;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(int $severity, string $message, string $file, int $line) : bool
    {
        return call_user_func($this->getErrorHandler(), $severity, $message, $file, $line);
    }
}

// vim: syntax=php sw=4 ts=4 et:
