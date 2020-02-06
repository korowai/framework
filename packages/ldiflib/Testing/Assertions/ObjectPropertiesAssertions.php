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
use Korowai\Lib\Ldif\SourceLocationInterface;

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
}

// vim: syntax=php sw=4 ts=4 et:
