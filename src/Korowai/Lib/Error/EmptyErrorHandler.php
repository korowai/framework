<?php
/**
 * @file src/Korowai/Lib/Error/EmptyErrorHandler.php
 *
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
 * Context-managed error handler disabler.
 */
final class EmptyErrorHandler implements ErrorHandlerInterface, ContextManagerInterface
{
    use \Korowai\Lib\Basic\Singleton;
    use ContextManagerMethods;

    /**
     * {@inheritdoc}
     */
    public function getErrorTypes() : int
    {
        return E_ALL | E_STRICT;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(int $severity, string $message, string $file, int $line) : bool
    {
        return true;
    }
}

// vim: syntax=php sw=4 ts=4 et:
