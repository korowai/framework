<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Ldaplib;

/**
 * Abstract base class for korowai/ldiflib unit tests.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class TestCase extends \Korowai\Testing\TestCase
{
    /**
     * {@inheritdoc}
     */
    public static function objectPropertyGettersMap() : array
    {
        return array_merge_recursive(
            parent::objectPropertyGettersMap(),
            \Korowai\Testing\Contracts\ObjectPropertyGettersMap::getObjectPropertyGettersMap(),
            \Korowai\Testing\Ldaplib\ObjectPropertyGettersMap::getObjectPropertyGettersMap()
        );
    }
}

// vim: syntax=php sw=4 ts=4 et:
