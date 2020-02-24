.. index::
   single: Ldap; Adapter
   single: Lib; Ldap; Adapter

.. _LdapAdapters:

Ldap Adapters
=============

:ref:`TheLdapLibrary` uses *adapters* to interact with the actual LDAP
implementation (client library). An adapter is a class that converts the
interface of that particular implementation to
:class:`Korowai\\Lib\\Ldap\\Adapter\\AdapterInterface`. This pattern
allows for different LDAP implementations to be used by :ref:`TheLdapLibrary`
in a pluggable manner. :ref:`TheLdapLibrary` itself provides an adapter named
*ExtLdap* which makes use of the standard `PHP ldap extension`_.

Each :class:`Korowai\\Lib\\Ldap\\Ldap` instance wraps an instance of
:class:`Korowai\\Lib\\Ldap\\Adapter\\AdapterInterface` (the *adapter*)
and interacts with particular LDAP implementation through this *adapter*. The
*adapter* is feed to :class:`Korowai\\Lib\\Ldap\\Ldap`'s constructor when
it's being created. The whole process of adapter instantiation is done behind
the scenes.

.. _LdapAdapterFactory:

Adapter Factory
---------------

An adapter class is accompanied with its adapter factory. This configurable
object creates adapter instances. Adapter factories implement
:class:`Korowai\\Lib\\Ldap\\Adapter\\AdapterFactoryInterface` which
defines two methods:
:method:`Korowai\\Lib\\Ldap\\Adapter\\AdapterFactoryInterface::configure`
and
:method:`Korowai\\Lib\\Ldap\\Adapter\\AdapterFactoryInterface::createAdapter`.
New adapter instances are created with
:method:`Korowai\\Lib\\Ldap\\Adapter\\AdapterFactoryInterface::createAdapter`
according to configuration options provided earlier to
:method:`Korowai\\Lib\\Ldap\\Adapter\\AdapterFactoryInterface::configure`.

Adapter factory may be specified when creating an
:class:`Korowai\\Lib\\Ldap\\Ldap` instance. For this purpose,
a preconfigured instance of the
:class:`Korowai\\Lib\\Ldap\\Adapter\\AdapterFactoryInterface`
shall be provided to :class:`Korowai\\Lib\\Ldap\\Ldap`'s static method
:method:`Korowai\\Lib\\Ldap\\Ldap::createWithAdapterFactory`:

.. literalinclude:: ../../examples/lib/ldap/adapter_factory_1.php
   :linenos:
   :start-after: [code]
   :end-before: [/code]

Alternatively, factory class name may be passed to
:method:`Korowai\\Lib\\Ldap::createWithConfig`
method:

.. literalinclude:: ../../examples/lib/ldap/adapter_factory_2.php
   :linenos:
   :start-after: [code]
   :end-before: [/code]

In this case, a temporary instance of adapter factory is created internally,
configured with ``$config`` and then used to create the actual adapter
instance for :class:`Korowai\\Lib\\Ldap\\Ldap`.


.. _PHP ldap extension: http://php.net/manual/en/book.ldap.php

.. <!--- vim: set syntax=rst spell: -->
