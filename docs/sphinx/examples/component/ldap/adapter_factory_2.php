<?php
/* [code] */
use Korowai\Component\Ldap\Ldap;
use Korowai\Component\Ldap\Adapter\ExtLdap\AdapterFactory;

$config = ['uri' => 'ldap://ldap-service'];
$ldap = Ldap::createWithConfig($config, AdapterFactory::class);
/* [/code] */

// vim: syntax=php sw=4 ts=4 et:
