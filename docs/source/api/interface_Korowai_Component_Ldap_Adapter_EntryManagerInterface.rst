.. index:: pair: interface; Korowai::Component::Ldap::Adapter::EntryManagerInterface
.. _doxid-da/ddf/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_entry_manager_interface:
.. _cid-korowai::component::ldap::adapter::entrymanagerinterface:

interface Korowai::Component::Ldap::Adapter::EntryManagerInterface
==================================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl :ref:`More...<details-doxid-da/ddf/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_entry_manager_interface>`

.. ref-code-block:: cpp
	:class: overview-code-block

	interface EntryManagerInterface

	    // direct descendants

	    class :ref:`Korowai::Component::Ldap::Adapter::ExtLdap::EntryManager<doxid-db/d60/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_entry_manager>` 
	    interface :ref:`Korowai::Component::Ldap::LdapInterface<doxid-df/d00/interface_korowai_1_1_component_1_1_ldap_1_1_ldap_interface>` 

	// methods

	:ref:`add<doxid-da/ddf/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_entry_manager_interface_1afc4ba29bd636263e23c5e19f1bb89b37>` (:ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry)
	:ref:`update<doxid-da/ddf/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_entry_manager_interface_1a9642cba773a197ae73af05f63476f34f>` (:ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry)

	:ref:`rename<doxid-da/ddf/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_entry_manager_interface_1a70c998a892022ffe5a5988bfcea1060e>` (
	    :ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry,
	    string $newRdn,
	    bool $deleteOldRdn = true
	    )

	:ref:`delete<doxid-da/ddf/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_entry_manager_interface_1a8fef021c7f47350290dbe7c5dcef783d>` (:ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry)

.. _details-doxid-da/ddf/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_entry_manager_interface:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-da/ddf/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_entry_manager_interface_1afc4ba29bd636263e23c5e19f1bb89b37:
.. _cid-korowai::component::ldap::adapter::entrymanagerinterface::add:
.. ref-code-block:: cpp
	:class: title-code-block

	add (:ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry)

Adds a new entry in the LDAP server.

:ref:`Entry <doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>`



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $entry

        - 

    *
        - LdapException

        -

.. _doxid-da/ddf/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_entry_manager_interface_1a9642cba773a197ae73af05f63476f34f:
.. _cid-korowai::component::ldap::adapter::entrymanagerinterface::update:
.. ref-code-block:: cpp
	:class: title-code-block

	update (:ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry)

Updates an entry in :ref:`Ldap <doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap>` server

:ref:`Entry <doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>`



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $entry

        - 

    *
        - LdapException

        -

.. _doxid-da/ddf/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_entry_manager_interface_1a70c998a892022ffe5a5988bfcea1060e:
.. _cid-korowai::component::ldap::adapter::entrymanagerinterface::rename:
.. ref-code-block:: cpp
	:class: title-code-block

	rename (
	    :ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry,
	    string $newRdn,
	    bool $deleteOldRdn = true
	    )

Renames an entry on the :ref:`Ldap <doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap>` server

:ref:`Entry <doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` string bool



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

.. _doxid-da/ddf/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_entry_manager_interface_1a8fef021c7f47350290dbe7c5dcef783d:
.. _cid-korowai::component::ldap::adapter::entrymanagerinterface::delete:
.. ref-code-block:: cpp
	:class: title-code-block

	delete (:ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry)

Removes an entry from the :ref:`Ldap <doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap>` server

:ref:`Entry <doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>`



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $entry

        - 

    *
        - LdapException

        -

