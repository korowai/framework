<?php
/* [code] */
use Korowai\Lib\Ldap\Ldap;
use Korowai\Lib\Ldap\Core\LdapLink;
use Korowai\Lib\Ldap\Core\Adapter;

$link = LdapLink::connect('ldap://ldap-service');
$link->set_option(LDAP_OPT_PROTOCOL_VERSION, 3);
$adapter = new Adapter($link);
$ldap = new Ldap($adapter);
// ...
$ldap->bind('cn=admin,dc=example,dc=org', 'admin');
/* [/code] */

// vim: syntax=php sw=4 ts=4 et:
