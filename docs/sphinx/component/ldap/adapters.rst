.. index::
   :single: Ldap; Adapter
   :single: Components; Ldap; Adapter

.. _LdapAdapters:

Ldap Adapters
-------------

:ref:`TheLdapComponent` uses *adapters* to interact with the actual LDAP
client library. An adapter is a class that converts the interface of that
particular implementation to
:class:`Korowai\\Component\\Ldap\\Adapter\\AdapterInterface`. This pattern
allows for different LDAP implementations to be used by :ref:`TheLdapComponent`
in a pluggable manner.

Each :class:`Korowai\\Component\\Ldap\\Ldap` instance wraps an instance of
:class:`Korowai\\Component\\Ldap\\Adapter\\AdapterInterface` (the *adapter*)
and interacts with particular LDAP implementation through this *adapter*. The
*adapter* is feed to :class:`Korowai\\Component\\Ldap\\Ldap`'s constructor when
it's being created. The whole process of adapter instantiation is done behind
the scenes.

.. _LdapAdapterFactory:

Adapter Factory
^^^^^^^^^^^^^^^

An adapter class is accompanied with its adapter factory. This configurable
object creates adapter instances. Adapter factories implement
:class:`Korowai\\Component\\Ldap\\Adapter\\AdapterFactoryInterface` which
defines two methods:
:method:`Korowai\\Component\\Ldap\\Adapter\\AdapterFactoryInterface::configure`
and
:method:`Korowai\\Component\\Ldap\\Adapter\\AdapterFactoryInterface::createAdapter`.
New adapter instances are created with
:method:`Korowai\\Component\\Ldap\\Adapter\\AdapterFactoryInterface::createAdapter`
according to configuration options provided earlier to
:method:`Korowai\\Component\\Ldap\\Adapter\\AdapterFactoryInterface::configure`.

Adapter factory may be specified when creating an
:class:`Korowai\\Component\\Ldap\\Ldap` instance. For this purpose,
a preconfigured instance of the
:class:`Korowai\\Component\\Ldap\\Adapter\\AdapterFactoryInterface`
shall be provided to :class:`Korowai\\Component\\Ldap\\Ldap`'s static method
:method:`Korowai\\Component\\Ldap\\Ldap::createWithAdapterFactory`:

.. literalinclude:: ../../examples/component/ldap/adapter_factory_1.php
   :linenos:
   :start-after: [code]
   :end-before: [/code]

Alternatively, factory class name may be passed to
:method:`Korowai\\Component\\Ldap::createWithConfig`
method:

.. literalinclude:: ../../examples/component/ldap/adapter_factory_2.php
   :linenos:
   :start-after: [code]
   :end-before: [/code]

In this case, a temporary instance of adapter factory is created internally,
configured with ``$config`` and then used to create the actual adapter
instance for :class:`Korowai\\Component\\Ldap\\Ldap`.


.. _PHP ldap extension: http://php.net/manual/en/book.ldap.php

.. <!--- vim: set syntax=rst spell: -->
