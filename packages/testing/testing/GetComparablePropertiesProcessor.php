<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class GetComparablePropertiesProcessor implements PropertiesProcessorInterface
{
    /**
     * @var SplObjectProperties
     */
    private $seen = null;

    /**
     * @var RecursiveIteratorIterator|null
     */
    private $iter = null;

    /**
     * Walks recursivelly through all $properties replacing them with corresponding arrays.
     *
     * @param PropertiesInterface $properties
     * @param array $result
     * @psalm-param-out array $result
     */
    public function walk(PropertiesInterface $properties, &$result = null) : void
    {
        $this->seen = new SplObjectStorage;
        try {
            $result = $this->walkRecursive($properties);
        } finally {
            $this->seen = null;
        }
    }

    private function walkRecursive(PropertiesInterface $properties) : array
    {
        $array = $properties->getArrayCopy();
        $this->seen->attach($properties);
        try {
            array_walk_recursive($array, [$this, 'visit'], $properties);
        } finally {
            $this->seen->detach($properties);
        }
        return $array;
    }

    private function visit(&$value, $key, PropertiesInterface $properties) : void
    {
        if ($value instanceof PropertiesInterface && $properties->canGetComparableFrom($value)) {
            if($this->seen->contains($value)) {
                // circular dependency would lead to endless recursion
                $this->throwCircular($properties, $value);
            }
            $value = $this->walkRecursive($value, $seen);
        }
    }

    private function throwCircular(Propertiesinterface $properties, PropertiesInterface $value) : void
    {
        $message = 'Circular dependency found in definition of properties.';
        throw new CircularDependencyException($message);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
