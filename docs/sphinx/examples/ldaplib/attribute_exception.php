<?php
/* [code] */
/* [use] */
use Korowai\Lib\Ldap\Ldap;
use Korowai\Lib\Ldap\AttributeException;
/* [/use] */

$ldap = Ldap::createWithConfig(['uri' => 'ldap://ldap-service']);
$ldap->bind('cn=admin,dc=example,dc=org', 'admin');

foreach($ldap->search('dc=example,dc=org', 'cn=admin') as $entry) {
    try {
        /* [getAttributeInexistent] */
        $entry->getAttribute('inexistent');
    } catch (AttributeException $e) {
        fprintf(STDERR, "AttributeException: %s\n", $e->getMessage());
        exit(1);
    }
}
/* [/code] */

// vim: syntax=php sw=4 ts=4 et:
