<?php
/* [use] */
use Korowai\Component\Ldap\Ldap;
use Korowai\Component\Ldap\Exception\LdapException;

$ldap = Ldap::createWithConfig(['uri' => 'ldap://ldap-service']);
$ldap->bind('cn=admin,dc=example,dc=org', 'admin');

/* [tryQueryInexistent] */
try {
    $ldap->query('dc=inexistent,dc=org', 'cn=admin');
} catch (LdapException $e) {
    fprintf(STDERR, "LdapException(0x%x): %s\n", $e->getCode(), $e->getMessage());
    exit(1);
}

// vim: syntax=php sw=4 ts=4 et:
