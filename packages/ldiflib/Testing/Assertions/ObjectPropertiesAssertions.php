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
use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Lib\Ldif\ValueInterface;
use Korowai\Lib\Ldif\AttrValInterface;
use Korowai\Lib\Ldif\SourceLocationInterface;
use League\Uri\Contracts\UriInterface;

// Specific records
use Korowai\Lib\Ldif\Records\AbstractRecord;
use Korowai\Lib\Ldif\Records\VersionSpec;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ObjectPropertiesAssertions
{
    /**
     * Asserts that selected properties of *$object* are identical with *$expected* ones.
     *
     * @param  array $expected An array of key-value pairs with expected values of attributes.
     * @param  object $object An object to be examined.
     * @param  array $options An array of options.
     *
     * @throws ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception when a non-string keys are found in *$expected*
     */
    abstract public static function assertHasPropertiesSameAs(
        array $expected,
        object $object,
        array $options = []
    ) : void;

    /**
     * Returns getters for SourceLocationInterface properties.
     *
     * @return array
     */
    public static function getSourceLocationPropertyGetters() : array
    {
        return [
            'fileName'                  => 'getSourceFileName',
            'sourceString'              => 'getSourceString',
            'sourceOffset'              => 'getSourceOffset',
            'sourceCharOffset'          => 'getSourceCharOffset',
            'sourceLineIndex'           => 'getSourceLineIndex',
            'sourceLine'                => 'getSourceLine',
            'sourceLineAndOffset'       => 'getSourceLineAndOffset',
            'sourceLineAndCharOffset'   => 'getSourceLineAndCharOffset',
        ];
    }

    /**
     * Returns getters for LocationInterface properties.
     *
     * @return array
     */
    public static function getLocationPropertyGetters() : array
    {
        return array_merge(static::getSourceLocationPropertyGetters(), [
            'string'                    => 'getString',
            'offset'                    => 'getOffset',
            'charOffset'                => 'getCharOffset',
        ]);
    }

    /**
     * Returns getters for CursorInterface properties.
     *
     * @return array
     */
    public static function getCursorPropertyGetters() : array
    {
        return array_merge(static::getLocationPropertyGetters(), [
        ]);
    }

    /**
     * Returns getters for \Throwable properties.
     *
     * @return array
     */
    public static function getThrowablePropertyGetters() : array
    {
        return [
            'message'                   => 'getMessage',
            'code'                      => 'getCode',
            'file'                      => 'getFile',
            'line'                      => 'getLine',
            'trace'                     => 'getTrace',
            'traceAsString'             => 'getTraceAsString',
            'previous'                  => 'getPrevious',
        ];
    }

    /**
     * Returns getters for LocationInterface properties.
     *
     * @return array
     */
    public static function getParserErrorPropertyGetters() : array
    {
        return array_merge(
            static::getSourceLocationPropertyGetters(),
            static::getThrowablePropertyGetters(),
            [
                'multilineMessage'          => 'getMultilineMessage'
            ]
        );
    }

    /**
     * Returns getters for ParserStateInterface properties.
     *
     * @return array
     */
    public static function getParserStatePropertyGetters() : array
    {
        return [
            'isOk'                      => 'isOk',
        ];
    }

    /**
     * Returns getters for SnippetInterface properties.
     *
     * @return array
     */
    public static function getSnippetPropertyGetters() : array
    {
        return array_merge(static::getLocationPropertyGetters(), [
            'length'                    => 'getLength',
            'endOffset'                 => 'getEndOffset',
            'sourceLength'              => 'getSourceLength',
            'sourceEndOffset'           => 'getSourceEndOffset',
            'sourceCharLength'          => 'getSourceCharLength',
            'sourceCharEndOffset'       => 'getSourceCharEndOffset'
        ]);
    }

    /**
     * Returns getters for RecordInterface properties.
     *
     * @return array
     */
    public static function getAbstractRecordPropertyGetters() : array
    {
        return array_merge(static::getSnippetPropertyGetters(), [
        ]);
    }

    /**
     * Returns getters for VersionSpec record's properties.
     *
     * @return array
     */
    public static function getVersionSpecPropertyGetters() : array
    {
        return array_merge(static::getAbstractRecordPropertyGetters(), [
            'version'                   => 'getVersion'
        ]);
    }

    /**
     * Returns getters for Value object's properties.
     *
     * @return array
     */
    public static function getValuePropertyGetters() : array
    {
        return [
            'type'                      => 'getType',
            'spec'                      => 'getSpec',
            'content'                   => 'getContent'
        ];
    }

    /**
     * Returns getters for AttrVal object's properties.
     *
     * @return array
     */
    public static function getAttrValPropertyGetters() : array
    {
        return array_merge(static::getAbstractRecordPropertyGetters(), [
            'attribute'                 => 'getAttribute',
        ]);
    }

    /**
     * Returns getters for UriInterface object's properties.
     *
     * @return array
     */
    public static function getUriPropertyGetters() : array
    {
        return [
            'string'                    => '__toString',
            'scheme'                    => 'getScheme',
            'authority'                 => 'getAuthority',
            'userinfo'                  => 'getUserInfo',
            'host'                      => 'getHost',
            'port'                      => 'getPort',
            'path'                      => 'getPath',
            'query'                     => 'getQuery',
            'fragment'                  => 'getFragment',
        ];
    }

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
        $options = ['getters' => static::getSourceLocationPropertyGetters(), 'message' => $message];
        static::assertHasPropertiesSameAs($expected, $object, $options);
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
        $options = ['getters' => static::getLocationPropertyGetters(), 'message' => $message];
        static::assertHasPropertiesSameAs($expected, $object, $options);
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
        $options = ['getters' => static::getCursorPropertyGetters(), 'message' => $message];
        static::assertHasPropertiesSameAs($expected, $object, $options);
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
        $options = ['getters' => static::getSnippetPropertyGetters(), 'message' => $message];
        static::assertHasPropertiesSameAs($expected, $object, $options);
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
        $options = ['getters' => static::getParserErrorPropertyGetters(), 'message' => $message];
        static::assertHasPropertiesSameAs($expected, $object, $options);
    }

    /**
     * Assert that RecordInterface *$object* has *$expected* properties.
     *
     * @param  array $expected A array of key-value pairs with expected values of attributes.
     * @param  RecordInterface $object An object to be examined.
     * @param  string|null $message Optional message.
     */
    public static function assertRecordHas(
        array $expected,
        RecordInterface $object,
        string $message = ''
    ) : void {
        static $getGettersMap = [
            AbstractRecord::class   => 'getAbstractRecordPropertyGetters',
            VersionSpec::class      => 'getVersionSpecPropertyGetters',
        ];
        $class = get_class($object);
        if (($expectedClass = $expected['class'] ?? null) !== null) {
            static::assertSame($expectedClass, $class);
        }
        $expectedProperties = array_filter($expected, function ($key) {
            return !in_array($key, ['class']);
        }, ARRAY_FILTER_USE_KEY);
        $getGetters = $getGettersMap[$class] ?? $getGettersMap[AbstractRecord::class];
        $getters = call_user_func([static::class, $getGetters]);
        $options = ['getters' => $getters, 'message' => $message];
        static::assertHasPropertiesSameAs($expectedProperties, $object, $options);
    }

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
        $expectedValues = array_filter($expected, function ($key) {
            return !in_array($key, ['cursor', 'records', 'errors']);
        }, ARRAY_FILTER_USE_KEY);

        $options = ['getters' => static::getParserStatePropertyGetters(), 'message' => $message];
        static::assertHasPropertiesSameAs($expectedValues, $object, $options);

        if (($expectedCursorProperties = $expected['cursor'] ?? null) !== null) {
            static::assertCursorHas($expectedCursorProperties, $object->getCursor(), $message);
        }

        if (($expectedErrors = $expected['errors'] ?? null) !== null) {
            static::assertArrayItemsUsingCallback(
                [static::class, 'assertParserErrorHas'],
                $expectedErrors,
                $object->getErrors(),
                $message
            );
        }

        if (($expectedRecords = $expected['records'] ?? null) !== null) {
            static::assertArrayItemsUsingCallback(
                [static::class, 'assertRecordHas'],
                $expectedRecords,
                $object->getRecords(),
                $message
            );
        }
    }

    /**
     * Assert that ValueInterface *$object* has *$expected* properties.
     *
     * @param  array $expected A array of key-value pairs with expected values of attributes.
     * @param  ValueInterface $object An object to be examined.
     * @param  string|null $message Optional message.
     */
    public static function assertValueHas(
        array $expected,
        ValueInterface $object,
        string $message = ''
    ) : void {
        $expectedValues = array_filter($expected, function ($value, $key) {
            return $key !== 'spec' || is_string($value) ;
        }, ARRAY_FILTER_USE_BOTH);
        $options = ['getters' => static::getValuePropertyGetters(), 'message' => $message];
        static::assertHasPropertiesSameAs($expectedValues, $object, $options);
        if (array_key_exists('spec', $expected) && !array_key_exists('spec', $expectedValues)) {
            static::assertUriHas($expected['spec'], $object->getSpec(), $message);
        }
    }

    /**
     * Assert that AttrValInterface *$object* has *$expected* properties.
     *
     * @param  array $expected A array of key-value pairs with expected values of attributes.
     * @param  AttrValInterface $object An object to be examined.
     * @param  string|null $message Optional message.
     */
    public static function assertAttrValHas(
        array $expected,
        AttrValInterface $object,
        string $message = ''
    ) : void {
        $expectedValues = array_filter($expected, function ($key) {
            return $key !== 'valueObject';
        }, ARRAY_FILTER_USE_KEY);
        $options = ['getters' => static::getAttrValPropertyGetters(), 'message' => $message];
        static::assertHasPropertiesSameAs($expectedValues, $object, $options);

        if (array_key_exists('valueObject', $expected)) {
            static::assertValueHas($expected['valueObject'], $object->getValueObject(), $message);
        }
    }


    /**
     * Asserts that all *$items* pass assertion provided as *$callback*.
     *
     * @param  callable $callback The assertion callback applied to each of *$items*.
     * @param  array $expected Expected items.
     * @param  mixed $items The items to be examined.
     * @param  string $message
     */
    public static function assertArrayItemsUsingCallback(
        callable $callback,
        array $expected,
        $items,
        string $message = ''
    ) : void {
        static::assertIsArray($items);
        static::assertCount(count($expected), $items);
        for ($i = 0; $i < count($items); $i++) {
            call_user_func($callback, $expected[$i], $items[$i], $message);
        }
    }

    /**
     * Assert that UriInterface *$object* has *$expected* properties.
     *
     * @param  array $expected A array of key-value pairs with expected values of attributes.
     * @param  UriInterface $object An object to be examined.
     * @param  string|null $message Optional message.
     */
    public static function assertUriHas(
        array $expected,
        UriInterface $object,
        string $message = ''
    ) : void {
        $options = ['getters' => static::getUriPropertyGetters(), 'message' => $message];
        static::assertHasPropertiesSameAs($expected, $object, $options);
    }
}

// vim: syntax=php sw=4 ts=4 et:
