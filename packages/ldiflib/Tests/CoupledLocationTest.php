<?php
/**
 * @file Tests/CoupledLocationTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Tests;

use Korowai\Lib\Ldif\CoupledLocation;
use Korowai\Lib\Ldif\CoupledLocationInterface;
use Korowai\Lib\Ldif\CoupledInputInterface;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class CoupledLocationTest extends TestCase
{
    public function test__implements__CoupledLocationInterface()
    {
        $interfaces = class_implements(CoupledLocation::class);
        $this->assertContains(CoupledLocationInterface::class, $interfaces);
    }

    public function test__construct()
    {
        $input = $this->getMockBuilder(CoupledInputInterface::class)
                      ->getMockForAbstractClass();
        $location = new CoupledLocation($input, 12);

        $this->assertSame($input, $location->getInput());
        $this->assertSame(12, $location->getByteOffset());
    }

    public function test__getString()
    {
        $input = $this->getMockBuilder(CoupledInputInterface::class)
                      ->getMockForAbstractClass();
        $input->expects($this->once())
              ->method('getString')
              ->with()
              ->willReturn('A');

        $location = new CoupledLocation($input, 0);
        $this->assertSame('A', $location->getString());
    }

    public function charOffsetCases()
    {
        return [
            [
                "",
                [
                    0 => 0,
                    1 => 0,
                ]
            ],

            [
            //   012467
                "dałże",
                [
                    0 => 0, // d
                    1 => 1, // a
                    2 => 2, // ł
                    4 => 3, // ż
                    6 => 4, // e
                    7 => 5, // EOF
                ]
            ]
        ];
    }

    /**
     * @dataProvider charOffsetCases
     */
    public function test__getCharOffset(string $string, array $cases)
    {
        $input = $this->getMockBuilder(CoupledInputInterface::class)
                      ->getMockForAbstractClass();
        $input->expects($this->any())
              ->method('getString')
              ->with()
              ->willReturn($string);

        foreach ($cases as $i => $j) {
            $location = new CoupledLocation($input, $i);
            $this->assertSame($j, $location->getCharOffset());
        }
    }

    public function test__getSourceFileName()
    {
        $input = $this->getMockBuilder(CoupledInputInterface::class)
                      ->getMockForAbstractClass();
        $input->expects($this->once())
              ->method('getSourceFileName')
              ->with()
              ->willReturn('foo.ldif');

        $location = new CoupledLocation($input, 0);
        $this->assertSame('foo.ldif', $location->getSourceFileName());
    }

    public function test__getSourceString()
    {
        $input = $this->getMockBuilder(CoupledInputInterface::class)
                      ->getMockForAbstractClass();
        $input->expects($this->once())
              ->method('getSourceString')
              ->with()
              ->willReturn('A');

        $location = new CoupledLocation($input, 0);
        $this->assertSame('A', $location->getSourceString());
    }

    public function test__getSourceByteOffset()
    {
        $input = $this->getMockBuilder(CoupledInputInterface::class)
                      ->getMockForAbstractClass();
        $input->expects($this->once())
              ->method('getSourceByteOffset')
              ->with(2)
              ->willReturn(4);

        $location = new CoupledLocation($input, 2);
        $this->assertSame(4, $location->getSourceByteOffset());
    }

    public function test__getSourceCharOffset()
    {
        $input = $this->getMockBuilder(CoupledInputInterface::class)
                      ->getMockForAbstractClass();
        $input->expects($this->once())
              ->method('getSourceCharOffset')
              ->with(4, 'U')
              ->willReturn(2);

        $location = new CoupledLocation($input, 4);
        $this->assertSame(2, $location->getSourceCharOffset('U'));
    }

    public function test__getSourceLineIndex()
    {
        $input = $this->getMockBuilder(CoupledInputInterface::class)
                      ->getMockForAbstractClass();
        $input->expects($this->once())
              ->method('getSourceLineIndex')
              ->with(4)
              ->willReturn(1);

        $location = new CoupledLocation($input, 4);
        $this->assertSame(1, $location->getSourceLineIndex());
    }

    public function test__getSourceLine()
    {
        $input = $this->getMockBuilder(CoupledInputInterface::class)
                      ->getMockForAbstractClass();
        $input->expects($this->once())
              ->method('getSourceLineIndex')
              ->with(4)
              ->willReturn(1);
        $input->expects($this->once())
              ->method('getSourceLine')
              ->with(1)
              ->willReturn('A');

        $location = new CoupledLocation($input, 4);
        $this->assertSame('A', $location->getSourceLine());
    }

    public function test__getSourceLineAndByteOffset()
    {
        $input = $this->getMockBuilder(CoupledInputInterface::class)
                      ->getMockForAbstractClass();
        $input->expects($this->once())
              ->method('getSourceLineAndByteOffset')
              ->with(4)
              ->willReturn(['A',1]);

        $location = new CoupledLocation($input, 4);
        $this->assertSame(['A',1], $location->getSourceLineAndByteOffset());
    }

    public function test__getSourceLineAndCharOffset()
    {
        $input = $this->getMockBuilder(CoupledInputInterface::class)
                      ->getMockForAbstractClass();
        $input->expects($this->once())
              ->method('getSourceLineAndCharOffset')
              ->with(4, 'U')
              ->willReturn(['A',1]);

        $location = new CoupledLocation($input, 4);
        $this->assertSame(['A',1], $location->getSourceLineAndCharOffset('U'));
    }
}

// vim: syntax=php sw=4 ts=4 et:
