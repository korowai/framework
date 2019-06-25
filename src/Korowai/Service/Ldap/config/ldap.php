<?php

return [
  'databases' => [
    [
      'id'          => 1,                                   // identifies database within an application
      'name'        => 'Test LDAP Database',                // short name for UI
      'description' => 'Description for the LDAP Service',  // description for UI
      'base'        => 'dc=example,dc=org',                 // default base DN
      'bind'        => [
        'dn'        => 'cn=admin,dc=example,dc=org',        // default bind DN
        'password'  => 'admin'                              // default bind password
      ],

      // 'factory' => '\Korowai\Component\Ldap\Adapter\ExtLdap\AdapterFactory'

      'server'      => [
        'uri'       => 'ldap://ldap-service',               // server's host address
        'options'   => [
          'protocol_version' => 3
        ]
      ]
    ],
    // You may define more LDAP servers
  ]
];
