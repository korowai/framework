<?php
/**
 * @file tests/Korowai/Lib/Ldap/Adapter/SearchQueryInterfaceTrait.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Adapter;

use Korowai\Lib\Ldap\Adapter\ResultInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait SearchQueryInterfaceTrait
{
    public $execute = null;
    public $result = null;

    public function execute() : ResultInterface
    {
        return $this->execute;
    }

    public function getResult() : ResultInterface
    {
        return $this->result;
    }
}

// vim: syntax=php sw=4 ts=4 et:
