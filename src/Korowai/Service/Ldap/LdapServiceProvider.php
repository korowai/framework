<?php
/**
 * @file src/Korowai/Service/Ldap/LdapServiceProvider.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\LdapService
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Service\Ldap;

use Korowai\Lib\Ldap\Ldap;
use Korowai\Service\Ldap\LdapService;

use Illuminate\Support\ServiceProvider;

class LdapServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function boot()
    {
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/ldap.php', 'ldap');
        $this->app->singleton('korowai.ldap', function ($app) {
            return new LdapService($this->app['config']->get('ldap'));
        });
    }
}

// vim: syntax=php sw=4 ts=4 et:
