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
final class RecursivePropertiesUnwrapper implements RecursivePropertiesUnwrapperInterface
{
    public const UNIQUE_TAG = 'unwrapped-properties:$1$zIlgusJc$ZZCyNRPOX1SbpKdzoD2hU/';

    /**
     * @var SplObjectProperties|null
     */
    private $seen = null;

    /**
     * @param PropertiesInterface $properties
     */
    public function unwrap(PropertiesInterface $properties) : array
    {
        $this->seen = new \SplObjectStorage;
        try {
            $result = $this->walkRecursive($properties);
        } finally {
            $this->seen = null;
        }
        return $result;
    }

    private function walkRecursive(PropertiesInterface $current) : array
    {
        $array = $current->getArrayCopy();
        $this->seen->attach($current);
        try {
            array_walk_recursive($array, [$this, 'visit'], $current);
        } finally {
            $this->seen->detach($current);
        }
        // Distinguish unwrapped properties from regular arrays
        // by adding UNIQUE TAG AT THE END of $array.
        $array[self::UNIQUE_TAG] = true;
        return $array;
    }

    private function visit(&$value, $key, PropertiesInterface $parent) : void
    {
        if ($value instanceof PropertiesInterface && $parent->canUnwrapChild($value)) {
            if ($this->seen->contains($value)) {
                // circular dependency
                $this->throwCircular($key);
            }
            $value = $this->walkRecursive($value);
        }
    }

    /**
     * @param string|int $key
     */
    private function throwCircular($key) : void
    {
        $id = is_string($key) ? "'".addslashes($key)."'" : $key;
        throw new CircularDependencyException("Circular dependency found in nested properties at key $id.");
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
