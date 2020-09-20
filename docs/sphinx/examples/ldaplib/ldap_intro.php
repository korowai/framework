<?php
/* [code] */
/* [use] */
use Korowai\Lib\Ldap\LdapFactoryInterface;
/* [/use] */

/* [getLdapFactoryInterface] */
$factory = $container->get(LdapFactoryInterface::class);
/* [/getLdapFactoryInterface] */

/* [createLdapInterface] */
$ldap = $factory->createLdapInterface(['uri' => 'ldap://ldap-service']);
/* [/createLdapInterface] */

/* [bind] */
/* Bind to 'cn=admin,dc=example,dc=org' using password 'admin'. */
$ldap->bind('cn=admin,dc=example,dc=org', 'admin');
/* [/bind] */

/* [search] */
/* The returned result implements ResultInterface. */
$result = $ldap->search('ou=people,dc=example,dc=org', 'objectclass=*');
/* [/search] */

/* [foreach] */
foreach($result as $dn => $entry) {
  print($dn . " => "); print_r($entry->getAttributes());
}
/* [/foreach] */

/* [getEntries] */
$entries = $result->getEntries();
/* [/getEntries] */

/* [entry] */
$entry = $entries['uid=jsmith,ou=people,dc=example,dc=org'];
/* [/entry] */

/* [setAttribute] */
$entry->setAttribute('uidnumber', [1234]);
/* [/setAttribute] */

/* [update] */
$ldap->update($entry);
/* [/update] */
/* [/code] */

// vim: syntax=php sw=4 ts=4 et:
