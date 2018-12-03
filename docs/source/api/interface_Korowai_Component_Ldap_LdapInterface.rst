.. index:: pair: interface; Korowai::Component::Ldap::LdapInterface
.. _doxid-df/d00/interface_korowai_1_1_component_1_1_ldap_1_1_ldap_interface:
.. _cid-korowai::component::ldap::ldapinterface:

interface Korowai::Component::Ldap::LdapInterface
=================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

LDAP interface

Paweł Tomulik ptomulik@meil.pw.edu.pl :ref:`More...<details-doxid-df/d00/interface_korowai_1_1_component_1_1_ldap_1_1_ldap_interface>`

.. ref-code-block:: cpp
	:class: overview-code-block

	interface LdapInterface:
	    public :ref:`Korowai::Component::Ldap::Adapter::BindingInterface<doxid-dc/d90/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_binding_interface>`
	    public :ref:`Korowai::Component::Ldap::Adapter::EntryManagerInterface<doxid-da/ddf/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_entry_manager_interface>`
	    public :ref:`Korowai::Component::Ldap::Adapter::AdapterInterface<doxid-d8/dca/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_interface>`

	    // direct descendants

	    class :ref:`Korowai::Component::Ldap::AbstractLdap<doxid-d1/da7/class_korowai_1_1_component_1_1_ldap_1_1_abstract_ldap>` 

	// methods

	:ref:`getAdapter<doxid-df/d00/interface_korowai_1_1_component_1_1_ldap_1_1_ldap_interface_1a23a4c71383d4d2dfc4ccade9fc78e5b9>` ()

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

.. _details-doxid-df/d00/interface_korowai_1_1_component_1_1_ldap_1_1_ldap_interface:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

LDAP interface

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-df/d00/interface_korowai_1_1_component_1_1_ldap_1_1_ldap_interface_1a23a4c71383d4d2dfc4ccade9fc78e5b9:
.. _cid-korowai::component::ldap::ldapinterface::getadapter:
.. ref-code-block:: cpp
	:class: title-code-block

	getAdapter ()

Returns adapter



.. rubric:: Returns:

AdapterInterface :ref:`Adapter <doxid-db/db7/namespace_korowai_1_1_component_1_1_ldap_1_1_adapter>`

