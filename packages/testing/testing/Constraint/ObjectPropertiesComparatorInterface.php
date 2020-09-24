<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Constraint;

use Korowai\Testing\ObjectPropertiesInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface ObjectPropertiesComparatorInterface
{
    /**
     * Returns actual *$object* properties in a form suitable for direct comparison or for representation. The returned
     * object contains only properties that are expected by this constraint.
     *
     * @param bool $represenation
     *
     * @return array|ObjectPropertiesInterface
     *                                         If *$representation* is false, returns an array suitable for comparison, if *$representation* is true,
     *                                         returns an instance of ObjectPropertiesInterface suitable for representation with exporter
     */
    public function getActualProperties(object $object, bool $representation);

    /**
     * Returns array of expected properties to be used for comparison. The
     * method may also apply its own transformations to particular properties.
     *
     * @param bool $represenation
     *
     * @return array|ObjectPropertiesInterface
     *                                         If *$representation* is false, returns an array suitable for comparison, if *$representation* is true,
     *                                         returns an instance of ObjectPropertiesInterface suitable for representation with exporter
     */
    public function getExpectedProperties(bool $representation);
}

// vim: syntax=php sw=4 ts=4 et tw=119:
