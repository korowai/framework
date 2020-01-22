<?php
/**
 * @file Tests/Traits/ParsesVersionSpecTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits\Tests;

use Korowai\Lib\Ldif\Traits\ParsesVersionSpec;
use Korowai\Lib\Ldif\Traits\SkipsWhitespaces;
use Korowai\Lib\Ldif\Traits\MatchesPatterns;
use Korowai\Lib\Ldif\Preprocessor;
use Korowai\Lib\Ldif\Cursor;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ParsesVersionSpecTest extends TestCase
{
    public function getTestObject()
    {
        $obj = new class {
            use ParsesVersionSpec;
            use SkipsWhitespaces;
            use MatchesPatterns;
        };
        return $obj;
    }

//    protected function createInput(string $source, string $filename = null) : InputInterface
//    {
//        return (new Preprocessor)->preprocess(...(func_get_args()));
//    }
//
//    protected function createCursor(string $source, int $position = 0, string $filename = null)
//    {
//        $input = $this->createInput($source, $filename);
//        return new Cursor($input, $position);
//    }
//
//    protected function createParserState(string $source, int $position = 0, string $filename = null)
//    {
//        return new ParserState($this->createCursor($source, $position, $filename));
//    }
//
//    public function versionNumberCases()
//    {
//        return [
//            [['1'], true, 1]
//        ];
//    }
//
//    /**
//     * @dataProvider versionNumberCases
//     */
//    public function test__parseVersionNumber(array $stateArgs, bool $expResult, int $expVersion = null)
//    {
//        $parser = $this->getTestObject();
//        $state = $this->createParserState(...$stateArgs);
//
//        $begin = $stateArgs[1] ?? 0;
//
//        $result = $parser->parseVersionNumber($state, $version);
//
//        if ($expResult) {
//            $this->assertTrue($result);
//        } else {
//            $this->assertFalse($result);
//            $this->assertSame($begin, $state->getCursor()->getOffset());
//            $this->
//        }
//    }
}

// vim: syntax=php sw=4 ts=4 et:
