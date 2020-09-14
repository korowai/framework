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
 * Specifies properties of an object.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ObjectProperties extends \ArrayObject implements ObjectPropertiesInterface
{
    /**
     * Initializes the object.
     */
    public function __construct(array $properties)
    {
        parent::__construct($properties);
    }

    /**
     * {@inheritdoc}
     */
    public function getArrayForComparison() : array
    {
        $array = $this->getArrayCopy();
        array_walk_recursive($array, [self::class, 'adjustValueForComparison']);
        return $array;
    }

    private function adjustValueForComparison(&$value)
    {
        if ($value instanceof ObjectPropertiesInterface) {
            $value = $value->getArrayForComparison();
        }
    }
}

// vim: syntax=php sw=4 ts=4 et tw=120:
