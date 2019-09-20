<?php
/* [use] */
use Korowai\Component\Ldap\Ldap;
use Korowai\Component\Ldap\Exception\AttributeException;

$ldap = Ldap::createWithConfig(['uri' => 'ldap://ldap-service']);
$ldap->bind('cn=admin,dc=example,dc=org', 'admin');

foreach($ldap->query('dc=example,dc=org', 'cn=admin') as $entry) {
    try {
        /* [getAttributeInexistent] */
        $entry->getAttribute('inexistent');
    } catch (AttributeException $e) {
        fprintf(STDERR, "AttributeException: %s\n", $e->getMessage());
        exit(1);
    }
}

// vim: syntax=php sw=4 ts=4 et:
