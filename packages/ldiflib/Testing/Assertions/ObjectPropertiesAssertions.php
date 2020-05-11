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

// Specific records
use Korowai\Lib\Ldif\AbstractRecord;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ObjectPropertiesAssertions
{
    /**
     * Object property getters per class for korowai/ldiflib package.
     *
     * @var array
     */
    protected static $ldiflibObjectPropertyGettersMap = [
        SourceLocationInterface::class  => [
            'fileName'                  => 'getSourceFileName',
            'sourceString'              => 'getSourceString',
            'sourceOffset'              => 'getSourceOffset',
            'sourceCharOffset'          => 'getSourceCharOffset',
            'sourceLineIndex'           => 'getSourceLineIndex',
            'sourceLine'                => 'getSourceLine',
            'sourceLineAndOffset'       => 'getSourceLineAndOffset',
            'sourceLineAndCharOffset'   => 'getSourceLineAndCharOffset',
        ],

        LocationInterface::class        => [
            'string'                    => 'getString',
            'offset'                    => 'getOffset',
            'charOffset'                => 'getCharOffset',
        ],

        CursorInterface::class          => [
        ],

        \Throwable::class               => [
            'message'                   => 'getMessage',
            'code'                      => 'getCode',
            'file'                      => 'getFile',
            'line'                      => 'getLine',
            'trace'                     => 'getTrace',
            'traceAsString'             => 'getTraceAsString',
            'previous'                  => 'getPrevious',
        ],

        ParserErrorInterface::class     => [
            'multilineMessage'          => 'getMultilineMessage'
        ],

        ParserStateInterface::class     => [
            'cursor'                    => 'getCursor',
            'errors'                    => 'getErrors',
            'records'                   => 'getRecords',
            'isOk'                      => 'isOk',
        ],

        SnippetInterface::class         => [
            'length'                    => 'getLength',
            'endOffset'                 => 'getEndOffset',
            'sourceLength'              => 'getSourceLength',
            'sourceEndOffset'           => 'getSourceEndOffset',
            'sourceCharLength'          => 'getSourceCharLength',
            'sourceCharEndOffset'       => 'getSourceCharEndOffset'
        ],

        AttrValInterface::class         => [
            'attribute'                 => 'getAttribute',
            'valueObject'               => 'getValueObject',
        ],

        ControlInterface::class         => [
            'oid'                       => 'getOid',
            'criticality'               => 'getCriticality',
            'valueObject'               => 'getValueObject',
        ],

        ValueInterface::class           => [
            'type'                      => 'getType',
            'spec'                      => 'getSpec',
            'content'                   => 'getContent'
        ],

        UriInterface::class             => [
            'string'                    => '__toString',
            'scheme'                    => 'getScheme',
            'authority'                 => 'getAuthority',
            'userinfo'                  => 'getUserInfo',
            'host'                      => 'getHost',
            'port'                      => 'getPort',
            'path'                      => 'getPath',
            'query'                     => 'getQuery',
            'fragment'                  => 'getFragment',
        ],

        RecordInterface::class          => [
            'dn'                        => 'getDn',
        ],

        AttrValSpecsInterface::class    => [
            'attrValSpecs'              => 'getAttrValSpecs'
        ],

        ChangeRecordInterface::class    => [
            'changeType'                => 'getChangeType'
        ],

        ModDnRecordInterface::class     => [
            'newRdn'                    => 'getNewRdn',
            'deleteOldRdn'              => 'getDeleteOldRdn',
            'newSuperior'               => 'getNewSuperior',
        ],

        ModifyRecordInterface::class    => [
        ],
    ];

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
     * Assert that SourceLocationInterface *$object* has *$expected* properties.
     *
     * @param  array $expected A array of key-value pairs with expected values of attributes.
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
     * @param  array $expected A array of key-value pairs with expected values of attributes.
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
     * @param  array $expected A array of key-value pairs with expected values of attributes.
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
     * @param  array $expected A array of key-value pairs with expected values of attributes.
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
     * @param  array $expected A array of key-value pairs with expected values of attributes.
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

//    /**
//     * Assert that AddRecordInterface *$object* has *$expected* properties.
//     *
//     * @param  array $expected A array of key-value pairs with expected values of attributes.
//     * @param  AddRecordInterface $object An object to be examined.
//     * @param  string|null $message Optional message.
//     */
//    public static function assertAddRecordHas(
//        array $expected,
//        AddRecordInterface $object,
//        string $message = ''
//    ) : void {
//        static::assertHasPropertiesSameAs($expected, $object, $message);
////        static $getGettersMap = [
////            AbstractRecord::class   => 'getAbstractRecordPropertyGetters',
////        ];
////        $class = get_class($object);
////        if (($expectedClass = $expected['class'] ?? null) !== null) {
////            static::assertSame($expectedClass, $class);
////        }
////        $expectedProperties = array_filter($expected, function ($key) {
////            return !in_array($key, ['class']);
////        }, ARRAY_FILTER_USE_KEY);
////        $getGetters = $getGettersMap[$class] ?? $getGettersMap[AbstractRecord::class];
////        $getters = call_user_func([static::class, $getGetters]);
////        static::assertHasPropertiesSameAs($expectedProperties, $object, $message, $getters);
//    }

    /**
     * Assert that ParserStateInterface *$object* has *$expected* properties.
     *
     * @param  array $expected A array of key-value pairs with expected values of attributes.
     * @param  ParserStateInterface $object An object to be examined.
     * @param  string|null $message Optional message.
     */
    public static function assertParserStateHas(
        array $expected,
        ParserStateInterface $object,
        string $message = ''
    ) : void {

        // non-object properties
        $values = array_diff_key($expected, [
            'cursor'        => true, 'getCursor()'   => true,
            'records'       => true, 'getRecords()'  => true,
            'errors'        => true, 'getErrors()'   => true,
        ]);
        static::assertHasPropertiesSameAs($values, $object, $message);

        // object properties
        static::assertObjectEachProperty([
            'cursor'        => [static::class, 'assertCursorHas'],
            'getCursor()'   => [static::class, 'assertCursorHas']
        ], $expected, $object, $message);

        // array-of-objects properties
        static::assertObjectEachPropertyArrayValue([
            'errors'        => [static::class, 'assertParserErrorHas'],
            'getErrors()'   => [static::class, 'assertParserErrorHas'],
            'records'       => [static::class, 'assertRecordHas'],
            'getRecords()'  => [static::class, 'assertRecordHas']
        ], $expected, $object, $message);
    }

    /**
     * Assert that ValueInterface *$object* has *$expected* properties.
     *
     * @param  array $expected A array of key-value pairs with expected values of attributes.
     * @param  ValueInterface $object An object to be examined.
     * @param  string|null $message Optional message.
     */
    public static function assertValueHas(array $expected, ValueInterface $object, string $message = '') : void
    {
        // non-object properties
        $values = array_filter($expected, function ($value, $key) {
            return !in_array($key, ['spec', 'getSpec()']) || is_string($value);
        }, ARRAY_FILTER_USE_BOTH);
        static::assertHasPropertiesSameAs($values, $object, $message);

        // object properties
        $objects = array_diff_key($expected, $values);
        static::assertObjectEachProperty([
            'spec'      => [static::class, 'assertUriHas'],
            'getSpec()' => [static::class, 'assertUriHas'],
        ], $objects, $object, $message);
    }

    /**
     * Assert that AttrValInterface *$object* has *$expected* properties.
     *
     * @param  array $expected A array of key-value pairs with expected values of attributes.
     * @param  AttrValInterface $object An object to be examined.
     * @param  string|null $message Optional message.
     */
    public static function assertAttrValHas(array $expected, AttrValInterface $object, string $message = '') : void
    {
        // non-object properties
        $values = array_diff_key($expected, ['valueObject' => true, 'getValueObject()' => true]);
        static::assertHasPropertiesSameAs($values, $object, $message);

        // object properties
        static::assertObjectEachProperty([
            'valueObject'       => [static::class, 'assertValueHas'],
            'getValueObject()'  => [static::class, 'assertValueHas']
        ], $expected, $object, $message);
    }

    /**
     * Assert that UriInterface *$object* has *$expected* properties.
     *
     * @param  array $expected A array of key-value pairs with expected values of attributes.
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
     * @param  array $expected A array of key-value pairs with expected values of attributes.
     * @param  ControlInterface $object An object to be examined.
     * @param  string|null $message Optional message.
     */
    public static function assertControlHas(array $expected, ControlInterface $object, string $message = '') : void
    {
        // non-object properties
        $values = array_filter($expected, function ($val, $key) {
            return !in_array($key, ['valueObject', 'getValueObject()'], true) || !is_array($val);
        }, ARRAY_FILTER_USE_BOTH);
        static::assertHasPropertiesSameAs($values, $object, $message);

        // object properties
        static::assertObjectEachProperty(array_diff_key([
            'valueObject'       => [static::class, 'assertValueHas'],
            'getValueObject()'  => [static::class, 'assertValueHas']
        ], $values), $expected, $object, $message);
    }
}

// vim: syntax=php sw=4 ts=4 et:
