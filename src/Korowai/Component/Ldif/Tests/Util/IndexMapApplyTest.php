<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Component\Ldif\Tests\Util;

use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class IndexMapApplyTest extends TestCase
{
    protected function getTestObj()
    {
        return new class {
            use \Korowai\Component\Ldif\Util\IndexMapApply {
                imApply as public;
            }
        };
    }

    public function test__imApply__00()
    {
        $obj = $this->getTestObj();

        $im = [];
        $this->assertEquals($obj::imApply($im, -1), -1);
        $this->assertEquals($obj::imApply($im,  0),  0);
        $this->assertEquals($obj::imApply($im,  1),  1);
    }

    public function test__imApply__01()
    {
        $obj = $this->getTestObj();

        $im = [[0,0]];
        $this->assertEquals($obj::imApply($im, -1), -1);
        $this->assertEquals($obj::imApply($im,  0),  0);
        $this->assertEquals($obj::imApply($im,  1),  1);
    }

    public function test__imApply__02()
    {
        $obj = $this->getTestObj();

        $im = [[0,4]];
        $this->assertEquals($obj::imApply($im, -1), -1);
        $this->assertEquals($obj::imApply($im,  0),  4);
        $this->assertEquals($obj::imApply($im,  1),  5);
    }

    public function test__imApply__03()
    {
        $obj = $this->getTestObj();

        $im = [[0,0], [3,8]];
        $this->assertEquals($obj::imApply($im, -1), -1);
        $this->assertEquals($obj::imApply($im,  0),  0);
        $this->assertEquals($obj::imApply($im,  1),  1);
        $this->assertEquals($obj::imApply($im,  2),  2);
        $this->assertEquals($obj::imApply($im,  3),  8);
        $this->assertEquals($obj::imApply($im,  4),  9);
    }

    public function test__imApply__04()
    {
        $obj = $this->getTestObj();

        $im = [[0,4], [3,8]];
        $this->assertEquals($obj::imApply($im, -1), -1);
        $this->assertEquals($obj::imApply($im,  0),  4);
        $this->assertEquals($obj::imApply($im,  1),  5);
        $this->assertEquals($obj::imApply($im,  2),  6);
        $this->assertEquals($obj::imApply($im,  3),  8);
        $this->assertEquals($obj::imApply($im,  4),  9);
    }
}

// vim: syntax=php sw=4 ts=4 et:
