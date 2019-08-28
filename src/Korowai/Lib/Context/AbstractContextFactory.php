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

use Exception\AlreadyInChainException;

/**
 * Abstract base class for custom context factories.
 */
abstract class AbstractContextFactory implements ContextFactoryInterface, ContextManagerInterface
{
    /**
     * @var ContextFactoryChain
     */
    protected $pushedToChain = null;


    /**
     * @todo Write documentation
     */
    public function pushToChain($chain = null)
    {
        if(isset($this->pushedToChain)) {
            throw new AlreadyInChainException("The context factory is already in a factory chain");
        }

        if($chain === null) {
            $chain = ContextFactoryChain::getInstance();
        }

        $chain->push($this);
        $this->pushedToChain = $chain;
        return $this;
    }

    /**
     * @todo Write documentation
     */
    public function popFromChain()
    {
        if(isset($this->pushedToChain)) {
            if($this->pushedToChain->top() === $this) {
                $this->pushedToChain->pop();
                $this->pushedToChain = null;
                return $this;
            }
        }
        return null;
    }

    /**
     * Enter the runtime context.
     *
     * Pushes $this object onto ContextFactoryChain.
     *
     * @return $this
     */
    public function __enter()
    {
        $this->pushToChain();
        return $this;
    }

    /**
     * Exit the runtime context.
     *
     * Removes itself from the top of ContextFactoryChain.
     *
     * @param \Throwable $exception The exception thrown from user function or
     *                              ``null``.
     * @return bool ``false``
     */
    public function __exit(?\Throwable $exception) : bool
    {
        $this->popFromChain();
        return false;
    }
}

// vim: syntax=php sw=4 ts=4 et:
