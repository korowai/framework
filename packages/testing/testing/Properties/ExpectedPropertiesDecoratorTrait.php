<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Properties;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ExpectedPropertiesDecoratorTrait
{
    abstract public function getExpectedProperties() : ExpectedPropertiesInterface;

    //
    // \IteratorAggregate
    //

    public function getIterator() : \Traversable
    {
        return $this->getExpectedProperties()->getIterator();
    }

    //
    // \ArrayAccess
    //

    public function offsetExists($offset) : bool
    {
        return $this->getExpectedProperties()->offsetExists($offset);
    }

    public function offsetGet($offset)
    {
        return $this->getExpectedProperties()->offsetGet($offset);
    }

    public function offsetSet($offset, $value) : void
    {
        $this->getExpectedProperties()->offsetSet($offset, $value);
    }

    public function offsetUnset($offset) : void
    {
        $this->getExpectedProperties()->offsetUnset($offset);
    }

    //
    // \Countable
    //
    public function count() : int
    {
        return $this->getExpectedProperties()->count();
    }

    //
    // PropertiesInterface
    //

    public function getArrayCopy()
    {
        return $this->getExpectedProperties()->getArrayCopy();
    }

    public function canUnwrapChild(PropertiesInterface $child) : bool
    {
        return $this->getExpectedProperties()->canUnwrapChild($child);
    }

    //
    // ExpectedPropertiesInterface
    //

    public function getPropertySelector() : PropertySelectorInterface
    {
        return $this->getExpectedProperties()->getPropertySelector();
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
