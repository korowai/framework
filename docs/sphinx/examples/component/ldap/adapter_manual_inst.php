<?php
/* [use] */
use Korowai\Component\Ldap\Ldap;
use Korowai\Component\Ldap\Adapter\ExtLdap\LdapLink;
use Korowai\Component\Ldap\Adapter\ExtLdap\Adapter;

$link = LdapLink::connect('ldap://ldap-service');
$link->set_option(LDAP_OPT_PROTOCOL_VERSION, 3);
$adapter = new Adapter($link);
$ldap = new Ldap($adapter);
// ...
$ldap->bind('cn=admin,dc=example,dc=org', 'admin');

// vim: syntax=php sw=4 ts=4 et:
