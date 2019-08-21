<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\ContextLib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Context;

/**
 * Converts argument to a context manager.
 *
 * If ``$arg`` is an instance of ContextManagerInterface, it's returned as is,
 * otherwise the function creates and returns new context manager appropriate
 * for the type of ``$arg`` (for example ResourceContextManager if ``$arg`` is
 * a PHP resource).
 *
 * @access public
 * @param mixed $arg The argument to be converted.
 *
 * @return ContextManagerInterface
 */
function context_manager($arg) : ContextManagerInterface
{
    if(is_a($arg, ContextManagerInterface::class)) {
        return $arg;
    } elseif(is_resource($arg)) {
        return new ResourceContextManager($arg);
    } else {
        return new TrivialValueWrapper($arg);
    }
}

/**
 * Creates an executor object which allows call user function within a context.
 *
 * @access public
 * @return ExecutorInterface
 */
function with(... $args) : ExecutorInterface
{
    $context = array_map(context_manager::class, $args);
    return new WithContextExecutor($context);
}

// vim: syntax=php sw=4 ts=4 et:
