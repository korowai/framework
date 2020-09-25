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
     * @param callable $errorHandler user-provided error handler function
     * @param int      $errorTypes   can be used to mask the triggering of the error
     *                               handler function
     */
    public function __construct(callable $errorHandler, int $errorTypes = E_ALL | E_STRICT)
    {
        $this->errorHandler = $errorHandler;
        parent::__construct($errorTypes);
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(int $severity, string $message, string $file, int $line): bool
    {
        return call_user_func($this->getErrorHandler(), $severity, $message, $file, $line);
    }

    /**
     * Returns the $errorHandler provided to constructor.
     */
    public function getErrorHandler(): callable
    {
        return $this->errorHandler;
    }
}

// vim: syntax=php sw=4 ts=4 et:
