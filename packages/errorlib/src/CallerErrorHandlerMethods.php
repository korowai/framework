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
 * Provides methods for CallerXxxHandler classes.
 */
trait CallerErrorHandlerMethods
{
    /**
     * @var string
     */
    protected $callerFile;

    /**
     * @var int
     */
    protected $callerLine;

    /**
     * {@inheritdoc}
     */
    public function __invoke(int $severity, string $message, string $file, int $line): bool
    {
        return parent::__invoke($severity, $message, $this->getCallerFile(), $this->getCallerLine());
    }

    /**
     * Returns the caller's file name as determined by the constructor.
     */
    public function getCallerFile(): string
    {
        return $this->callerFile;
    }

    /**
     * Returns the caller's line number as determined by the constructor.
     */
    public function getCallerLine(): int
    {
        return $this->callerLine;
    }

    protected function initCallerErrorHandler($distance)
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1 + $distance);
        $caller = end($trace);

        $this->callerFile = $caller['file'];
        $this->callerLine = $caller['line'];
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
