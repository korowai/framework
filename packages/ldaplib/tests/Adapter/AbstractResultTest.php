<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Adapter;

use Korowai\Testing\TestCase;

use Korowai\Lib\Ldap\Adapter\AbstractResult;
use Korowai\Lib\Ldap\Adapter\ResultEntryInterface;
use Korowai\Lib\Ldap\Adapter\ResultEntryIteratorInterface;
use Korowai\Lib\Ldap\Adapter\ResultInterface;
use Korowai\Lib\Ldap\EntryInterface;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class AbstractResultTest extends TestCase
{
    private function getAbstractResultMock($ctor = true, array $methods = [])
    {
        $builder = $this->getMockBuilder(AbstractResult::class);

        if (!$ctor) {
            $builder->disableOriginalConstructor();
        } elseif (is_array($ctor)) {
            $builder->setConstructorArgs($ctor);
        }

        foreach (['getResultEntryIterator', 'getResultReferenceIterator'] as $method) {
            if (!in_array($method, $methods)) {
                $methods[] = $method;
            }
        }
        $builder->setMethods($methods);
        return $builder->getMockForAbstractClass();
    }

    //
    //
    // TESTS
    //
    //

    public function test__implements__ResultInterface()
    {
        $this->assertImplementsInterface(ResultInterface::class, AbstractResult::class);
    }

    public function test__getEntries()
    {
        $entries = ['e1', 'e2'];
        $result = $this->getAbstractResultMock(true, ['getIterator']);
        $result->expects($this->once())
               ->method('getIterator')
               ->with()
               ->willReturn(new \ArrayIterator($entries));
        $this->assertEquals($entries, $result->getEntries());
    }

    public function test__getIterator()
    {
        $resultEntries = [
            $this->getMockBuilder(ResultEntryInterface::class)
                 ->setMethods(['toEntry'])
                 ->getMockForAbstractClass(),
            $this->getMockBuilder(ResultEntryInterface::class)
                 ->setMethods(['toEntry'])
                 ->getMockForAbstractClass(),
        ];

        $entries = [
            $this->getMockBuilder(EntryInterface::class)
                 ->getMockForAbstractClass(),
            $this->getMockBuilder(EntryInterface::class)
                 ->getMockForAbstractClass(),
        ];

        foreach ($resultEntries as $offset => $resultEntry) {
            $resultEntry->expects($this->once())
                        ->method('toEntry')
                        ->with()
                        ->willReturn($entries[$offset]);
        }


        $iterator = $this->getMockBuilder(ResultEntryIteratorInterface::class)
                         ->setMethods(['rewind', 'key', 'valid', 'next', 'current'])
                         ->getMockForAbstractClass();

        $iterator->expects($this->once())
                 ->method('rewind')
                 ->with();

        $iterator->expects($this->exactly(count($resultEntries)))
                 ->method('key')
                 ->with()
                 ->will($this->onConsecutiveCalls(...range(0, count($resultEntries)-1)));

        $iterator->expects($this->exactly(1 + count($resultEntries)))
                 ->method('valid')
                 ->with()
                 ->will($this->onConsecutiveCalls(true, true, false));

        $iterator->expects($this->exactly(count($resultEntries)))
                 ->method('next')
                 ->with();

        $iterator->expects($this->exactly(count($resultEntries)))
                 ->method('current')
                 ->with()
                 ->will($this->onConsecutiveCalls(...$resultEntries));

        $result = $this->getAbstractResultMock();
        $result->expects($this->once())
               ->method('getResultEntryIterator')
               ->with()
               ->willReturn($iterator);

        $i = 0;
        foreach ($result as $offset => $entry) {
            $this->assertSame($entries[$offset], $entry);
            $this->assertSame($i, $offset);
            $i++;
        }
    }

    public function test__getIterator__withBuggyIterator()
    {

        $iterator = $this->getMockBuilder(ResultEntryIteratorInterface::class)
                         ->setMethods(['rewind', 'valid', 'key', 'current'])
                         ->getMockForAbstractClass();

        $iterator->expects($this->once())
                 ->method('rewind')
                 ->with();

        $iterator->expects($this->once())
                 ->method('key')
                 ->with()
                 ->willReturn(null);

        $iterator->expects($this->once())
                 ->method('valid')
                 ->with()
                 ->willReturn(true);

        $iterator->expects($this->once())
                 ->method('current')
                 ->with()
                 ->willReturn(null);

        $result = $this->getAbstractResultMock();
        $result->expects($this->once())
               ->method('getResultEntryIterator')
               ->with()
               ->willReturn($iterator);

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage(sprintf('Null returned by %s::current() during iteration', get_class($iterator)));

        foreach ($result as $offset => $entry) {
            continue;
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
