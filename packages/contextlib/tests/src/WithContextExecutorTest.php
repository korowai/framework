<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Context;

use Korowai\Lib\Context\ContextManagerInterface;
use Korowai\Lib\Context\ExecutorInterface;
use Korowai\Lib\Context\WithContextExecutor;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

final class ExceptionEB3IB4EL extends \Exception
{
}

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Context\WithContextExecutor
 *
 * @internal
 */
final class WithContextExecutorTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public function testImplementsExecutorInterface(): void
    {
        $this->assertImplementsInterface(ExecutorInterface::class, WithContextExecutor::class);
    }

    public function testConstruct(): void
    {
        $executor = new WithContextExecutor(['foo', 'bar']);
        $this->assertEquals(['foo', 'bar'], $executor->getContext());
    }

    public function testInvoke(): void
    {
        $in1 = ['foo'];
        $in2 = ['bar'];

        $out1 = null;
        $out2 = null;

        $enter = [];
        $exit = [];

        $cm1 = $this->getMockBuilder(ContextManagerInterface::class)
            ->setMethods(['enterContext', 'exitContext'])
            ->getMock()
        ;
        $cm1->expects($this->once())
            ->method('enterContext')
            ->will($this->returnCallback(
                function () use ($in1, &$enter) {
                    $enter[] = 'cm1';

                    return $in1;
                }
            ))
        ;
        $cm1->expects($this->once())
            ->method('exitContext')
            ->with(null)
            ->will($this->returnCallback(
                function (\Throwable $exception = null) use (&$exit) {
                    $exit[] = 'cm1';

                    return false;
                }
            ))
        ;

        $cm2 = $this->getMockBuilder(ContextManagerInterface::class)
            ->setMethods(['enterContext', 'exitContext'])
            ->getMock()
        ;
        $cm2->expects($this->once())
            ->method('enterContext')
            ->will($this->returnCallback(
                function () use ($in2, &$enter) {
                    $enter[] = 'cm2';

                    return $in2;
                }
            ))
        ;
        $cm2->expects($this->once())
            ->method('exitContext')
            ->with(null)
            ->will($this->returnCallback(
                function (\Throwable $exception = null) use (&$exit) {
                    $exit[] = 'cm2';

                    return false;
                }
            ))
        ;

        $executor = new WithContextExecutor([$cm1, $cm2]);
        $retval = $executor(
            function (array $arg1, array $arg2) use (&$out1, &$out2) {
                $out1 = $arg1;
                $out2 = $arg2;

                return 'baz';
            }
        );

        $this->assertEquals('baz', $retval);

        $this->assertSame($in1, $out1);
        $this->assertSame($in2, $out2);

        $this->assertEquals(['cm1', 'cm2'], $enter);
        $this->assertEquals(['cm2', 'cm1'], $exit);
    }

    public function testInvokeWithException01(): void
    {
        $in1 = ['foo'];
        $in2 = ['bar'];

        $ex1 = null;
        $ex2 = null;

        $enter = [];
        $exit = [];

        $throw = new ExceptionEB3IB4EL('testing exception');

        $cm1 = $this->getMockBuilder(ContextManagerInterface::class)
            ->setMethods(['enterContext', 'exitContext'])
            ->getMock()
        ;
        $cm1->expects($this->once())
            ->method('enterContext')
            ->will($this->returnCallback(
                function () use ($in1, &$enter) {
                    $enter[] = 'cm1';

                    return $in1;
                }
            ))
        ;
        $cm1->expects($this->once())
            ->method('exitContext')
            ->with($throw)
            ->will($this->returnCallback(
                function (\Throwable $exception = null) use (&$exit, &$ex1) {
                    $ex1 = $exception;
                    $exit[] = 'cm1';

                    return false;
                }
            ))
        ;

        $cm2 = $this->getMockBuilder(ContextManagerInterface::class)
            ->setMethods(['enterContext', 'exitContext'])
            ->getMock()
        ;
        $cm2->expects($this->once())
            ->method('enterContext')
            ->will($this->returnCallback(
                function () use ($in2, &$enter) {
                    $enter[] = 'cm2';

                    return $in2;
                }
            ))
        ;
        $cm2->expects($this->once())
            ->method('exitContext')
            ->with($throw)
            ->will($this->returnCallback(
                function (\Throwable $exception = null) use (&$exit, &$ex2) {
                    $ex2 = $exception;
                    $exit[] = 'cm2';

                    return false;
                }
            ))
        ;

        $executor = new WithContextExecutor([$cm1, $cm2]);

        $caught = null;

        try {
            $executor(
                function (array $arg1, array $arg2) use ($throw) {
                    throw $throw;
                }
            );
        } catch (ExceptionEB3IB4EL $e) {
            $caught = $e;
        }

        $this->assertSame($throw, $caught);

        $this->assertSame($throw, $ex1);
        $this->assertSame($throw, $ex2);

        $this->assertEquals(['cm1', 'cm2'], $enter);
        $this->assertEquals(['cm2', 'cm1'], $exit);
    }

    public function testInvokeWithException02(): void
    {
        $in1 = ['foo'];
        $in2 = ['bar'];

        $ex1 = null;
        $ex2 = null;

        $enter = [];
        $exit = [];

        $throw = new ExceptionEB3IB4EL('testing exception');

        $cm1 = $this->getMockBuilder(ContextManagerInterface::class)
            ->setMethods(['enterContext', 'exitContext'])
            ->getMock()
        ;
        $cm1->expects($this->once())
            ->method('enterContext')
            ->will($this->returnCallback(
                function () use ($in1, &$enter) {
                    $enter[] = 'cm1';

                    return $in1;
                }
            ))
        ;
        $cm1->expects($this->once())
            ->method('exitContext')
            ->with(null)
            ->will($this->returnCallback(
                function (\Throwable $exception = null) use (&$exit, &$ex1) {
                    $ex1 = $exception;
                    $exit[] = 'cm1';

                    return false;
                }
            ))
        ;

        $cm2 = $this->getMockBuilder(ContextManagerInterface::class)
            ->setMethods(['enterContext', 'exitContext'])
            ->getMock()
        ;
        $cm2->expects($this->once())
            ->method('enterContext')
            ->will($this->returnCallback(
                function () use ($in2, &$enter) {
                    $enter[] = 'cm2';

                    return $in2;
                }
            ))
        ;
        $cm2->expects($this->once())
            ->method('exitContext')
            ->with($throw)
            ->will($this->returnCallback(
                function (\Throwable $exception = null) use (&$exit, &$ex2) {
                    $ex2 = $exception;
                    $exit[] = 'cm2';

                    return true;
                }
            ))
        ;

        $executor = new WithContextExecutor([$cm1, $cm2]);

        $caught = null;

        try {
            $retval = $executor(
                function (array $arg1, array $arg2) use ($throw) {
                    throw $throw;
                }
            );
        } catch (ExceptionEB3IB4EL $e) {
            $caught = $e;
        }

        $this->assertNull($retval);
        $this->assertNull($caught);

        $this->assertSame(null, $ex1);
        $this->assertSame($throw, $ex2);

        $this->assertEquals(['cm1', 'cm2'], $enter);
        $this->assertEquals(['cm2', 'cm1'], $exit);
    }

    public function testInvokeWhenEnterContextThrows(): void
    {
        $in1 = ['foo'];
        $in2 = ['bar'];

        $ex1 = null;
        $ex2 = null;

        $enter = [];
        $exit = [];

        $throw = new ExceptionEB3IB4EL('testing exception');

        $cm1 = $this->getMockBuilder(ContextManagerInterface::class)
            ->setMethods(['enterContext', 'exitContext'])
            ->getMock()
        ;
        $cm1->expects($this->once())
            ->method('enterContext')
            ->will($this->returnCallback(
                function () use ($in1, &$enter) {
                    $enter[] = 'cm1';

                    return $in1;
                }
            ))
        ;
        $cm1->expects($this->once())
            ->method('exitContext')
            ->with($throw)
            ->will($this->returnCallback(
                function (\Throwable $exception = null) use (&$exit, &$ex1) {
                    $ex1 = $exception;
                    $exit[] = 'cm1';

                    return false;
                }
            ))
        ;

        $cm2 = $this->getMockBuilder(ContextManagerInterface::class)
            ->setMethods(['enterContext', 'exitContext'])
            ->getMock()
        ;
        $cm2->expects($this->once())
            ->method('enterContext')
            ->will($this->returnCallback(
                function () use ($throw) {
                    throw $throw;
                }
            ))
        ;
        $cm2->expects($this->never())
            ->method('exitContext')
            ->will($this->returnCallback(
                function (\Throwable $exception = null) use (&$exit, &$ex2) {
                    $ex2 = $exception;
                    $exit[] = 'cm2';

                    return false;
                }
            ))
        ;

        $executor = new WithContextExecutor([$cm1, $cm2]);

        $caught = null;

        try {
            $retval = $executor(
                function (array $arg1, array $arg2) {
                    return 'ok';
                }
            );
        } catch (ExceptionEB3IB4EL $e) {
            $caught = $e;
        }

        $this->assertFalse(isset($retval));
        $this->assertSame($throw, $caught);

        $this->assertSame($throw, $ex1);
        $this->assertNull($ex2);

        $this->assertEquals(['cm1'], $enter);
        $this->assertEquals(['cm1'], $exit);
    }
}

// vim: syntax=php sw=4 ts=4 et:
