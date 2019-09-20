<?php
/* [use] */
use Korowai\Component\Ldap\Ldap;

$ldap = Ldap::createWithConfig(['uri' => 'ldap://ldap-service']);

/* [bind] */
/* Bind to 'cn=admin,dc=example,dc=org' using password 'admin'. */
$ldap->bind('cn=admin,dc=example,dc=org', 'admin');

/* [query] */
/* The returned result implements ResultInterface. */
$result = $ldap->query('ou=people,dc=example,dc=org', 'objectclass=*');

/* [foreach] */
foreach($result as $dn => $entry) {
  print($dn . " => "); print_r($entry->getAttributes());
}

/* [getEntries] */
$entries = $result->getEntries();

/* [entry] */
$entry = $entries['uid=jsmith,ou=people,dc=example,dc=org'];

/* [setAttribute] */
$entry->setAttribute('uidnumber', [1234]);

/* [update] */
$ldap->update($entry);

// vim: syntax=php sw=4 ts=4 et:
