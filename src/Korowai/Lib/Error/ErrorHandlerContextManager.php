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
 * Context manager for temporarily setting error handlers.
 */
class ErrorHandlerContextManager implements ContextManagerInterface
{
    /**
     * @var callable
     */
    protected $handler;

    /**
     * @var int
     */
    protected $errorTypes;

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
    public function enterContext()
    {
        set_error_handler($this->getHandler(), $this->getErrorTypes());
        return $this->handler;
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
