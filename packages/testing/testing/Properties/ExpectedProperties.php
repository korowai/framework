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
final class ExpectedProperties extends \ArrayObject implements ExpectedPropertiesInterface
{
    /**
     * @var PropertySelectorInterface
     * @psalm-readonly
     */
    private $propertySelector;

    /**
     * @param mixed $input
     * @psalm-assert array|object $input
     */
    public function __construct(PropertySelectorInterface $propertySelector, $input = [])
    {
        $this->propertySelector = $propertySelector;
        parent::__construct($input);
    }

    /**
     * @psalm-mutation-free
     */
    public function getPropertySelector(): PropertySelectorInterface
    {
        return $this->propertySelector;
    }

    /**
     * @psalm-mutation-free
     */
    public function canUnwrapChild(PropertiesInterface $child): bool
    {
        return $child instanceof ExpectedPropertiesInterface;
    }
}

// vim: syntax=php sw=4 ts=4 et:
