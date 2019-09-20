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

:ref:`Ldap Component <TheLdapComponent>` provides
:class:`Korowai\\Component\\Ldap\\Ldap` class to authenticate and
query against an LDAP server. 

.. literalinclude:: ../examples/component/ldap/ldap_intro.php
   :linenos:
   :start-after: [use]
   :end-before: [/use]

An instance of :class:`Korowai\\Component\\Ldap\\Ldap` may be easily created with
:method:`Ldap::createWithConfig() <Korowai\\Component\\Ldap\\Ldap::createWithConfig>`.

.. literalinclude:: ../examples/component/ldap/ldap_intro.php
   :linenos:
   :start-after: [createWithConfig]
   :end-before: [/createWithConfig]

To establish connection and authenticate, the
:method:`Korowai\\Component\\Ldap\\Ldap::bind` method can be used.

.. literalinclude:: ../examples/component/ldap/ldap_intro.php
   :linenos:
   :start-after: [bind]
   :end-before: [/bind]

Once bound, we can search in the database with
:method:`Korowai\\Component\\Ldap\\AbstractLdap::query` method.

.. literalinclude:: ../examples/component/ldap/ldap_intro.php
   :linenos:
   :start-after: [query]
   :end-before: [/query]

The ``$result`` object returned by :method:`Korowai\\Component\\Ldap\\AbstractLdap::query`
provides access to entries selected by the query. It (``$result``) implements
the :class:`Korowai\\Component\\Ldap\\Adapter\\ResultInterface` which,
in turn, includes the standard :phpclass:`\IteratorAggregate` interface. This
means, you may iterate over the result entries in a usual way

.. literalinclude:: ../examples/component/ldap/ldap_intro.php
   :linenos:
   :start-after: [foreach]
   :end-before: [/foreach]

Alternatively, an array of entries can be retrieved with a single call to
:method:`Korowai\\Component\\Ldap\\Adapter\\ResultInterface::getEntries` method

.. literalinclude:: ../examples/component/ldap/ldap_intro.php
   :linenos:
   :start-after: [getEntries]
   :end-before: [/getEntries]

By default, entries' distinguished names (DN) are used as array keys
in the returned ``$entries``

.. literalinclude:: ../examples/component/ldap/ldap_intro.php
   :linenos:
   :start-after: [entry]
   :end-before: [/entry]

Each entry is an :class:`Korowai\\Component\\Ldap\\Entry` object and
contains attributes. It can me modified in memory

.. literalinclude:: ../examples/component/ldap/ldap_intro.php
   :linenos:
   :start-after: [setAttribute]
   :end-before: [/setAttribute]

.. note:: The Ldap component uses lower-cased keys to access entry attributes.
          Attributes in :class:`Korowai\\Component\\Ldap\\Entry` are always
          array-valued. This simplifies handling the multi-valued LDAP
          attribues.

Once modified, the entry may be written back to the LDAP database with
:method:`Korowai\\Component\\Ldap\\Ldap::update` method.

.. literalinclude:: ../examples/component/ldap/ldap_intro.php
   :linenos:
   :start-after: [update]
   :end-before: [/update]


.. toctree::
   :maxdepth: 1
   :hidden:
   :glob:

   ldap/config
   ldap/exceptions
   ldap/adapters

.. <!--- vim: set syntax=rst spell: -->
