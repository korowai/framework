.. index:: pair: class; Korowai::Component::Ldap::Adapter::ExtLdap::EntryManager
.. _doxid-db/d60/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_entry_manager:
.. _cid-korowai::component::ldap::adapter::extldap::entrymanager:

class Korowai::Component::Ldap::Adapter::ExtLdap::EntryManager
==============================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl :ref:`More...<details-doxid-db/d60/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_entry_manager>`

.. _doxid-db/d60/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_1a7b8fd9c8b7bee858bfea842e39f931f4:
.. _cid-korowai::component::ldap::adapter::extldap::entrymanager::updateimpl:
.. ref-code-block:: cpp
	:class: overview-code-block

	class EntryManager: public :ref:`Korowai::Component::Ldap::Adapter::EntryManagerInterface<doxid-da/ddf/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_entry_manager_interface>`

	// methods

	:ref:`__construct<doxid-db/d60/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_1ae513bd043b6a9db423d2fbb6ed205a71>` (:ref:`LdapLink<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link>` $link)
	:ref:`getLink<doxid-db/d60/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_1abea978f4dcd47c4289232744d3ed2f01>` ()
	:ref:`add<doxid-db/d60/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_1afc4ba29bd636263e23c5e19f1bb89b37>` (:ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry)
	:ref:`update<doxid-db/d60/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_1a9642cba773a197ae73af05f63476f34f>` (:ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry)

	:ref:`rename<doxid-db/d60/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_1a70c998a892022ffe5a5988bfcea1060e>` (
	    :ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry,
	    string $newRdn,
	    bool $deleteOldRdn = true
	    )

	:ref:`delete<doxid-db/d60/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_1a8fef021c7f47350290dbe7c5dcef783d>` (:ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry)
	updateImpl (:ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry)

	:ref:`renameImpl<doxid-db/d60/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_1aafe22ec6ee643695bbaeafe0af581246>` (
	    :ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry,
	    string $newRdn,
	    bool $deleteOldRdn = true
	    )

	:ref:`deleteImpl<doxid-db/d60/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_1a07b29da75eb60950fafea26c3bebd1dc>` (:ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry)

Inherited Members
-----------------

.. ref-code-block:: cpp
	:class: overview-inherited-code-block

	// methods

	:ref:`add<doxid-da/ddf/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_entry_manager_interface_1afc4ba29bd636263e23c5e19f1bb89b37>` (:ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry)
	:ref:`update<doxid-da/ddf/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_entry_manager_interface_1a9642cba773a197ae73af05f63476f34f>` (:ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry)

	:ref:`rename<doxid-da/ddf/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_entry_manager_interface_1a70c998a892022ffe5a5988bfcea1060e>` (
	    :ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry,
	    string $newRdn,
	    bool $deleteOldRdn = true
	    )

	:ref:`delete<doxid-da/ddf/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_entry_manager_interface_1a8fef021c7f47350290dbe7c5dcef783d>` (:ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry)

.. _details-doxid-db/d60/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_entry_manager:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-db/d60/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_1ae513bd043b6a9db423d2fbb6ed205a71:
.. _cid-korowai::component::ldap::adapter::extldap::entrymanager::__construct:
.. ref-code-block:: cpp
	:class: title-code-block

	__construct (:ref:`LdapLink<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link>` $link)

Constructs :ref:`EntryManager <doxid-db/d60/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_entry_manager>`

.. _doxid-db/d60/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_1abea978f4dcd47c4289232744d3ed2f01:
.. _cid-korowai::component::ldap::adapter::extldap::entrymanager::getlink:
.. ref-code-block:: cpp
	:class: title-code-block

	getLink ()

Returns a link resource



.. rubric:: Returns:

resource

.. _doxid-db/d60/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_1afc4ba29bd636263e23c5e19f1bb89b37:
.. _cid-korowai::component::ldap::adapter::extldap::entrymanager::add:
.. ref-code-block:: cpp
	:class: title-code-block

	add (:ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry)

{ Adds a new entry in the LDAP server.

:ref:`Entry <doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>`

}

Invokes ldap_add().



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $entry

        - 

    *
        - LdapException

        -

.. _doxid-db/d60/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_1a9642cba773a197ae73af05f63476f34f:
.. _cid-korowai::component::ldap::adapter::extldap::entrymanager::update:
.. ref-code-block:: cpp
	:class: title-code-block

	update (:ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry)

{ Updates an entry in :ref:`Ldap <doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap>` server

:ref:`Entry <doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>`

}

Invokes ldap_modify()



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $entry

        - 

    *
        - LdapException

        -

.. _doxid-db/d60/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_1a70c998a892022ffe5a5988bfcea1060e:
.. _cid-korowai::component::ldap::adapter::extldap::entrymanager::rename:
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

Invokes ldap_rename()



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

.. _doxid-db/d60/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_1a8fef021c7f47350290dbe7c5dcef783d:
.. _cid-korowai::component::ldap::adapter::extldap::entrymanager::delete:
.. ref-code-block:: cpp
	:class: title-code-block

	delete (:ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry)

{ Removes an entry from the :ref:`Ldap <doxid-d3/d4a/class_korowai_1_1_component_1_1_ldap_1_1_ldap>` server

:ref:`Entry <doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>`

}

Invokes ldap_delete()



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $entry

        - 

    *
        - LdapException

        -

.. _doxid-db/d60/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_1aafe22ec6ee643695bbaeafe0af581246:
.. _cid-korowai::component::ldap::adapter::extldap::entrymanager::renameimpl:
.. ref-code-block:: cpp
	:class: title-code-block

	renameImpl (
	    :ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry,
	    string $newRdn,
	    bool $deleteOldRdn = true
	    )

{}

Invokes ldap_rename()

.. _doxid-db/d60/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_1a07b29da75eb60950fafea26c3bebd1dc:
.. _cid-korowai::component::ldap::adapter::extldap::entrymanager::deleteimpl:
.. ref-code-block:: cpp
	:class: title-code-block

	deleteImpl (:ref:`Entry<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` $entry)

{}

Invokes ldap_delete()

