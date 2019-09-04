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

/**
 * Context-managed error handler that calls user-provided function.
 */
class CustomErrorHandler extends AbstractManagedErrorHandler
{
    /**
     * @var callable
     */
    protected $handler;

    /**
     * Initializes the object.
     *
     * @param callable $handler User-provided error handler function.
     * @param int $errorTypes Can be used to mask the triggering of the error
     *                        handler function.
     */
    public function __construct(callable $handler, int $errorTypes = E_ALL | E_STRICT)
    {
        $this->handler = $handler;
        $this->errorTypes = $errorTypes;
    }

    /**
     * Returns the $handler provided to constructor.
     *
     * @return callable
     */
    public function getHandler() : callable
    {
        return $this->handler;
    }

    /**
     * Returns the $errorTypes provided to constructor.
     *
     * @return int
     */
    public function getErrorTypes() : int
    {
        return $this->errorTypes;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(int $severity, string $message, string $file, int $line) : bool
    {
        return call_user_func($this->getHandler(), $severity, $message, $file, $line);
    }
}

// vim: syntax=php sw=4 ts=4 et:
