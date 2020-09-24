<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\Constraint;

use Korowai\Testing\TestCase;
use Korowai\Testing\Constraint\ClassHasPropertiesEqualTo;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Constraint\ClassHasPropertiesEqualTo
 * @covers \Korowai\Testing\Constraint\AbstractPropertiesComparator
 * @covers \Korowai\Tests\Testing\Constraint\PropertiesComparatorTestTrait
 *
 * @internal
 */
final class ClassHasPropertiesEqualToTest extends TestCase
{
    use PropertiesComparatorTestTrait;

    // required by PropertiesComparatorTestTrait
    public function getPropertiesComparatorClass(): string
    {
        return ClassHasPropertiesEqualTo::class;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
