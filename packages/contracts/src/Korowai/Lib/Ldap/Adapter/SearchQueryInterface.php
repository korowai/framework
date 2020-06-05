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
interface SearchQueryInterface
{
    /**
     * Executes query and returns result.
     *
     * @return ResultInterface
     */
    public function execute() : ResultInterface;

    /**
     * Returns the result of last execution of the query, calls execute() if
     * necessary.
     *
     * @return ResultInterface
     */
    public function getResult() : ResultInterface;
}

// vim: syntax=php sw=4 ts=4 et:
