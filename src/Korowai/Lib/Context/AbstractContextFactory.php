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
 * Abstract base class for custom context factories.
 */
abstract class AbstractContextFactory implements ContextFactoryInterface, ContextManagerInterface
{
    /**
     * @var array
     */
    protected $pushedToStacks = [];


    /**
     * Returns an array of stacks to which the factory was pushed (in order).
     *
     * If the factory was pushed multiple times to a stack, the stack will
     * appear multiple times in the returned array.
     *
     * @return array
     */
    public function getPushedToStacks() : array
    {
        return $this->pushedToStacks;
    }

    /**
     * Pushes the factory to a factory stack.
     */
    public function pushToStack(ContextFactoryStackInterface $stack = null)
    {
        if($stack === null) {
            $stack = ContextFactoryStack::getInstance();
        }

        $stack->push($this);
        array_push($this->pushedToStacks, $stack);
        return $this;
    }

    /**
     * Pops the factory from the last stack it was pushed into.
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
     * Pushes $this object onto context factory stack.
     *
     * @return $this
     */
    public function enterContext()
    {
        $this->pushToStack();
        return $this;
    }

    /**
     * Exit the runtime context.
     *
     * Removes itself from the top of context factory stack.
     *
     * @param \Throwable $exception The exception thrown from user function or
     *                              ``null``.
     * @return bool ``false``
     */
    public function exitContext(?\Throwable $exception) : bool
    {
        $this->popFromStack();
        return false;
    }
}

// vim: syntax=php sw=4 ts=4 et:
