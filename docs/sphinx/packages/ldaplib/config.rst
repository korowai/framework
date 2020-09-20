.. index::
   single: Ldap; Configuration
   single: Lib; Ldap; Configuration


.. _ldaplib.configuration:

Ldap Configuration
==================

:class:`Korowai\\Lib\\Ldap\\Ldap` instances are configured according to
settings provided with ``$config`` array (an argument to
:method:`Ldap::createWithConfig() <Korowai\\Lib\\Ldap\\Ldap::createWithConfig>`).
The ``$config`` array is internally passed to an
:ref:`adapter factory <ldaplib.adapterfactory>` to create the
supporting adapter object. Some settings are "standard" and shall be accepted
by any adapter type. Other options may be specific to a particular adapter type
(such as the default :class:`ExtLdap\\Adapter <Korowai\\Lib\\Ldap\\Core\\Adapter>`,
where the adapter-specific options are stored in ``$config['options']``).

Common LDAP settings
--------------------

The following table lists configuration settings supported by any adapter.

.. list-table:: Configuration settings
   :header-rows: 1
   :widths: 1 1 3

   * - Option
     - Default
     - Description

   * - ``host``
     - ``'localhost'``
     - Host to connect to (server IP or hostname).

   * - ``uri``
     - ``null``
     - URI for LDAP connection. This may be specified alternativelly to
       ``host``, ``port`` and ``encryption``.

   * - ``encryption``
     - ``'none'``
     - Encription. Possible values: ``'none'``, ``'ssl'``.

   * - ``port``
     - ``389`` or ``636``
     - Server port to conect to. If ``'encryption'`` is ``'ssl'``, then ``636``
       is used. Otherwise default ``'port'`` is ``389``.

   * - ``options``
     - ``array()``
     - An array of additional connection options (adapter-specific).


LDAP options specific to ExtLdap adapter
----------------------------------------

The ``$config['options']`` specific to
:class:`ExtLdap\\Adapter <Korowai\\Lib\\Ldap\\Core\\Adapter>`
are listed below. For more details see PHP function
:phpfunction:`ldap_get_option() <ldap_get_option>` and OpenLDAP function
`ldap_get_option(3)`_.

.. list-table:: Configuration options for :class:`ExtLdap\\Adapter <Korowai\\Lib\\Ldap\\Core\\Adapter>`
   :header-rows: 1
   :widths: 2 1 5

   * - Option
     - Type
     - Description

   * - ``deref``
     - ``string|int``
     - Specifies how alias dereferencing is done when performing a search. The
       option's value can be specified as one of the following constants:

         - ``'never'`` (``LDAP_DEREF_NEVER``): aliases are never dereferenced,
         - ``searching`` (``LDAP_DEREF_SEARCHING``): aliases are dereferenced
           in subordinates of the base object, but not in locating the base
           object of the search,
         - ``'finding'`` (``LDAP_DEREF_FINDING``): aliases are only
           dereferenced when locating the base object of the search,
         - ``'always'`` (``LDAP_DEREF_ALWAYS``): aliases are dereferenced both
           in searching and in locating the base object of the search.

   * - ``sizelimit``
     - ``int``
     - Specifies a size limit (number of entries) to use when performing
       searches. The number should be a non-negative integer. ``sizelimit`` of
       zero (0) specifies a request for unlimited search size. Please note that
       the server may still apply any server-side limit on the amount of
       entries that can be returned by a search operation.

   * - ``timelimit``
     - ``int``
     - Specifies a time limit (in seconds) to use when performing searches.
       The number should be a non-negative integer. ``timelimit`` of zero (0)
       specifies unlimited search time to be used. Please note that the server
       may still apply any server-side limit on the duration of a search
       operation.

   * - ``network_timeout``
     - ``int``
     - Specifies the timeout (in seconds) after which the poll(2)/select(2)
       following a connect(2) returns in case of no activity.

   * - ``protocol_version``
     - ``int``
     - Specifies what version of the LDAP protocol should be used. Allowed
       values are ``2`` and ``3``. Default is: ``3``.

   * - ``error_number``
     - ``int``
     - Sets/gets the LDAP result code associated to the handle.

   * - ``referrals``
     - ``bool``
     - Determines whether the library should implicitly chase referrals or not.

   * - ``restart``
     - ``bool``
     - Determines whether the library should implicitly restart connections.

   * - ``host_name``
     - ``string``
     - Sets/gets a space-separated list of hosts to be contacted by the library
       when trying to establish a connection. This is now deprecated in favor
       of ``uri``.

   * - ``error_string``
     - ``string``
     - Sets/gets a string containing the error string associated to the LDAP
       handle. This option is now known as ``diagnostic_message``
       (``LDAP_OPT_DIAGNOSTIC_MESSAGE``).

   * - ``diagnostic_message``
     - ``string``
     - Sets/gets a string containing the error string associated to the LDAP
       handle. This option was formerly known as ``error_string``
       (``LDAP_OPT_ERROR_STRING``).

   * - ``matched_dn``
     - ``string``
     - Sets/gets a string containing the matched DN associated to the LDAP
       handle.

   * - ``server_controls``
     - ``array``
     - Sets/gets the server-side controls to be used for all operations. This
       is now deprecated as modern LDAP C API provides replacements for all
       main operations which accepts server-side controls as explicit
       arguments; see for example `ldap_search_ext(3)`_, `ldap_add_ext(3)`_,
       `ldap_modify_ext(3)`_ and so on.

   * - ``client_controls``
     - ``array``
     - Sets/gets the client-side controls to be used for all operations. This
       is now deprecated as modern LDAP C API provides replacements for all
       main operations which accepts client-side controls as explicit
       arguments; see for example `ldap_search_ext(3)`_, `ldap_add_ext(3)`_,
       `ldap_modify_ext(3)`_ and so on.

   * - ``keepalive_idle``
     - ``int``
     - Sets/gets the number of seconds a connection needs to remain idle before
       TCP starts sending keepalive probes.

   * - ``keepalive_probes``
     - ``int``
     - Sets/gets the maximum number of keepalive probes TCP should send before
       dropping the connection.

   * - ``keepalive_interval``
     - ``int``
     - Sets/gets the interval in seconds between individual keepalive probes.

   * - ``sasl_mech``
     - ``string``
     - Gets the SASL mechanism.

   * - ``sasl_realm``
     - ``string``
     - Gets the SASL realm.

   * - ``sasl_authcid``
     - ``string``
     - Gets the SASL authentication identity.

   * - ``sasl_authzid``
     - ``string``
     - Gets the SASL authorization identity.

   * - ``tls_cacertdir``
     - ``string``
     - Sets/gets the path of the directory containing CA certificates.

   * - ``tls_cacertfile``
     - ``string``
     - Sets/gets the full-path of the CA certificate file.

   * - ``tls_certfile``
     - ``string``
     - Sets/gets the full-path of the certificate file.

   * - ``tls_cipher_suite``
     - ``string``
     - Sets/gets the allowed cipher suite.

   * - ``tls_crlcheck``
     - ``string|int``
     - Sets/gets the CRL evaluation strategy, one of

         - ``'none'`` (``LDAP_OPT_X_TLS_CRL_NONE``),
         - ``'peer'`` (``LDAP_OPT_X_TLS_CRL_PEER``),
         - ``'all'`` (``LDAP_OPT_X_TLS_CRL_ALL``).

   * - ``tls_crlfile``
     - ``string``
     - Sets/gets the full-path of the CRL file.

   * - ``tls_dhfile``
     - ``string``
     - Gets/sets the full-path of the file containing the parameters for
       Diffie-Hellman ephemeral key exchange.

   * - ``tls_keyfile``
     - ``string``
     - Sets/gets the full-path of the certificate key file.

   * - ``tls_protocol_min``
     - ``int``
     - Sets/gets the minimum protocol version.

   * - ``tls_random_file``
     - ``string``
     - Sets/gets the random file when ``/dev/random`` and ``/dev/urandom`` are
       not available.

   * - ``tls_require_cert``
     - ``string|int``
     - Sets/gets the peer certificate checking strategy, one of

         - ``'never'`` (``LDAP_OPT_X_TLS_NEVER``),
         - ``'hard'`` (``LDAP_OPT_X_TLS_HARD``),
         - ``'demand'`` (``LDAP_OPT_X_TLS_DEMAND``),
         - ``'allow'`` (``LDAP_OPT_X_TLS_ALLOW``),
         - ``'try'`` (``LDAP_OPT_X_TLS_TRY``).

.. _ldap_get_option(3): http://www.openldap.org/software/man.cgi?query=ldap_set_option&sektion=3&apropos=0&manpath=OpenLDAP+2.4-Release
.. _ldap_search_ext(3): http://www.openldap.org/software/man.cgi?query=ldap_search_ext&apropos=0&sektion=3&manpath=OpenLDAP+2.4-Release&format=html
.. _ldap_add_ext(3): http://www.openldap.org/software/man.cgi?query=ldap_add_ext&apropos=0&sektion=3&manpath=OpenLDAP+2.4-Release&format=html
.. _ldap_modify_ext(3): http://www.openldap.org/software/man.cgi?query=ldap_modify_ext&apropos=0&sektion=3&manpath=OpenLDAP+2.4-Release&format=html


.. <!--- vim: set syntax=rst spell: -->
