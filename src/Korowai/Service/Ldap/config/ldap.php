<?php
/**
 * @file src/Korowai/Service/Ldap/config/ldap.php
 */

return [
  'databases' => [
    [
      'id' => 1,                                              // identifies database within an application
      // 'factory' => '\Korowai\Lib\Ldap\Adapter\ExtLdap\AdapterFactory',

      'ldap' => [
        'uri' => 'ldap://ldap-service',                       // server's host address
        'options' => [                                        // extra LDAP options
          'protocol_version' => 3
        ]
      ],

      'meta' => [
        'name' => 'Test LDAP Database',                       // short name for UI
        'description' => 'Description for the LDAP Service',  // description for UI
        'base' => 'dc=example,dc=org',                        // default base DN
//        'bind' => [
//          'dn' => 'cn=admin,dc=example,dc=org',               // default bind DN
//          'password' => 'admin'                               // default bind password
//        ],
      ],
    ],
    // You may define more LDAP servers
  ]
];
