<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/errorlib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Error;

/**
 * Context-managed error handler that throws an exception with predefined $file and $line arguments.
 */
class CallerExceptionErrorHandler extends ExceptionErrorHandler
{
    use CallerErrorHandlerMethods;

    /**
     * Initializes the object.
     *
     * @param  callable $exceptionGenerator
     * @param  int $distance
     * @param  int $errorTypes
     */
    public function __construct(callable $exceptionGenerator, int $distance = 1, int $errorTypes = E_ALL | E_STRICT)
    {
        $this->initCallerErrorHandler(1 + $distance);
        parent::__construct($exceptionGenerator, $errorTypes);
    }
}

// vim: syntax=php sw=4 ts=4 et:
