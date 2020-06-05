<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface CompareQueryInterface
{
    /**
     * Executes query and returns result.
     *
     * @return bool
     */
    public function execute() : bool;

    /**
     * Returns the result of last execution of the query, calls execute() if
     * necessary.
     *
     * @return bool
     */
    public function getResult() : bool;
}

// vim: syntax=php sw=4 ts=4 et:
