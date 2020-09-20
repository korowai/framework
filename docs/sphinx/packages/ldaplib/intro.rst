Installation
============

.. code-block:: shell

   php composer.phar require "korowai/ldaplib:dev-master"

Basic Usage
===========

The :ref:`Ldap Library <ldaplib>` implements
:class:`Korowai\\Lib\\Ldap\\LdapFactoryInterface`.

.. literalinclude:: ../../examples/ldaplib/ldap_intro.php
   :linenos:
   :start-after: [use]
   :end-before: [/use]

which may be used to create new connections to LDAP servers.
An instance of :class:`Korowai\\Lib\\Ldap\\LdapFactoryInterface` may be
obtained via dependency injection or from a preconfigured PSR-11 container

.. literalinclude:: ../../examples/ldaplib/ldap_intro.php
   :linenos:
   :start-after: [getLdapFactoryInterface]
   :end-before: [/getLdapFactoryInterface]

then the method :method:`createLdapInterface() <Korowai\\Lib\\Ldap\\LdapFactoryInterface::createLdapInterface>`
may be used to create new instance of
:class:`Korowai\\Lib\\Ldap\\LdapInterface` which represents single connection
to an LDAP server

.. literalinclude:: ../../examples/ldaplib/ldap_intro.php
   :linenos:
   :start-after: [createLdapInterface]
   :end-before: [/createLdapInterface]

To establish connection and authenticate, the
:method:`Korowai\\Lib\\Ldap\\Ldap::bind` method can be used.

.. literalinclude:: ../../examples/ldaplib/ldap_intro.php
   :linenos:
   :start-after: [bind]
   :end-before: [/bind]

Once bound, we can search in the database with
:method:`Korowai\\Lib\\Ldap\\AbstractLdap::search` method.

.. literalinclude:: ../../examples/ldaplib/ldap_intro.php
   :linenos:
   :start-after: [search]
   :end-before: [/search]

The ``$result`` object returned by :method:`Korowai\\Lib\\Ldap\\AbstractLdap::search`
provides access to entries selected by the query. It (``$result``) implements
the :class:`Korowai\\Lib\\Ldap\\Adapter\\ResultInterface` which,
in turn, includes the standard :phpclass:`\IteratorAggregate` interface. This
means, you may iterate over the result entries in a usual way

.. literalinclude:: ../../examples/ldaplib/ldap_intro.php
   :linenos:
   :start-after: [foreach]
   :end-before: [/foreach]

Alternatively, an array of entries can be retrieved with a single call to
:method:`Korowai\\Lib\\Ldap\\Adapter\\ResultInterface::getEntries` method

.. literalinclude:: ../../examples/ldaplib/ldap_intro.php
   :linenos:
   :start-after: [getEntries]
   :end-before: [/getEntries]

By default, entries' distinguished names (DN) are used as array keys
in the returned ``$entries``

.. literalinclude:: ../../examples/ldaplib/ldap_intro.php
   :linenos:
   :start-after: [entry]
   :end-before: [/entry]

Each entry is an :class:`Korowai\\Lib\\Ldap\\Entry` object and
contains attributes. It can me modified in memory

.. literalinclude:: ../../examples/ldaplib/ldap_intro.php
   :linenos:
   :start-after: [setAttribute]
   :end-before: [/setAttribute]

.. note:: The Ldap library uses lower-cased keys to access entry attributes.
          Attributes in :class:`Korowai\\Lib\\Ldap\\Entry` are always
          array-valued.

Once modified, the entry may be written back to the LDAP database with
:method:`Korowai\\Lib\\Ldap\\Ldap::update` method.

.. literalinclude:: ../../examples/ldaplib/ldap_intro.php
   :linenos:
   :start-after: [update]
   :end-before: [/update]

.. <!--- vim: set syntax=rst spell: -->
