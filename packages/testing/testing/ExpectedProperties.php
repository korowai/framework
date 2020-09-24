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
final class ExpectedProperties extends AbstractProperties implements ExpectedPropertiesInterface
{
    /**
     * @var PropertySelectorInterface
     */
    private $propertySelector;

    public function __construct(PropertySelectorInterface $propertySelector, $input = [])
    {
        $this->propertySelector = $propertySelector;
        parent::__construct($input);
    }

    public function getPropertySelector() : PropertySelectorInterface
    {
        return $this->propertySelector;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
