<?php
/**
 * @file Testing/Traits/ObjectPropertiesUtils.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/testing
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Traits;

/**
 * Example trait for testing purposes.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ObjectPropertiesUtils
{
    /**
     * Returns a key-value array which maps class names onto arrays of property
     * getters. Each array of property getters is a key-value array with keys
     * being property names and values being names of corresponding getter methods.
     *
     * @return array
     */
    abstract public static function objectPropertyGettersMap() : array;

    /**
     * Returns array of property getters intended to be used with objects of
     * given *$class*.
     *
     * @param  string $class Fully qualified class name
     * @return array
     */
    public static function getObjectPropertyGetters(string $class) : array
    {
        $all = class_implements($class);
        $classes = array_merge(class_parents($class), [$class => $class]);

        foreach ($classes as $key => $val) {
            $all = array_merge($all, class_uses($key), [$key => $val]);
        }

        return array_merge(...array_map(function (string $key) {
            $getters = static::objectPropertyGettersMap();
            return $getters[$key] ?? [];
        }, array_keys($all)));
    }

    /**
     * Returns object's property identified by *$key*.
     *
     * @param  object $object
     * @param  string $key
     * @param  array $getters
     * @return mixed
     */
    public static function getObjectProperty(object $object, string $key, array $getters = null)
    {
        $class = get_class($object);
        $getters = $getters ?? static::getObjectPropertyGetters($class);
        $getter = substr($key, -2) === '()' ? substr($key, 0, -2) : $getters[$key] ?? null;
        if ($getter !== null) {
            return call_user_func([$object, $getter]);
        } else {
            return $object->{$key};
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
