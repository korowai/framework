.. index:: pair: namespace; ExtLdap
.. _doxid-d9/d6f/namespace_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap:
.. _cid-korowai::component::ldap::adapter::extldap:

namespace ExtLdap
=================

.. toctree::
	:hidden:

	class_Korowai_Component_Ldap_Adapter_ExtLdap_Adapter.rst
	class_Korowai_Component_Ldap_Adapter_ExtLdap_AdapterFactory.rst
	class_Korowai_Component_Ldap_Adapter_ExtLdap_Binding.rst
	class_Korowai_Component_Ldap_Adapter_ExtLdap_EntryManager.rst
	class_Korowai_Component_Ldap_Adapter_ExtLdap_LdapLink.rst
	class_Korowai_Component_Ldap_Adapter_ExtLdap_Query.rst
	class_Korowai_Component_Ldap_Adapter_ExtLdap_Result.rst
	class_Korowai_Component_Ldap_Adapter_ExtLdap_ResultAttributeIterator.rst
	class_Korowai_Component_Ldap_Adapter_ExtLdap_ResultEntry.rst
	class_Korowai_Component_Ldap_Adapter_ExtLdap_ResultEntryIterator.rst
	class_Korowai_Component_Ldap_Adapter_ExtLdap_ResultReference.rst
	class_Korowai_Component_Ldap_Adapter_ExtLdap_ResultReferenceIterator.rst



Overview
~~~~~~~~



.. ref-code-block:: cpp
	:class: overview-code-block

	// classes

	class :ref:`Adapter<doxid-df/dea/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_adapter>` 
	class :ref:`AdapterFactory<doxid-db/da8/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_adapter_factory>` 
	class :ref:`Binding<doxid-de/d91/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_binding>` 
	class :ref:`EntryManager<doxid-db/d60/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_entry_manager>` 
	class :ref:`LdapLink<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link>` 
	class :ref:`Query<doxid-d2/d9d/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_query>` 
	class :ref:`Result<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` 
	class :ref:`ResultAttributeIterator<doxid-db/d65/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_attribute_iterator>` 
	class :ref:`ResultEntry<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry>` 
	class :ref:`ResultEntryIterator<doxid-df/d5c/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_iterator>` 
	class :ref:`ResultReference<doxid-d6/d01/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference>` 
	class :ref:`ResultReferenceIterator<doxid-df/d9b/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference_iterator>` 

	// global variables

	trait :ref:`LdapLinkOptions<doxid-d9/d6f/namespace_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1afc6d0bb3cfdeccc85365404294c194ef>`

	// global functions

	:ref:`getLdapLinkOptionConstantName<doxid-d9/d6f/namespace_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1a56f4e692a517fc84b987cbcf439d43a1>` ($optionName)
	:ref:`getLdapLinkOptionConstant<doxid-d9/d6f/namespace_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1ad0a39adbfe65bd979b751687a4320c0b>` ($name)
	:ref:`getLdapLinkOptionDeclarations<doxid-d9/d6f/namespace_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1aca19ffb533899af857fae7110c24553a>` ()

.. _details-doxid-d9/d6f/namespace_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~



Global Variables
----------------

.. _doxid-d9/d6f/namespace_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1afc6d0bb3cfdeccc85365404294c194ef:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplinkoptions:
.. ref-code-block:: cpp
	:class: title-code-block

	trait LdapLinkOptions

Paweł Tomulik ptomulik@meil.pw.edu.pl

Global Functions
----------------

.. _doxid-d9/d6f/namespace_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1a56f4e692a517fc84b987cbcf439d43a1:
.. _cid-korowai::component::ldap::adapter::extldap::getldaplinkoptionconstantname:
.. ref-code-block:: cpp
	:class: title-code-block

	getLdapLinkOptionConstantName ($optionName)

Returns name of an ext-ldap option constant for a given option name



.. rubric:: Returns:

string Name of the ext-ldap constant

.. _doxid-d9/d6f/namespace_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1ad0a39adbfe65bd979b751687a4320c0b:
.. _cid-korowai::component::ldap::adapter::extldap::getldaplinkoptionconstant:
.. ref-code-block:: cpp
	:class: title-code-block

	getLdapLinkOptionConstant ($name)

Returns value of an ext-ldap option constant for a given option name



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - LdapException

        - 



.. rubric:: Returns:

mixed Value of the ext-ldap constant

.. _doxid-d9/d6f/namespace_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1aca19ffb533899af857fae7110c24553a:
.. _cid-korowai::component::ldap::adapter::extldap::getldaplinkoptiondeclarations:
.. ref-code-block:: cpp
	:class: title-code-block

	getLdapLinkOptionDeclarations ()

Returns declarations of options, mainly for use by ``configureLdapLinkOptions()``



.. rubric:: Returns:

array Declarations

