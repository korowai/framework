<?php
/* [code] */
/* [use] */
use Korowai\Lib\Ldap\LdapFactoryInterface;
/* [/use] */

/* [container] */
// FIXME: reduce boilerplate related to container creation
use Korowai\Testing\Container\PhpDi\ContainerFactory;
use function Korowai\Ldaplib\config_path;
$container = (new ContainerFactory)->setConfig(config_path('php-di/services.php'))->createContainer();
/* [/container] */

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
foreach($result as $offset => $entry) {
  print($offset . " => "); print_r(['dn' => $entry->getDn(), 'attributes' => $entry->getAttributes()]);
}
/* [/foreach] */

/* [getEntries] */
$entries = $result->getEntries();
/* [/getEntries] */

/* [entry] */
// FIXME: DNs are no longer used as keys, need a nicer API to select entries by dn
//$entry = $entries['uid=jsmith,ou=people,dc=example,dc=org'];
$entry = array_values(array_filter($entries, function ($entry) {
    return $entry->getDn() === 'uid=jsmith,ou=people,dc=example,dc=org';
}));
$entry = $entry[0] ?? null;
/* [/entry] */

/* [setAttribute] */
$entry->setAttribute('uidnumber', [1234]);
/* [/setAttribute] */

/* [update] */
$ldap->update($entry);
/* [/update] */
/* [/code] */

// vim: syntax=php sw=4 ts=4 et:
