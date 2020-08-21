<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ConnectionParametersInterfaceTrait
{
    public $host = null;
    public $port = null;
    public $encryption = null;
    public $uri = null;
    public $options = null;

    public function host() : string
    {
        return $this->host;
    }

    public function port() : int
    {
        return $this->port;
    }

    public function encryption() : string
    {
        return $this->encryption;
    }

    public function uri() : string
    {
        return $this->uri;
    }

    public function options() : array
    {
        return $this->options;
    }
}

// vim: syntax=php sw=4 ts=4 et:
