.. index::
   :single: Ldap; Mocking
   :single: Lib; Ldap; Mocking

.. _LdapMocking:

Mocking Ldap objects
--------------------

Unit-testing applications that use :ref:`TheLdapLibrary` can be troublesome.
The code under test may depend on :ref:`ldap library's <TheLdapLibrary>`
interfaces, such as :class:`Korowai\\Lib\\Ldap\\Adapter\\SearchQueryInterface`.
A common practice for unit-testing is to not depend on real services, so we
can't just use actual implementations, such as
:class:`ExtLdap\\SearchQuery <Korowai\\Lib\\Ldap\\Adapter\\ExtLdap\\SearchQuery>`,
which operate on real LDAP databases.

Mocking is a technique of replacing actual object with fake ones called mocks
or stubs. It's applicable also to our case, but creating mocks for
objects/interfaces of :ref:`TheLdapLibrary` becomes complicated when it comes
to higher-level interfaces such as the
:class:`Korowai\\Lib\\Ldap\\Adapter\\SearchQueryInterface`.

Consider the following function to be unit-tested

.. literalinclude:: ../../examples/lib/ldap/mock_searchquery.php
   :linenos:
   :start-after: [functions]
   :end-before: [/functions]

The expected behavior is that ``getPosixAccounts()`` executes ``$query`` and
extracts *posixAccount* entries from its result. From the code we see
immediately, that our unit-test needs to create a mock for
:class:`Korowai\\Lib\\Ldap\\Adapter\\SearchQueryInterface`. The mock shall
provide ``getResult()`` method returning an instance of
:class:`Korowai\\Lib\\Ldap\\Adapter\\ResultInterface` (another mock?) having
``getResultEntryIterator()`` method that shall return an instance of
:class:`Korowai\\Lib\\Ldap\\Adapter\\ResultEntryIteratorInterface` (yet another
mock?) and so on. Quite complicated as for single unit-test, isn't it?

To facilitate unit-testing and mocking, :ref:`TheLdapLibrary` provides a bunch
of classes for "fake objects" under the :namespace:`Korowai\\Lib\\Ldap\\Adapter\\Mock <Korowai\\Lib\\Ldap\\Adapter\\Mock>`
namespace. For the purpose of our example, an instance of
:class:`Korowai\\Lib\\Ldap\\Adapter\\Mock\\Result` class (from the above
namespace) may be created


.. literalinclude:: ../../examples/lib/ldap/mock_searchquery.php
   :linenos:
   :start-after: [result]
   :end-before: [/result]

and used as a return value of the mocked
:method:`SearchQueryInterface::getResult() <Korowai\\Lib\\Ldap\\Adapter\\SearchQueryInterface::getResult>`
method.

.. literalinclude:: ../../examples/lib/ldap/mock_searchquery.php
   :linenos:
   :start-after: [queryMock]
   :end-before: [/queryMock]

This significantly reduces the boilerplate of mocking the
:class:`Korowai\\Lib\\Ldap\\Adapter\\SearchQueryInterface` (we created one mock
and one fake object instead of a chain of four mocks).
The full example is the following

.. literalinclude:: ../../examples/lib/ldap/mock_searchquery.php
   :linenos:
   :start-after: [code]
   :end-before: [/code]

.. _LdapMockingPredefinedFakeObjects:

Predefined fake objects
^^^^^^^^^^^^^^^^^^^^^^^

The :class:`Korowai\\Lib\\Ldap\\Adapter\\Mock\\Result` object, used in previous
example, is an example of what we'll call "fake objects". A fake object is an
implementation of particular interface, which imitates actual implementation,
except the fake object does not call any actual LDAP implementation
(such as the `PHP ldap extension`_). For example,
:class:`Korowai\\Lib\\Ldap\\Adapter\\Mock\\Result`
implements :class:`Korowai\\Lib\\Ldap\\Adapter\\ResultInterface`
providing two iterators, one over a collection of
:class:`Korowai\\Lib\\Ldap\\Adapter\\Mock\\ResultEntry` objects and the other
over :class:`Korowai\\Lib\\Ldap\\Adapter\\Mock\\ResultReference` objects. Once
configured with arrays of entries and references, the
:class:`Korowai\\Lib\\Ldap\\Adapter\\Mock\\Result`, behaves
exactly as real implementation of
:class:`Korowai\\Lib\\Ldap\\Adapter\\ResultInterface` would.

Below is a list of interfaces and their fake-object implementations.

.. list-table:: Fake Objects
   :widths: 50 50
   :header-rows: 1

   * - Interface
     - Fake Object
   * - :class:`Korowai\\Lib\\Ldap\\Adapter\\ResultInterface`
     - :class:`Korowai\\Lib\\Ldap\\Adapter\\Mock\\Result`
   * - :class:`Korowai\\Lib\\Ldap\\Adapter\\ResultEntryInterface`
     - :class:`Korowai\\Lib\\Ldap\\Adapter\\Mock\\ResultEntry`
   * - :class:`Korowai\\Lib\\Ldap\\Adapter\\ResultReferenceInterface`
     - :class:`Korowai\\Lib\\Ldap\\Adapter\\Mock\\ResultReference`
   * - :class:`Korowai\\Lib\\Ldap\\Adapter\\ResultEntryIteratorInterface`
     - :class:`Korowai\\Lib\\Ldap\\Adapter\\Mock\\ResultEntryIterator`
   * - :class:`Korowai\\Lib\\Ldap\\Adapter\\ResultReferenceIteratorInterface`
     - :class:`Korowai\\Lib\\Ldap\\Adapter\\Mock\\ResultReferenceIterator`
   * - :class:`Korowai\\Lib\\Ldap\\Adapter\\ResultAttributeIteratorInterface`
     - :class:`Korowai\\Lib\\Ldap\\Adapter\\Mock\\ResultAttributeIterator`
   * - :class:`Korowai\\Lib\\Ldap\\Adapter\\ResultReferralIteratorInterface`
     - :class:`Korowai\\Lib\\Ldap\\Adapter\\Mock\\ResultReferralIterator`

.. _PHP ldap extension: http://php.net/manual/en/book.ldap.php

.. <!--- vim: set syntax=rst spell: -->
