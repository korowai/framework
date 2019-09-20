.. index::
   single: Ldap
   single: Components; Ldap

.. _TheLdapComponent:

The Ldap Component
------------------

The LDAP component provides means to connect to and use an LDAP server.

Installation
^^^^^^^^^^^^

.. code-block:: shell

   php composer.phar require "korowai/ldap:dev-master"

Basic Usage
^^^^^^^^^^^

:ref:`TheLdapComponent` provides :class:`Korowai\\Component\\Ldap\\Ldap` class
with methods to authenticate and query against an LDAP server. An instance of
:class:`Korowai\\Component\\Ldap\\Ldap` may be easily created with
:method:`Ldap::createWithConfig() <Korowai\\Component\\Ldap\\Ldap::createWithConfig>`.

.. literalinclude:: ../examples/component/ldap/ldap_intro.php
   :start-after: [use]
   :lines: 1-3

The next step is to get authenticated against some DN in the database. This is
called binding and can be performed with
:method:`Korowai\\Component\\Ldap\\Ldap::bind` method.

.. literalinclude:: ../examples/component/ldap/ldap_intro.php
   :start-after: [bind]
   :lines: 1-2

Once bound, we can search in the database with
:method:`Korowai\\Component\\Ldap\\AbstractLdap::query` method.

.. literalinclude:: ../examples/component/ldap/ldap_intro.php
   :start-after: [query]
   :lines: 1-2

The returned object provides access to entries selected by the query. It
implements :class:`Korowai\\Component\\Ldap\\Adapter\\ResultInterface` which,
in turn, includes the standard :phpclass:`\IteratorAggregate` interface. This
means, you may iterate over the result entries in a usual way

.. literalinclude:: ../examples/component/ldap/ldap_intro.php
   :start-after: [foreach]
   :lines: 1-3

An array of entries can be retrieved with result's
:method:`Korowai\\Component\\Ldap\\Adapter\\ResultInterface::getEntries` method

.. literalinclude:: ../examples/component/ldap/ldap_intro.php
   :start-after: [getEntries]
   :lines: 1

By default, entries' distinguished names (DN) are used as array keys

.. literalinclude:: ../examples/component/ldap/ldap_intro.php
   :start-after: [entry]
   :lines: 1

Each entry is an :class:`Korowai\\Component\\Ldap\\Entry` object and
contains attributes. It can me modified in memory

.. literalinclude:: ../examples/component/ldap/ldap_intro.php
   :start-after: [setAttribute]
   :lines: 1

.. note:: The Ldap component uses lower-cased keys to access entry attributes.
          Attributes in :class:`Korowai\\Component\\Ldap\\Entry` are always
          array-valued. This simplifies handling the multi-valued LDAP
          attribues.

Once modified, the entry may be written back to the LDAP database with
:method:`Korowai\\Component\\Ldap\\Ldap::update` method.

.. literalinclude:: ../examples/component/ldap/ldap_intro.php
   :start-after: [update]
   :lines: 1


.. toctree::
   :maxdepth: 1
   :hidden:
   :glob:

   ldap/config
   ldap/exceptions
   ldap/adapters

.. <!--- vim: set syntax=rst spell: -->
