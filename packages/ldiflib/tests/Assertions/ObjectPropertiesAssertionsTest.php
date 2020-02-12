<?php
/**
 * @file tests/Assertions/ObjectPropertiesAssertionsTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Assertions;

use Korowai\Testing\TestCase;
use Korowai\Testing\Lib\Ldif\Assertions\ObjectPropertiesAssertions;
use Korowai\Lib\Ldif\SourceLocationInterface;
use Korowai\Lib\Ldif\LocationInterface;
use Korowai\Lib\Ldif\CursorInterface;
use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Lib\Ldif\ParserErrorInterface;
use Korowai\Lib\Ldif\ParserError;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ObjectPropertiesAssertionsTest extends TestCase
{
    use ObjectPropertiesAssertions;

    public function test__assertSourceLocationHas()
    {
        $getters = [
            'getSourceFileName'         => 'foo.ldif',
            'getSourceString'           => "# comment\nversion: 1\n",
	        'getSourceOffset'           => 10,
	        'getSourceCharOffset'       => 10,
	        'getSourceLineIndex'        => 1,
	        'getSourceLine'             => "version: 1",
	        'getSourceLineAndOffset'    => [1, 0],
	        'getSourceLineAndCharOffset'=> [1, 0],
        ];

        $location = $this->getMockBuilder(SourceLocationInterface::class)->getMockForAbstractClass();
        foreach ($getters as $method => $value) {
            $location->expects($this->once())
                     ->method($method)
                     ->with()
                     ->willReturn($value);
        }

        $this->assertSourceLocationHas([
            'fileName'               => 'foo.ldif',
            'sourceString'           => "# comment\nversion: 1\n",
	        'sourceOffset'           => 10,
	        'sourceCharOffset'       => 10,
	        'sourceLineIndex'        => 1,
	        'sourceLine'             => "version: 1",
	        'sourceLineAndOffset'    => [1, 0],
	        'sourceLineAndCharOffset'=> [1, 0],
        ], $location);
    }

    public function test__assertLocationHas()
    {
        $getters = [
            'getSourceFileName'         => 'foo.ldif',
            'getSourceString'           => "# comment\nversion: 1\n",
	        'getSourceOffset'           => 10,
	        'getSourceCharOffset'       => 10,
	        'getSourceLineIndex'        => 1,
	        'getSourceLine'             => "version: 1",
	        'getSourceLineAndOffset'    => [1, 0],
	        'getSourceLineAndCharOffset'=> [1, 0],
            'getString'                 => "version: 1\n",
            'getOffset'                 => 0,
            'getCharOffset'             => 0,
        ];

        $location = $this->getMockBuilder(LocationInterface::class)->getMockForAbstractClass();
        foreach ($getters as $method => $value) {
            $location->expects($this->once())
                     ->method($method)
                     ->with()
                     ->willReturn($value);
        }

        $this->assertLocationHas([
            'fileName'               => 'foo.ldif',
            'sourceString'           => "# comment\nversion: 1\n",
	        'sourceOffset'           => 10,
	        'sourceCharOffset'       => 10,
	        'sourceLineIndex'        => 1,
	        'sourceLine'             => "version: 1",
	        'sourceLineAndOffset'    => [1, 0],
	        'sourceLineAndCharOffset'=> [1, 0],
            'string'                 => "version: 1\n",
            'offset'                 => 0,
            'charOffset'             => 0,
        ], $location);
    }

    public function test__assertCursorHas()
    {
        $getters = [
            'getSourceFileName'         => 'foo.ldif',
            'getSourceString'           => "# comment\nversion: 1\n",
	        'getSourceOffset'           => 10,
	        'getSourceCharOffset'       => 10,
	        'getSourceLineIndex'        => 1,
	        'getSourceLine'             => "version: 1",
	        'getSourceLineAndOffset'    => [1, 0],
	        'getSourceLineAndCharOffset'=> [1, 0],
            'getString'                 => "version: 1\n",
            'getOffset'                 => 0,
            'getCharOffset'             => 0,
        ];

        $cursor = $this->getMockBuilder(CursorInterface::class)->getMockForAbstractClass();
        foreach ($getters as $method => $value) {
            $cursor->expects($this->once())
                   ->method($method)
                   ->with()
                   ->willReturn($value);
        }

        $this->assertCursorHas([
            'fileName'               => 'foo.ldif',
            'sourceString'           => "# comment\nversion: 1\n",
	        'sourceOffset'           => 10,
	        'sourceCharOffset'       => 10,
	        'sourceLineIndex'        => 1,
	        'sourceLine'             => "version: 1",
	        'sourceLineAndOffset'    => [1, 0],
	        'sourceLineAndCharOffset'=> [1, 0],
            'string'                 => "version: 1\n",
            'offset'                 => 0,
            'charOffset'             => 0,
        ], $cursor);
    }

    public function test__assertSnippetHas()
    {
        $getters = [
            'getSourceFileName'         => 'foo.ldif',
            'getSourceString'           => "# comment\nversion: 1\n",
	        'getSourceOffset'           => 10,
	        'getSourceCharOffset'       => 10,
	        'getSourceLineIndex'        => 1,
	        'getSourceLine'             => "version: 1",
	        'getSourceLineAndOffset'    => [1, 0],
	        'getSourceLineAndCharOffset'=> [1, 0],
            'getString'                 => "version: 1\n",
            'getOffset'                 => 0,
            'getCharOffset'             => 0,
            'getLength'                 => 7,
	        'getEndOffset'              => 7,
	        'getSourceLength'           => 7,
	        'getSourceEndOffset'        => 17,
	        'getSourceCharLength'       => 7,
	        'getSourceCharEndOffset'    => 17,
        ];

        $snippet = $this->getMockBuilder(SnippetInterface::class)->getMockForAbstractClass();
        foreach ($getters as $method => $value) {
            $snippet->expects($this->once())
                    ->method($method)
                    ->with()
                    ->willReturn($value);
        }

        $this->assertSnippetHas([
            'fileName'               => 'foo.ldif',
            'sourceString'           => "# comment\nversion: 1\n",
	        'sourceOffset'           => 10,
	        'sourceCharOffset'       => 10,
	        'sourceLineIndex'        => 1,
	        'sourceLine'             => "version: 1",
	        'sourceLineAndOffset'    => [1, 0],
	        'sourceLineAndCharOffset'=> [1, 0],
            'string'                 => "version: 1\n",
            'offset'                 => 0,
            'charOffset'             => 0,
            'length'                 => 7,
	        'endOffset'              => 7,
	        'sourceLength'           => 7,
	        'sourceEndOffset'        => 17,
	        'sourceCharLength'       => 7,
	        'sourceCharEndOffset'    => 17,
        ], $snippet);
    }

    public function test__assertParserErrorHas()
    {
        $previous = new \Exception('previous');

        $getters = [
            'getSourceFileName'         => 'foo.ldif',
            'getSourceString'           => "# comment\nversion: 1\n",
	        'getSourceOffset'           => 10,
	        'getSourceCharOffset'       => 10,
	        'getSourceLineIndex'        => 1,
	        'getSourceLine'             => "version: 1",
	        'getSourceLineAndOffset'    => [1, 0],
	        'getSourceLineAndCharOffset'=> [1, 0],
        ];

        $location = $this->getMockBuilder(SourceLocationInterface::class)->getMockForAbstractClass();
        foreach ($getters as $method => $value) {
            $location->expects($this->atLeastOnce())
                     ->method($method)
                     ->with()
                     ->willReturn($value);
        }

        $line = 1 + __line__;
        $error = new ParserError($location, 'syntax error: foo', 0, $previous);

        $this->assertParserErrorHas([
            'fileName'               => 'foo.ldif',
            'sourceString'           => "# comment\nversion: 1\n",
	        'sourceOffset'           => 10,
	        'sourceCharOffset'       => 10,
	        'sourceLineIndex'        => 1,
	        'sourceLine'             => "version: 1",
	        'sourceLineAndOffset'    => [1, 0],
	        'sourceLineAndCharOffset'=> [1, 0],
            'message'                => "syntax error: foo",
            'code'                   => 0,
            'file'                   => __file__,
            'line'                   => $line,
//            'trace'                  => ['T'],
//            'traceAsString'          => 'T',
            'previous'               => $previous,
            'multilineMessage'       => "foo.ldif:2:1:syntax error: foo\nfoo.ldif:2:1:version: 1\nfoo.ldif:2:1:^",
        ], $error);
    }

    public function test__assertRecordHas()
    {
        $this->markTestIncomplete('The test has not been implemented yet.');
    }

    public function test__assertParserStateHas()
    {
        $this->markTestIncomplete('The test has not been implemented yet.');
    }

    public function test__assertArrayItemsUsingCallback()
    {
        $this->markTestIncomplete('The test has not been implemented yet.');
    }
}

// vim: syntax=php sw=4 ts=4 et:
