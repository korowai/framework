.. index:: pair: class; Korowai::Component::Ldap::Adapter::ExtLdap::ResultReference
.. _doxid-d6/d01/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference:
.. _cid-korowai::component::ldap::adapter::extldap::resultreference:

class Korowai::Component::Ldap::Adapter::ExtLdap::ResultReference
=================================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Wrapper for ldap reference result resource. :ref:`More...<details-doxid-d6/d01/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference>`

.. ref-code-block:: cpp
	:class: overview-code-block

	class ResultReference:
	    public :ref:`Korowai::Component::Ldap::Adapter::ExtLdap::ResultEntry<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry>`
	    public :ref:`Korowai::Component::Ldap::Adapter::ResultReferenceInterface<doxid-d3/db5/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_reference_interface>`

	// methods

	:ref:`next_reference<doxid-d6/d01/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference_1a7e96c55ff19a4bbcdad0adef1cb6f250>` ()
	:ref:`parse_reference<doxid-d6/d01/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference_1a7599d31d71be00d0e754b835b677ede6>` (& $referrals)
	:ref:`getReferrals<doxid-d6/d01/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference_1a054f872551291fab14d99ba81ab0fbe5>` ()

Inherited Members
-----------------

.. ref-code-block:: cpp
	:class: overview-inherited-code-block

	// methods

	:ref:`getDn<doxid-d3/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_entry_interface_1afb70ca32ff1f281efeff89dd894d2b0f>` ()
	:ref:`getAttributes<doxid-d3/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_entry_interface_1afbe85ec4b9947cc951c67d63911cf0a4>` ()
	:ref:`toEntry<doxid-d3/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_entry_interface_1a6e2f2ed8067b1d18c93b70c387eb2d86>` ()
	:ref:`getAttributeIterator<doxid-d3/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_entry_interface_1a8a282368be441fc092505ab441b6421a>` ()
	:ref:`toEntry<doxid-de/d4d/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_result_entry_1a6e2f2ed8067b1d18c93b70c387eb2d86>` ()

	:ref:`__construct<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_1a7a28a55006035afaeea801ffc6add5dd>` (
	    $entry,
	    :ref:`Result<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` $result
	    )

	:ref:`getResource<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_1a8c5a689e9be7d35d0d01d0194637a7d2>` ()
	:ref:`getResult<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_1ae077eb8a032a325ceb939bfabfa5f472>` ()
	:ref:`first_attribute<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_1a61263f48fc6e3089ca959ab94b559ffd>` ()
	:ref:`get_attributes<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_1a817b935d47337110d60b0496da0d5b73>` ()
	:ref:`get_dn<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_1a25d71610059c54e0e0c0954901bccba7>` ()
	:ref:`get_values_len<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_1a45d6402a44b81867f002057e0622e7de>` (string $attribute)
	:ref:`get_values<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_1a52f92800b08631b997a98c5cd547078c>` ($attribute)
	:ref:`next_attribute<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_1af572f5295fc06cb6a9e29265c18c9e0a>` ()
	:ref:`next_entry<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_1a8ac85e275415870a9f77540b5b63262a>` ()
	:ref:`getAttributeIterator<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_1a8a282368be441fc092505ab441b6421a>` ()
	:ref:`getDn<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_1afb70ca32ff1f281efeff89dd894d2b0f>` ()
	:ref:`getAttributes<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_1afbe85ec4b9947cc951c67d63911cf0a4>` ()
	:ref:`getReferrals<doxid-d3/db5/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_reference_interface_1a054f872551291fab14d99ba81ab0fbe5>` ()

.. _details-doxid-d6/d01/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Wrapper for ldap reference result resource.

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-d6/d01/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference_1a7e96c55ff19a4bbcdad0adef1cb6f250:
.. _cid-korowai::component::ldap::adapter::extldap::resultreference::next_reference:
.. ref-code-block:: cpp
	:class: title-code-block

	next_reference ()

Get next result reference

:ref:`ldap_next_reference() <doxid->`

.. _doxid-d6/d01/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference_1a7599d31d71be00d0e754b835b677ede6:
.. _cid-korowai::component::ldap::adapter::extldap::resultreference::parse_reference:
.. ref-code-block:: cpp
	:class: title-code-block

	parse_reference (& $referrals)

Extract referrals from the reference message

:ref:`ldap_parse_reference() <doxid->`

.. _doxid-d6/d01/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference_1a054f872551291fab14d99ba81ab0fbe5:
.. _cid-korowai::component::ldap::adapter::extldap::resultreference::getreferrals:
.. ref-code-block:: cpp
	:class: title-code-block

	getReferrals ()

{ Returns referrals

}



.. rubric:: Returns:

array An array of referrals

