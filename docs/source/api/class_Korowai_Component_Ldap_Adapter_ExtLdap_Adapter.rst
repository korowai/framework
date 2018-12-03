.. index:: pair: class; Korowai::Component::Ldap::Adapter::ExtLdap::Adapter
.. _doxid-df/dea/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_adapter:
.. _cid-korowai::component::ldap::adapter::extldap::adapter:

class Korowai::Component::Ldap::Adapter::ExtLdap::Adapter
=========================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl :ref:`More...<details-doxid-df/dea/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_adapter>`

.. _doxid-df/dea/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_adapter_1ae513bd043b6a9db423d2fbb6ed205a71:
.. _cid-korowai::component::ldap::adapter::extldap::adapter::__construct:
.. ref-code-block:: cpp
	:class: overview-code-block

	class Adapter: public :ref:`Korowai::Component::Ldap::Adapter::AdapterInterface<doxid-d8/dca/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_interface>`

	// methods

	__construct (:ref:`LdapLink<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link>` $link)
	:ref:`getLdapLink<doxid-df/dea/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_adapter_1a3e852e02aed74e2f5b01581c5ea8822d>` ()
	:ref:`getBinding<doxid-df/dea/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_adapter_1ac89b72f3b844eb3316dfcab18ae1c0ba>` ()
	:ref:`getEntryManager<doxid-df/dea/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_adapter_1a8b66a24d3f88cc755663c4e25802d924>` ()

	:ref:`createQuery<doxid-df/dea/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_adapter_1ad8b740f1e3428926aeb01d73c286e1d9>` (
	    string $base_dn,
	    string $filter,
	    array $options = array ()
	    )

Inherited Members
-----------------

.. ref-code-block:: cpp
	:class: overview-inherited-code-block

	// methods

	:ref:`getBinding<doxid-d8/dca/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_interface_1ac89b72f3b844eb3316dfcab18ae1c0ba>` ()
	:ref:`getEntryManager<doxid-d8/dca/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_interface_1a8b66a24d3f88cc755663c4e25802d924>` ()

	:ref:`createQuery<doxid-d8/dca/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_interface_1ad8b740f1e3428926aeb01d73c286e1d9>` (
	    string $base_dn,
	    string $filter,
	    array $options = array ()
	    )

.. _details-doxid-df/dea/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_adapter:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-df/dea/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_adapter_1a3e852e02aed74e2f5b01581c5ea8822d:
.. _cid-korowai::component::ldap::adapter::extldap::adapter::getldaplink:
.. ref-code-block:: cpp
	:class: title-code-block

	getLdapLink ()

Returns the ``$link`` provided to ``__construct()`` at creation



.. rubric:: Returns:

:ref:`LdapLink <doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link>` The ``$link`` provided to ``__construct()`` at creation

.. _doxid-df/dea/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_adapter_1ac89b72f3b844eb3316dfcab18ae1c0ba:
.. _cid-korowai::component::ldap::adapter::extldap::adapter::getbinding:
.. ref-code-block:: cpp
	:class: title-code-block

	getBinding ()

{ Returns the current binding object.

}



.. rubric:: Returns:

:ref:`BindingInterface <doxid-dc/d90/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_binding_interface>`

.. _doxid-df/dea/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_adapter_1a8b66a24d3f88cc755663c4e25802d924:
.. _cid-korowai::component::ldap::adapter::extldap::adapter::getentrymanager:
.. ref-code-block:: cpp
	:class: title-code-block

	getEntryManager ()

{ Returns the current entry manager.

}



.. rubric:: Returns:

:ref:`EntryManagerInterface <doxid-da/ddf/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_entry_manager_interface>`

.. _doxid-df/dea/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_adapter_1ad8b740f1e3428926aeb01d73c286e1d9:
.. _cid-korowai::component::ldap::adapter::extldap::adapter::createquery:
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

