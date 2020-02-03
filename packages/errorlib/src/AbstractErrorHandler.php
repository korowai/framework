<?php
/**
 * @file src/Korowai/Lib/Error/AbstractErrorHandler.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\errorlib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Error;

/**
 * Abstract base class for error handlers.
 *
 * The class provides $errorTypes property and getErrorTypes() method.
 */
abstract class AbstractErrorHandler implements ErrorHandlerInterface
{
    /**
     * @var int
     */
    protected $errorTypes = E_ALL | E_STRICT;

    /**
     * Initializes the object
     *
     * @param int $errorTypes
     *        Can be used to mask the triggering of the error handler.
     */
    public function __construct(int $errorTypes = E_ALL | E_STRICT)
    {
        $this->errorTypes = $errorTypes;
    }

    /**
     * Returns the integer defining error types that are captured by the error
     * handler.
     *
     * @return int
     */
    public function getErrorTypes() : int
    {
        return $this->errorTypes;
    }
}

// vim: syntax=php sw=4 ts=4 et:
