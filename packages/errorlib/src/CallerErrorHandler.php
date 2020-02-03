<?php
/**
 * @file src/Korowai/Lib/Error/CallerErrorHandler.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/errorlib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Error;

/**
 * Context-managed error handler that calls user-provided function with predefined $file and $line arguments.
 */
class CallerErrorHandler extends ErrorHandler
{
    use CallerErrorHandlerMethods;

    /**
     * Initializes the object.
     *
     * @param callable $errorHandler
     * @param int $distance
     * @param int $errorTypes
     */
    public function __construct(callable $errorHandler, int $distance = 1, int $errorTypes = E_ALL | E_STRICT)
    {
        $this->initCallerErrorHandler(1 + $distance);
        parent::__construct($errorHandler, $errorTypes);
    }
}

// vim: syntax=php sw=4 ts=4 et:
