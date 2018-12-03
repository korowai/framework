.. index:: pair: class; Korowai::Component::Ldap::Ldap
.. _doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap:
.. _cid-korowai::component::ldap::ldap:

class Korowai::Component::Ldap::Ldap
====================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

A facade to ldap component. :ref:`More...<details-doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap>`

.. _doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap_1a88255761dac6d915507e7d96abe1a446:
.. _cid-korowai::component::ldap::ldap::createwithadapterfactory:
.. ref-code-block:: cpp
	:class: overview-code-block

	class Ldap: public :ref:`Korowai::Component::Ldap::AbstractLdap<doxid-d1/da7/class_korowai_1_1_component_1_1_ldap_1_1_abstract_ldap>`

	// methods

	static static :ref:`createWithConfig<doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap_1a7f4080e2d085bf5927dacbc5c34f3f24>` (
	    array $config = array (),
	    string $factoryClass = null
	    )

	static static createWithAdapterFactory (:ref:`AdapterFactoryInterface<doxid-d4/d6c/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_factory_interface>` $factory)
	:ref:`__construct<doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap_1aa68bd28db3348387b2d4dab662f20491>` (:ref:`AdapterInterface<doxid-d8/dca/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_interface>` $adapter)
	:ref:`getAdapter<doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap_1a23a4c71383d4d2dfc4ccade9fc78e5b9>` ()
	:ref:`getBinding<doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap_1ac89b72f3b844eb3316dfcab18ae1c0ba>` ()
	:ref:`getEntryManager<doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap_1a8b66a24d3f88cc755663c4e25802d924>` ()

	:ref:`bind<doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap_1aa30d268841f3bf5d79b89c2bd2749959>` (
	    string $dn = null,
	    string $password = null
	    )

	:ref:`unbind<doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap_1a346fa8b27edda87ed42edbd0323e2b63>` ()
	:ref:`isBound<doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap_1aa146f780c6982d8fb070a9bd579dacdc>` ()
	:ref:`add<doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap_1afc4ba29bd636263e23c5e19f1bb89b37>` (:ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry)
	:ref:`update<doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap_1a9642cba773a197ae73af05f63476f34f>` (:ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry)

	:ref:`rename<doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap_1a70c998a892022ffe5a5988bfcea1060e>` (
	    :ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry,
	    string $newRdn,
	    bool $deleteOldRdn = true
	    )

	:ref:`delete<doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap_1a8fef021c7f47350290dbe7c5dcef783d>` (:ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry)

	:ref:`createQuery<doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap_1ad8b740f1e3428926aeb01d73c286e1d9>` (
	    string $base_dn,
	    string $filter,
	    array $options = array ()
	    )

Inherited Members
-----------------

.. ref-code-block:: cpp
	:class: overview-inherited-code-block

	// methods

	:ref:`isBound<doxid-dc/d90/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_binding_interface_1aa146f780c6982d8fb070a9bd579dacdc>` ()

	:ref:`bind<doxid-dc/d90/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_binding_interface_1aa30d268841f3bf5d79b89c2bd2749959>` (
	    string $dn = null,
	    string $password = null
	    )

	:ref:`unbind<doxid-dc/d90/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_binding_interface_1a346fa8b27edda87ed42edbd0323e2b63>` ()
	:ref:`add<doxid-da/ddf/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_entry_manager_interface_1afc4ba29bd636263e23c5e19f1bb89b37>` (:ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry)
	:ref:`update<doxid-da/ddf/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_entry_manager_interface_1a9642cba773a197ae73af05f63476f34f>` (:ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry)

	:ref:`rename<doxid-da/ddf/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_entry_manager_interface_1a70c998a892022ffe5a5988bfcea1060e>` (
	    :ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry,
	    string $newRdn,
	    bool $deleteOldRdn = true
	    )

	:ref:`delete<doxid-da/ddf/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_entry_manager_interface_1a8fef021c7f47350290dbe7c5dcef783d>` (:ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry)
	:ref:`getBinding<doxid-d8/dca/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_interface_1ac89b72f3b844eb3316dfcab18ae1c0ba>` ()
	:ref:`getEntryManager<doxid-d8/dca/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_interface_1a8b66a24d3f88cc755663c4e25802d924>` ()

	:ref:`createQuery<doxid-d8/dca/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_interface_1ad8b740f1e3428926aeb01d73c286e1d9>` (
	    string $base_dn,
	    string $filter,
	    array $options = array ()
	    )

	:ref:`getAdapter<doxid-df/d00/interface_korowai_1_1_component_1_1_ldap_1_1_ldap_interface_1a23a4c71383d4d2dfc4ccade9fc78e5b9>` ()

	:ref:`query<doxid-d1/da7/class_korowai_1_1_component_1_1_ldap_1_1_abstract_ldap_1a3354008655e90a468b704b72c8ec33b9>` (
	    string $base_dn,
	    string $filter,
	    array $options = array ()
	    )

.. _details-doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

A facade to ldap component. Creates connection, binds, reads from and writes to ldap.

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap_1a7f4080e2d085bf5927dacbc5c34f3f24:
.. _cid-korowai::component::ldap::ldap::createwithconfig:
.. ref-code-block:: cpp
	:class: title-code-block

	static static createWithConfig (
	    array $config = array (),
	    string $factoryClass = null
	    )

array string



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $config

        - 

    *
        - $factoryClass

        - 

    *
        - InvalidArgumentException

        -

.. _doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap_1aa68bd28db3348387b2d4dab662f20491:
.. _cid-korowai::component::ldap::ldap::__construct:
.. ref-code-block:: cpp
	:class: title-code-block

	__construct (:ref:`AdapterInterface<doxid-d8/dca/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_interface>` $adapter)

Create new :ref:`Ldap <doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap>` instance

:ref:`Adapter <doxid-db/db7/namespace_korowai_1_1_component_1_1_ldap_1_1_adapter>`



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $adapter

        -

.. _doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap_1a23a4c71383d4d2dfc4ccade9fc78e5b9:
.. _cid-korowai::component::ldap::ldap::getadapter:
.. ref-code-block:: cpp
	:class: title-code-block

	getAdapter ()

{ Returns adapter

}



.. rubric:: Returns:

AdapterInterface :ref:`Adapter <doxid-db/db7/namespace_korowai_1_1_component_1_1_ldap_1_1_adapter>`

.. _doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap_1ac89b72f3b844eb3316dfcab18ae1c0ba:
.. _cid-korowai::component::ldap::ldap::getbinding:
.. ref-code-block:: cpp
	:class: title-code-block

	getBinding ()

{ Returns the current binding object.

}



.. rubric:: Returns:

:ref:`BindingInterface <doxid-dc/d90/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_binding_interface>`

.. _doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap_1a8b66a24d3f88cc755663c4e25802d924:
.. _cid-korowai::component::ldap::ldap::getentrymanager:
.. ref-code-block:: cpp
	:class: title-code-block

	getEntryManager ()

{ Returns the current entry manager.

}



.. rubric:: Returns:

:ref:`EntryManagerInterface <doxid-da/ddf/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_entry_manager_interface>`

.. _doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap_1aa30d268841f3bf5d79b89c2bd2749959:
.. _cid-korowai::component::ldap::ldap::bind:
.. ref-code-block:: cpp
	:class: title-code-block

	bind (
	    string $dn = null,
	    string $password = null
	    )

{ Binds the connection against a DN and password

string string

}



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $dn

        - The user's DN

    *
        - $password

        - The associated password

.. _doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap_1a346fa8b27edda87ed42edbd0323e2b63:
.. _cid-korowai::component::ldap::ldap::unbind:
.. ref-code-block:: cpp
	:class: title-code-block

	unbind ()

{ Unbinds the connection

}

.. _doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap_1aa146f780c6982d8fb070a9bd579dacdc:
.. _cid-korowai::component::ldap::ldap::isbound:
.. ref-code-block:: cpp
	:class: title-code-block

	isBound ()

{ Check whether the connection was already bound or not.

}



.. rubric:: Returns:

bool

.. _doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap_1afc4ba29bd636263e23c5e19f1bb89b37:
.. _cid-korowai::component::ldap::ldap::add:
.. ref-code-block:: cpp
	:class: title-code-block

	add (:ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry)

{ Adds a new entry in the LDAP server.

:ref:`Entry <doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>`

}



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $entry

        - 

    *
        - LdapException

        -

.. _doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap_1a9642cba773a197ae73af05f63476f34f:
.. _cid-korowai::component::ldap::ldap::update:
.. ref-code-block:: cpp
	:class: title-code-block

	update (:ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry)

{ Updates an entry in :ref:`Ldap <doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap>` server

:ref:`Entry <doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>`

}



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $entry

        - 

    *
        - LdapException

        -

.. _doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap_1a70c998a892022ffe5a5988bfcea1060e:
.. _cid-korowai::component::ldap::ldap::rename:
.. ref-code-block:: cpp
	:class: title-code-block

	rename (
	    :ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry,
	    string $newRdn,
	    bool $deleteOldRdn = true
	    )

{ Renames an entry on the :ref:`Ldap <doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap>` server

:ref:`Entry <doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` string bool

}



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $entry

        - 

    *
        - $newRdn

        - 

    *
        - $deleteOldRdn

        - 

    *
        - LdapException

        -

.. _doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap_1a8fef021c7f47350290dbe7c5dcef783d:
.. _cid-korowai::component::ldap::ldap::delete:
.. ref-code-block:: cpp
	:class: title-code-block

	delete (:ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry)

{ Removes an entry from the :ref:`Ldap <doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap>` server

:ref:`Entry <doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>`

}



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $entry

        - 

    *
        - LdapException

        -

.. _doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap_1ad8b740f1e3428926aeb01d73c286e1d9:
.. _cid-korowai::component::ldap::ldap::createquery:
.. ref-code-block:: cpp
	:class: title-code-block

	createQuery (
	    string $base_dn,
	    string $filter,
	    array $options = array ()
	    )

{ Creates a search query.

string string array

}



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $base_dn

        - Base DN where the search will start

    *
        - $filter

        - Filter used by ldap search

    *
        - $options

        - Additional search options



.. rubric:: Returns:

:ref:`QueryInterface <doxid-d1/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_query_interface>`

