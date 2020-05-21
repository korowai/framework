<?php
/**
 * @file tests/Traits/LdifAttrValRecordNestedRulesTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\Traits\LdifAttrValRecordNestedRules;
use Korowai\Lib\Ldif\Rules\ControlRule;
use Korowai\Lib\Ldif\Rules\ModSpecRule;
use Korowai\Lib\Ldif\Rules\ChangeRecordInitRule;

use Korowai\Testing\Lib\Ldif\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LdifAttrValRecordNestedRulesTest extends TestCase
{
    public function test__getNestedRulesSpecs()
    {
        $object = $this->getMockForTrait(LdifAttrValRecordNestedRules::class);

        $this->assertSame([
            ],
            get_class($object)::getNestedRulesSpecs()
        );
    }
}

// vim: syntax=php sw=4 ts=4 et:
