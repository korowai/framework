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

/**
 * Context-managed error handler disabler.
 */
class EmptyErrorHandler extends AbstractManagedErrorHandler
{
    use \Korowai\Lib\Basic\Singleton;

    /**
     * Sets this object as error handler using PHP function ``set_error_handler()`` and returns ``$this``.
     *
     * @return AbstractManagedErrorHandler
     */
    public function enterContext()
    {
        set_error_handler($this);
        return $this;
    }

    /**
     * Restores original error handler using PHP function \restore_error_hander() and returns ``false``.
     *
     * @return bool Always ``false``.
     */
    public function exitContext(?\Throwable $exception = null) : bool
    {
        restore_error_handler();
        return false;
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
