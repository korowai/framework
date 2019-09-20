<?php
/* [code] */
use Korowai\Component\Ldap\Ldap;
use Korowai\Component\Ldap\Adapter\ExtLdap\AdapterFactory;

$config = ['uri' => 'ldap://ldap-service'];
$factory = new AdapterFactory($config);
$ldap = Ldap::createWithAdapterFactory($factory);
/* [/code] */

// vim: syntax=php sw=4 ts=4 et:
