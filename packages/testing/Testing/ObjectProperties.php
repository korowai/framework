<?php
/**
 * @file Testing/ObjectProperties.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/testing
 * @license Distributed under MIT license.
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
        return array_map(function ($v) {
            return $v instanceof ObjectPropertiesInterface ? $v->getArrayForComparison() : $v;
        }, (array)$this);
    }
}

// vim: syntax=php sw=4 ts=4 et:
