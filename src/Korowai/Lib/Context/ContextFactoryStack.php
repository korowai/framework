<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Contextlib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Context;

use Exception\ContextFactoryException;

/**
 * A composite context factory which collects other factories and organizes
 * them into a stack.
 */
class ContextFactoryStack implements ContextFactoryInterface
{
    use Util\Singleton;

    /**
     * @var ContextFactoryInterface[]
     */
    protected $factories;

    /**
     * Initializes the object.
     */
    protected function __initialize()
    {
        $this->clean();
    }

    /**
     * Returns the array of factories keept by the factory stack.
     *
     * @return array
     */
    public function getFactories() : array
    {
        return $this->factories;
    }

    /**
     * Resets the stack to empty state.
     */
    public function clean()
    {
        $this->factories = [];
    }

    /**
     * Returns the factory from the top of stack.
     */
    public function top()
    {
        return array_slice($this->factories, -1)[0];
    }

    /**
     * Pushes the $factory to the top of stack.
     */
    public function push(ContextFactoryInterface $factory)
    {
        return array_push($this->factories, $factory);
    }

    /**
     * Pops and returns the factory from the top of stack shortening the array
     * of factories by one element.
     *
     * @return ContextFactoryInterface
     * @throws ContextFactoryException
     */
    public function pop() : ?ContextFactoryInterface
    {
        if(count($this->factories) > 0) {
            return array_pop($this->factories);
        } else {
            throw new ContextFactoryException("can't pop from empty stack");
        }
    }

    /**
     * Removes all the occurrences of $factory from the stack.
     *
     * Uses comparison by identity to find and remove ocurrences of $factory.
     *
     * @param ContextFactoryInterface $factory
     */
    public function remove(ContextFactoryInterface $factory) : int
    {
        $this->factories = array_filter(
            $this->factories,
            function($item) use($factory) {
                return $item !== $factory;
            }
        );
    }

    /**
     * Count occurrences of $factory on the stack.
     *
     * Uses comparison by identity to find occurrences of $factory in the stack.
     *
     * @param ContextFactoryInterface $factory
     * @return int
     */
    public function count(ContextFactoryInterface $factory) : int
    {
        return count(array_filter(
            $this->factories,
            function($item) use ($factory) {
                return $item === $factory;
            }
        ));
    }

    /**
     * Returns true if $factory is on stack.
     *
     * @param ContextFactoryInterface $factory
     * @return bool
     */
    public function contains(ContextFactoryInterface $factory) : bool
    {
        return ($this->count($factory) > 0);
    }

    /**
     * Returns the stack size.
     *
     * @return int
     */
    public function size() : int
    {
        return count($this->factories);
    }

    /**
     * {@inheritdoc}
     */
    public function getContextManager($arg) : ?ContextManagerInterface
    {
        for($i = count($this->factories) - 1; $i >= 0; $i--) {
            $factory = $this->factories[$i];
            if(null !== ($cm = $factory->getContextManager($arg))) {
                return $cm;
            }
        }
        return null;
    }
}

// vim: syntax=php sw=4 ts=4 et:
