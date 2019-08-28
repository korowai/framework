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

use Exception\ContextFactoryException;

/**
 * Abstract base class for custom context factories.
 */
abstract class AbstractContextFactory implements ContextFactoryInterface, ContextManagerInterface
{
    /**
     * @var array
     */
    protected $pushedToStacks = [];


    /**
     * Pushes the factory to a factory stack.
     */
    public function pushToStack(ContextFactoryStack $stack = null)
    {
        if($stack === null) {
            $stack = ContextFactoryStack::getInstance();
        }

        $stack->push($this);
        array_push($this->pushedToStacks, $stack);
        return $this;
    }

    /**
     * @todo Write documentation
     */
    public function popFromStack()
    {
        $stack = array_pop($this->pushedToStacks);
        if(null !== $stack) {
            return $stack->pop();
        }
        return null;
    }

    /**
     * Enter the runtime context.
     *
     * Pushes $this object onto ContextFactoryStack.
     *
     * @return $this
     */
    public function __enter()
    {
        $this->pushToStack();
        return $this;
    }

    /**
     * Exit the runtime context.
     *
     * Removes itself from the top of ContextFactoryStack.
     *
     * @param \Throwable $exception The exception thrown from user function or
     *                              ``null``.
     * @return bool ``false``
     */
    public function __exit(?\Throwable $exception) : bool
    {
        $this->popFromStack();
        return false;
    }
}

// vim: syntax=php sw=4 ts=4 et:
