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
 * Abstract base class for managed custom context factories.
 *
 * A managed context factory implements enterContext() and exitContext(), so it
 * works as a context manager. A class that extends AbstractManagedContextFactory
 * must still implement the getContextManager() method.
 */
abstract class AbstractManagedContextFactory implements ContextFactoryInterface, ContextManagerInterface
{
    use FactoryContextMethods;
}

// vim: syntax=php sw=4 ts=4 et:
