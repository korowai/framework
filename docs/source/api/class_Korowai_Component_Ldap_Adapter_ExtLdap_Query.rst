.. index:: pair: class; Korowai::Component::Ldap::Adapter::ExtLdap::Query
.. _doxid-d2/d9d/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_query:
.. _cid-korowai::component::ldap::adapter::extldap::query:

class Korowai::Component::Ldap::Adapter::ExtLdap::Query
=======================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl :ref:`More...<details-doxid-d2/d9d/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_query>`

.. ref-code-block:: cpp
	:class: overview-code-block

	class Query: public :ref:`Korowai::Component::Ldap::Adapter::AbstractQuery<doxid-d4/d2e/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_query>`

	// methods

	:ref:`__construct<doxid-d2/d9d/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_query_1a3140e5f153ef8bb4a60e83ab72f876f3>` (
	    :ref:`LdapLink<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link>` $link,
	    string $base_dn,
	    string $filter,
	    array $options = array ()
	    )

	:ref:`getLink<doxid-d2/d9d/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_query_1abea978f4dcd47c4289232744d3ed2f01>` ()

Inherited Members
-----------------

.. ref-code-block:: cpp
	:class: overview-inherited-code-block

	// methods

	:ref:`execute<doxid-d1/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_query_interface_1a1909f4b7f8129c7790cb75de2ffbe1e4>` ()
	:ref:`getResult<doxid-d1/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_query_interface_1ae077eb8a032a325ceb939bfabfa5f472>` ()

	:ref:`__construct<doxid-d4/d2e/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_query_1a668d52797aee90a20b1380d64983f24b>` (
	    string $base_dn,
	    string $filter,
	    array $options = array ()
	    )

	:ref:`getBaseDn<doxid-d4/d2e/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_query_1a9cece4593d7c30ec17181f1733fe4d98>` ()
	:ref:`getFilter<doxid-d4/d2e/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_query_1a2ce3342ac043ecadecff91b1321445f4>` ()
	:ref:`getOptions<doxid-d4/d2e/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_query_1a1a49b8dded6e91a52e2fd07195d334da>` ()
	:ref:`getResult<doxid-d4/d2e/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_query_1ae077eb8a032a325ceb939bfabfa5f472>` ()
	:ref:`execute<doxid-d4/d2e/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_query_1a1909f4b7f8129c7790cb75de2ffbe1e4>` ()
	static static :ref:`getDefaultOptions<doxid-d4/d2e/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_query_1a4c07c427694681161fb52687c4db0bb5>` ()

.. _details-doxid-d2/d9d/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_query:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-d2/d9d/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_query_1a3140e5f153ef8bb4a60e83ab72f876f3:
.. _cid-korowai::component::ldap::adapter::extldap::query::__construct:
.. ref-code-block:: cpp
	:class: title-code-block

	__construct (
	    :ref:`LdapLink<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link>` $link,
	    string $base_dn,
	    string $filter,
	    array $options = array ()
	    )

Constructs :ref:`Query <doxid-d2/d9d/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_query>`

.. _doxid-d2/d9d/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_query_1abea978f4dcd47c4289232744d3ed2f01:
.. _cid-korowai::component::ldap::adapter::extldap::query::getlink:
.. ref-code-block:: cpp
	:class: title-code-block

	getLink ()

Returns a link resource



.. rubric:: Returns:

resource

