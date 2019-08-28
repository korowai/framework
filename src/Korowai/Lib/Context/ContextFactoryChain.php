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

use Exception\ContextFactoryPopException;

/**
 * @todo Write documentation
 */
class ContextFactoryChain implements ContextFactoryInterface
{
    use Util\Singleton;

    /**
     * @var ContextFactoryInterface[]
     */
    protected $factories;

    /**
     * Initializes the singleton object.
     */
    protected function __initialize()
    {
        $this->clean();
    }

    /**
     * @todo Write documentation
     */
    public function getFactories()
    {
        return $this->factories;
    }

    /**
     * @todo Write documentation
     */
    public function clean()
    {
        $this->factories = [];
    }

    /**
     * @todo Write documentation
     */
    public function top()
    {
        return array_slice($this->factories, -1)[0];
    }

    /**
     * @todo Write documentation
     */
    public function push(ContextFactoryInterface $factory)
    {
        return array_push($this->factories, $factory);
    }

    /**
     * @todo Write documentation
     */
    public function pop()
    {
        if(count($this->factories) > 0) {
            return array_pop($this->factories);
        } else {
            throw new ContextFactoryPopException("can't pop from empty chain");
        }
    }

    /**
     * @return An index
     */
    public function search(ContextFactoryInterface $factory)
    {
        return array_search($factory, $this->factories, true);
    }

    /**
     * Removes all the occurrences of $factory from the chain.
     *
     * @return int number of 
     */
    public function remove(ContextFactoryInterface $factory) : int
    {
        $i = 0;
        while(false !== ($key = $this->search($factory))) {
            unset($this->factories[$key]);
            $i++;
        }
        return $i;
    }

    public function count(ContextFactoryInterface $factory)
    {
        return array_filter($this->factories, function($x) use ($factory) {
            return $x === $factory;
        });
    }

    /**
     * Returns true if the chain contains factory.
     *
     * @return bool
     */
    public function contains(ContextFactoryInterface $factory)
    {

    }

    /**
     * @todo Write documentation
     */
    public function length() : int
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
        return DefaultContextFactory::getInstance()->getContextManager($arg);
    }
}

// vim: syntax=php sw=4 ts=4 et:
