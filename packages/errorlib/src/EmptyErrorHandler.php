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
    public function __invoke(int $severity, string $message, string $file, int $line): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorTypes(): int
    {
        return E_ALL | E_STRICT;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
