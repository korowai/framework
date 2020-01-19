<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Context;

/**
 * Interface for context managers.
 */
interface ContextFactoryInterface
{
    /**
     * @param  mixed $arg An argument to be turned into a context manager.
     *
     * @return ContextManagerInterface
     */
    public function getContextManager($arg) : ?ContextManagerInterface;
}

// vim: syntax=php sw=4 ts=4 et:
