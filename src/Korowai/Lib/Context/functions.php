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
 * Creates an executor object which allows call user function within a context.
 *
 * @access public
 * @return ExecutorInterface
 */
function with(... $args) : ExecutorInterface
{
    $chain = ContextFactoryChain::getInstance();
    $context = array_map(function($arg) use ($chain) {
        return $chain->getContextManager($arg);
    }, $args);
    return new WithContextExecutor($context);
}

// vim: syntax=php sw=4 ts=4 et:
