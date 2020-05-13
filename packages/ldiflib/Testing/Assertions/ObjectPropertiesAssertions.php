<?php
/**
 * @file Testing/Assertions/ObjectPropertiesAssertions.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Lib\Ldif\Assertions;

use Korowai\Lib\Ldif\CursorInterface;
use Korowai\Lib\Ldif\LocationInterface;
use Korowai\Lib\Ldif\ParserErrorInterface;
use Korowai\Lib\Ldif\ParserStateInterface;
use Korowai\Lib\Ldif\RecordInterface;
use Korowai\Lib\Ldif\Records\AttrValSpecsInterface;
use Korowai\Lib\Ldif\Records\AttrValRecordInterface;
use Korowai\Lib\Ldif\Records\ChangeRecordInterface;
use Korowai\Lib\Ldif\Records\AddRecordInterface;
use Korowai\Lib\Ldif\Records\DeleteRecordInterface;
use Korowai\Lib\Ldif\Records\ModDnRecordInterface;
use Korowai\Lib\Ldif\Records\ModifyRecordInterface;
use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Lib\Ldif\ValueInterface;
use Korowai\Lib\Ldif\AttrValInterface;
use Korowai\Lib\Ldif\ControlInterface;
use Korowai\Lib\Ldif\SourceLocationInterface;
use League\Uri\Contracts\UriInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ObjectPropertiesAssertions
{
    /**
     * Asserts that selected properties of *$object* are identical to *$expected* ones.
     *
     * @param  array $expected
     *      An array of key => value pairs with property names as keys and
     *      their expected values as values.
     * @param  object $object
     *      An object to be examined.
     * @param  string $message
     *      Optional failure message.
     * @param  callable $getters
     *      A function that defines mappings between property names and their
     *      getter method names for particular objects.
     *
     * @throws ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception when a non-string keys are found in *$expected*
     */
    abstract public static function assertHasPropertiesSameAs(
        array $expected,
        object $object,
        string $message = '',
        callable $getters = null
    ) : void;

    /**
     * @todo Write documentation
     */
    abstract public static function assertObjectEachProperty(
        array $callbacks,
        array $expected,
        object $object,
        string $message = ''
    ) : void;

    /**
     * @todo Write documentation
     */
    abstract public static function assertObjectEachPropertyArrayValue(
        array $callbacks,
        array $expected,
        object $object,
        string $message = ''
    ) : void;

    /**
     * Assert that *$object* has *$expected* properties. Delegate certain
     * properties to specific assertion methods.
     *
     * Supported *$options*
     *
     * - ``delegate`` (array); keys of *$options['delegate']* specify which
     *   of the *$object* properties should be delegated to other assertion
     *   methods (provided with corresponding values of *$options['delegate']*).
     * - ``delegateArray`` (array) - keys of *$options['delegateArray]* specify
     *   which of the *$object* properties should be treated as arrays whose
     *   items should be delegated to other assertion methods (provided with
     *   corresponding values of *$options['delegateArray']*).
     *
     * @param  array $expected
     *      An array of key-value pairs with expected values of attributes.
     * @param  object $object
     *      An object to be examined.
     * @param  string|null $message
     *      Optional message.
     * @param  array $options A key-value array of options.
     */
    public static function assertLdifObjectHas(
        array $expected,
        object $object,
        string $message = '',
        array $options = []
    ) : void {
        $delegate = $options['delegate'] ?? [];
        $delegateArray = $options['delegateArray'] ?? [];
        $nonValues = array_merge($delegate, $delegateArray);

        // non-object properties
        $values = array_diff_key($expected, $nonValues);
        static::assertHasPropertiesSameAs($values, $object, $message);

        // object properties
        static::assertObjectEachProperty($delegate, $expected, $object, $message);

        // array-of-objects properties
        static::assertObjectEachPropertyArrayValue($delegateArray, $expected, $object, $message);
    }


    /**
     * Assert that SourceLocationInterface *$object* has *$expected* properties.
     *
     * @param  array $expected An array of key-value pairs with expected values of attributes.
     * @param  SourceLocationInterface $object An object to be examined.
     * @param  string|null $message Optional message.
     */
    public static function assertSourceLocationHas(
        array $expected,
        SourceLocationInterface $object,
        string $message = ''
    ) : void {
        static::assertHasPropertiesSameAs($expected, $object, $message);
    }

    /**
     * Assert that LocationInterface *$object* has *$expected* properties.
     *
     * @param  array $expected An array of key-value pairs with expected values of attributes.
     * @param  LocationInterface $object An object to be examined.
     * @param  string|null $message Optional message.
     */
    public static function assertLocationHas(
        array $expected,
        LocationInterface $object,
        string $message = ''
    ) : void {
        static::assertHasPropertiesSameAs($expected, $object, $message);
    }

    /**
     * Assert that CursorInterface *$object* has *$expected* properties.
     *
     * @param  array $expected An array of key-value pairs with expected values of attributes.
     * @param  CursorInterface $object An object to be examined.
     * @param  string|null $message Optional message.
     */
    public static function assertCursorHas(
        array $expected,
        CursorInterface $object,
        string $message = ''
    ) : void {
        static::assertHasPropertiesSameAs($expected, $object, $message);
    }

    /**
     * Assert that SnippetInterface *$object* has *$expected* properties.
     *
     * @param  array $expected An array of key-value pairs with expected values of attributes.
     * @param  SnippetInterface $object An object to be examined.
     * @param  string|null $message Optional message.
     */
    public static function assertSnippetHas(
        array $expected,
        SnippetInterface $object,
        string $message = ''
    ) : void {
        static::assertHasPropertiesSameAs($expected, $object, $message);
    }

    /**
     * Assert that ParserErrorInterface *$object* has *$expected* properties.
     *
     * @param  array $expected An array of key-value pairs with expected values of attributes.
     * @param  ParserErrorInterface $object An object to be examined.
     * @param  string|null $message Optional message.
     */
    public static function assertParserErrorHas(
        array $expected,
        ParserErrorInterface $object,
        string $message = ''
    ) : void {
        static::assertHasPropertiesSameAs($expected, $object, $message);
    }

    /**
     * Assert that AddRecordInterface *$object* has *$expected* properties.
     *
     * @param  array $expected An array of key-value pairs with expected values of attributes.
     * @param  AddRecordInterface $object An object to be examined.
     * @param  string|null $message Optional message.
     */
    public static function assertRecordHas(
        array $expected,
        RecordInterface $object,
        string $message = ''
    ) : void {
        static::assertHasPropertiesSameAs($expected, $object, $message);
    }

    /**
     * Assert that AttrValRecordInterface *$object* has *$expected* properties.
     *
     * @param  array $expected An array of key-value pairs with expected values of attributes.
     * @param  AttrValRecordInterface $object An object to be examined.
     * @param  string|null $message Optional message.
     */
    public static function assertAttrValRecordHas(
        array $expected,
        AttrValRecordInterface $object,
        string $message = ''
    ) : void {
        static::assertLdifObjectHas($expected, $object, $message, [
            'delegateArray' => [
                'attrValSpecs'      => [static::class, 'assertAttrValHas'],
                'getAttrValSpecs()' => [static::class, 'assertAttrValHas'],
            ]
        ]);
    }

    /**
     * Assert that ChangeRecordInterface *$object* has *$expected* properties.
     *
     * @param  array $expected An array of key-value pairs with expected values of attributes.
     * @param  ChangeRecordInterface $object An object to be examined.
     * @param  string|null $message Optional message.
     */
    public static function assertChangeRecordHas(
        array $expected,
        ChangeRecordInterface $object,
        string $message = ''
    ) : void {
        static::assertHasPropertiesSameAs($expected, $object, $message);
    }

    /**
     * Assert that AddRecordInterface *$object* has *$expected* properties.
     *
     * @param  array $expected An array of key-value pairs with expected values of attributes.
     * @param  AddRecordInterface $object An object to be examined.
     * @param  string|null $message Optional message.
     */
    public static function assertAddRecordHas(
        array $expected,
        AddRecordInterface $object,
        string $message = ''
    ) : void {
        static::assertLdifObjectHas($expected, $object, $message, [
            'delegateArray' => [
                'attrValSpecs'      => [static::class, 'assertAttrValHas'],
                'getAttrValSpecs()' => [static::class, 'assertAttrValHas'],
            ]
        ]);
    }

    /**
     * Assert that DeleteRecordInterface *$object* has *$expected* properties.
     *
     * @param  array $expected An array of key-value pairs with expected values of attributes.
     * @param  DeleteRecordInterface $object An object to be examined.
     * @param  string|null $message Optional message.
     */
    public static function assertDeleteRecordHas(
        array $expected,
        DeleteRecordInterface $object,
        string $message = ''
    ) : void {
        static::assertHasPropertiesSameAs($expected, $object, $message);
    }

    /**
     * Assert that ModDnRecordInterface *$object* has *$expected* properties.
     *
     * @param  array $expected An array of key-value pairs with expected values of attributes.
     * @param  ModDnRecordInterface $object An object to be examined.
     * @param  string|null $message Optional message.
     */
    public static function assertModDnRecordHas(
        array $expected,
        ModDnRecordInterface $object,
        string $message = ''
    ) : void {
        static::assertHasPropertiesSameAs($expected, $object, $message);
    }

    /**
     * Assert that ModSpecInterface *$object* has *$expected* properties.
     *
     * @param  array $expected An array of key-value pairs with expected values of attributes.
     * @param  ModifyRecordInterface $object An object to be examined.
     * @param  string|null $message Optional message.
     */
    public static function assertModifyRecordHas(
        array $expected,
        ModSpecRecordInterface $object,
        string $message = ''
    ) : void {
        static::assertLdifObjectHas($expected, $object, $message, [
            'delegate' => [
                'snippet'           => [static::class, 'assertSnippetHas']
                'getSnippet'        => [static::class, 'assertSnippetHas']
            ],
            'delegateArray' => [
                'attrValSpecs'      => [static::class, 'assertAttrValHas'],
                'getAttrValSpecs()' => [static::class, 'assertAttrValHas'],
            ]
        ]);
    }

    /**
     * Assert that ModifyRecordInterface *$object* has *$expected* properties.
     *
     * @param  array $expected An array of key-value pairs with expected values of attributes.
     * @param  ModifyRecordInterface $object An object to be examined.
     * @param  string|null $message Optional message.
     */
    public static function assertModifyRecordHas(
        array $expected,
        ModifyRecordInterface $object,
        string $message = ''
    ) : void {
        static::assertHasPropertiesSameAs($expected, $object, $message);
    }

    /**
     * Assert that ParserStateInterface *$object* has *$expected* properties.
     *
     * @param  array $expected An array of key-value pairs with expected values of attributes.
     * @param  ParserStateInterface $object An object to be examined.
     * @param  string|null $message Optional message.
     */
    public static function assertParserStateHas(
        array $expected,
        ParserStateInterface $object,
        string $message = ''
    ) : void {
        static::assertLdifObjectHas($expected, $object, $message, [
            'delegate' => [
                'cursor'        => [static::class, 'assertCursorHas'],
                'getCursor()'   => [static::class, 'assertCursorHas']
            ],
            'delegateArray' => [
                'errors'        => [static::class, 'assertParserErrorHas'],
                'getErrors()'   => [static::class, 'assertParserErrorHas'],
                'records'       => [static::class, 'assertRecordHas'],
                'getRecords()'  => [static::class, 'assertRecordHas']
            ]
        ]);
    }

    /**
     * Assert that ValueInterface *$object* has *$expected* properties.
     *
     * @param  array $expected An array of key-value pairs with expected values of attributes.
     * @param  ValueInterface $object An object to be examined.
     * @param  string|null $message Optional message.
     */
    public static function assertValueHas(array $expected, ValueInterface $object, string $message = '') : void
    {
        $delegate = array_filter([
            'spec'      => [static::class, 'assertUriHas'],
            'getSpec()' => [static::class, 'assertUriHas'],
        ], function ($value, $key) use ($expected) {
            return !is_string($expected[$key] ?? null);
        }, ARRAY_FILTER_USE_BOTH);

        static::assertLdifObjectHas($expected, $object, $message, ['delegate' => $delegate]);
    }

    /**
     * Assert that AttrValInterface *$object* has *$expected* properties.
     *
     * @param  array $expected An array of key-value pairs with expected values of attributes.
     * @param  AttrValInterface $object An object to be examined.
     * @param  string|null $message Optional message.
     */
    public static function assertAttrValHas(array $expected, AttrValInterface $object, string $message = '') : void
    {
        static::assertLdifObjectHas($expected, $object, $message, [
            'delegate' => [
                'valueObject'       => [static::class, 'assertValueHas'],
                'getValueObject()'  => [static::class, 'assertValueHas']
            ]
        ]);
    }

    /**
     * Assert that UriInterface *$object* has *$expected* properties.
     *
     * @param  array $expected An array of key-value pairs with expected values of attributes.
     * @param  UriInterface $object An object to be examined.
     * @param  string|null $message Optional message.
     */
    public static function assertUriHas(array $expected, UriInterface $object, string $message = '') : void
    {
        static::assertHasPropertiesSameAs($expected, $object, $message);
    }

    /**
     * Assert that ControlInterface *$object* has *$expected* properties.
     *
     * @param  array $expected An array of key-value pairs with expected values of attributes.
     * @param  ControlInterface $object An object to be examined.
     * @param  string|null $message Optional message.
     */
    public static function assertControlHas(array $expected, ControlInterface $object, string $message = '') : void
    {
        $delegate = array_filter([
            'valueObject'       => [static::class, 'assertValueHas'],
            'getValueObject()'  => [static::class, 'assertValueHas']
        ], function ($value, $key) use ($expected) {
            return is_array($expected[$key] ?? null);
        }, ARRAY_FILTER_USE_BOTH);

        static::assertLdifObjectHas($expected, $object, $message, ['delegate' => $delegate]);
    }
}

// vim: syntax=php sw=4 ts=4 et:
