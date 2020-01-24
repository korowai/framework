<?php
/**
 * @file Testing/PackagesDetails.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing;

/**
 * Describes expected details of all Korowai packages altogether.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class PackagesDetails implements PackageDetailsInterface
{
    protected static $packageDetailsClasses = [
        Contracts\PackageDetails::class
    ];

    protected static function merge(string $detail, array $classes)
    {
        $arrays = array_map(function ($class) use ($detail) {
            return call_user_func([$class, $detail]);
        }, $classes);
        return array_merge(...$arrays);
    }

    /**
     * {@inheritdoc}
     */
    public static function objectProperties() : array
    {
        return self::merge('objectProperties', self::$packageDetailsClasses);
    }

    /**
     * {@inheritdoc}
     */
    public static function interfaceInheritance() : array
    {
        return self::merge('interfaceInheritance', self::$packageDetailsClasses);
    }
}

// vim: syntax=php sw=4 ts=4 et:
