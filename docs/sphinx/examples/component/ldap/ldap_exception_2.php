<?php
/* [code] */
/* [use] */
use Korowai\Component\Ldap\Ldap;
use Korowai\Component\Ldap\Exception\LdapException;
/* [/use] */

$ldap = Ldap::createWithConfig(['uri' => 'ldap://ldap-service']);
$ldap->bind('cn=admin,dc=example,dc=org', 'admin');

/* [try-catch] */
try {
    $ldap->query('dc=inexistent,dc=org', 'cn=admin');
} catch (LdapException $e) {
    if ($e->getCode() == 0x20) { /* No such object */
        fprintf(STDERR, "Warning(0x%x): %s\n", $e->getCode(), $e->getMessage());
        exit(2);
    } else {
        fprintf(STDERR, "LdapException(0x%x): %s\n", $e->getCode(), $e->getMessage());
        exit(1);
    }
}
/* [/try-catch] */
/* [/code] */

// vim: syntax=php sw=4 ts=4 et: