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
     * @var null|SplObjectProperties
     */
    private $seen;

    /**
     * @var bool
     */
    private $tagging;

    /**
     * Initializes the object.
     *
     * @param bool $tagging
     *      If true, then a unique tag will be appended to the end of every
     *      array that results from unwrapping of array of properties.
     */
    public function __construct(bool $tagging = true)
    {
        $this->tagging = $tagging;
    }

    /**
     * Returns whether the algorithm is tagging the unwrapped arrays.
     *
     * @return bool
     */
    public function isTagging(): bool
    {
        return $this->tagging;
    }

    /**
     * Walk recursively through $properties and unwrap nested instances of
     * PropertiesInterface when suitable.
     *
     * A call to $properties->canUnwrapChild($child) is made to decide whether
     * to unwrap given $child as well.
     *
     * @param  PropertiesInterface $properties
     * @return array
     * @throws CircularDependencyException
     */
    public function unwrap(PropertiesInterface $properties): array
    {
        $this->seen = new \SplObjectStorage();

        try {
            $result = $this->walkRecursive($properties);
        } finally {
            $this->seen = null;
        }

        return $result;
    }

    private function walkRecursive(PropertiesInterface $current): array
    {
        $array = $current->getArrayCopy();
        $this->seen->attach($current);

        try {
            array_walk_recursive($array, [$this, 'visit'], $current);
        } finally {
            $this->seen->detach($current);
        }

        if ($this->tagging) {
            // Distinguish unwrapped properties from regular arrays
            // by adding UNIQUE TAG AT THE END of $array.
            $array[self::UNIQUE_TAG] = true;
        }

        return $array;
    }

    private function visit(&$value, $key, PropertiesInterface $parent): void
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
     * @param int|string $key
     */
    private function throwCircular($key): void
    {
        $id = is_string($key) ? "'".addslashes($key)."'" : $key;

        throw new CircularDependencyException("Circular dependency found in nested properties at key {$id}.");
    }
}

// vim: syntax=php sw=4 ts=4 et:
