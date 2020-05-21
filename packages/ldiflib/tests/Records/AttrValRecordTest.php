<?php
/**
 * @file tests/Records/AttrValRecordTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Records;

use Korowai\Lib\Ldif\Records\AttrValRecord;
use Korowai\Lib\Ldif\Records\AttrValRecordInterface;
use Korowai\Lib\Ldif\Records\AbstractRecord;
use Korowai\Lib\Ldif\RecordVisitorInterface;
use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Lib\Ldif\Traits\HasAttrValSpecs;

use Korowai\Testing\Lib\Ldif\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class AttrValRecordTest extends TestCase
{
    public function tets__extends__AbstractRecord()
    {
        $this->assertExtendsClass(AbstractRecord::class, AttraValRecord::class);
    }

    public function test__implements__AttrValRecordInterface()
    {
        $this->assertImplementsInterface(AttrValRecordInterface::class, AttrValRecord::class);
    }

    public function test__uses__HasAttrValSpecs()
    {
        $this->assertUsesTrait(HasAttrValSpecs::class, AttrValRecord::class);
    }

    public function construct__cases()
    {
        $snippet = $this->getMockBuilder(SnippetInterface::class)->getMockForAbstractClass();

        return [
            '__construct("dc=example,dc=org", ["X"])' => [
                'args' => [
                    'dc=example,dc=org',
                    ['X']
                ],
                'expect' => [
                    'dn' => 'dc=example,dc=org',
                    'attrValSpecs' => ['X'],
                    'snippet' => null,
                ]
            ],
            '__construct("dc=example,dc=org", ["X"], ["snippet" => $snippet])' => [
                'args' => [
                    'dc=example,dc=org',
                    ['X'],
                    [ "snippet" => $snippet ],
                ],
                'expect' => [
                    'dn' => 'dc=example,dc=org',
                    'attrValSpecs' => ['X'],
                    'snippet' => $snippet
                ]
            ]
        ];
    }

    /**
     * @dataProvider construct__cases
     */
    public function test__construct(array $args, array $expect)
    {
        $record = new AttrValRecord(...$args);
        $this->assertHasPropertiesSameAs($expect, $record);
    }

    public function test__setAttrValSpecs()
    {
        $record = new AttrValRecord("dc=example,dc=org", []);

        $this->assertSame($record, $record->setAttrValSpecs(['X']));
        $this->assertSame(['X'], $record->getAttrValSpecs());
    }

    public function test__acceptRecordVisitor()
    {
        $visitor = $this->getMockBuilder(RecordVisitorInterface::class)
                        ->getMockForAbstractClass();

        $record = new AttrValRecord("dc=example,dc=org", []);

        $visitor->expects($this->once())
                ->method('visitAttrValRecord')
                ->with($record)
                ->will($this->returnValue('ok'));

        $this->assertSame('ok', $record->acceptRecordVisitor($visitor));
    }
}

// vim: syntax=php sw=4 ts=4 et:
