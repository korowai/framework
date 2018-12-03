.. index:: pair: class; Korowai::Component::Ldap::Adapter::ExtLdap::ResultEntry
.. _doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry:
.. _cid-korowai::component::ldap::adapter::extldap::resultentry:

class Korowai::Component::Ldap::Adapter::ExtLdap::ResultEntry
=============================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Wrapper for ldap entry result resource. :ref:`More...<details-doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry>`

.. ref-code-block:: cpp
	:class: overview-code-block

	class ResultEntry: public :ref:`Korowai::Component::Ldap::Adapter::AbstractResultEntry<doxid-de/d4d/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_result_entry>`

	    // direct descendants

	    class :ref:`Korowai::Component::Ldap::Adapter::ExtLdap::ResultReference<doxid-d6/d01/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference>` 

	// methods

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

.. _details-doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Wrapper for ldap entry result resource.

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_1a7a28a55006035afaeea801ffc6add5dd:
.. _cid-korowai::component::ldap::adapter::extldap::resultentry::__construct:
.. ref-code-block:: cpp
	:class: title-code-block

	__construct (
	    $entry,
	    :ref:`Result<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` $result
	    )

Initializes the ``ResultEntry`` instance

resource null :ref:`Result <doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>`



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $entry

        - 

    *
        - $result

        -

.. _doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_1a8c5a689e9be7d35d0d01d0194637a7d2:
.. _cid-korowai::component::ldap::adapter::extldap::resultentry::getresource:
.. ref-code-block:: cpp
	:class: title-code-block

	getResource ()

Return the underlying resource identifier.



.. rubric:: Returns:

resource

.. _doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_1ae077eb8a032a325ceb939bfabfa5f472:
.. _cid-korowai::component::ldap::adapter::extldap::resultentry::getresult:
.. ref-code-block:: cpp
	:class: title-code-block

	getResult ()

Return the :ref:`Result <doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` object which contains the entry.



.. rubric:: Returns:

resource

.. _doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_1a61263f48fc6e3089ca959ab94b559ffd:
.. _cid-korowai::component::ldap::adapter::extldap::resultentry::first_attribute:
.. ref-code-block:: cpp
	:class: title-code-block

	first_attribute ()

Return first attribute

:ref:`ldap_first_attribute() <doxid->`

.. _doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_1a817b935d47337110d60b0496da0d5b73:
.. _cid-korowai::component::ldap::adapter::extldap::resultentry::get_attributes:
.. ref-code-block:: cpp
	:class: title-code-block

	get_attributes ()

Get attributes from a search result entry

:ref:`ldap_get_attributes() <doxid->`

.. _doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_1a25d71610059c54e0e0c0954901bccba7:
.. _cid-korowai::component::ldap::adapter::extldap::resultentry::get_dn:
.. ref-code-block:: cpp
	:class: title-code-block

	get_dn ()

Get the DN of a result entry

:ref:`ldap_get_dn() <doxid->`

.. _doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_1a45d6402a44b81867f002057e0622e7de:
.. _cid-korowai::component::ldap::adapter::extldap::resultentry::get_values_len:
.. ref-code-block:: cpp
	:class: title-code-block

	get_values_len (string $attribute)

Get all binary values from a result entry

:ref:`ldap_get_values_len() <doxid->`

.. _doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_1a52f92800b08631b997a98c5cd547078c:
.. _cid-korowai::component::ldap::adapter::extldap::resultentry::get_values:
.. ref-code-block:: cpp
	:class: title-code-block

	get_values ($attribute)

Get all values from a result entry

:ref:`ldap_get_values() <doxid->`

.. _doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_1af572f5295fc06cb6a9e29265c18c9e0a:
.. _cid-korowai::component::ldap::adapter::extldap::resultentry::next_attribute:
.. ref-code-block:: cpp
	:class: title-code-block

	next_attribute ()

Get the next attribute in result

:ref:`ldap_next_attribute() <doxid->`

.. _doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_1a8ac85e275415870a9f77540b5b63262a:
.. _cid-korowai::component::ldap::adapter::extldap::resultentry::next_entry:
.. ref-code-block:: cpp
	:class: title-code-block

	next_entry ()

Get next result entry

:ref:`ldap_next_entry() <doxid->`

.. _doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_1a8a282368be441fc092505ab441b6421a:
.. _cid-korowai::component::ldap::adapter::extldap::resultentry::getattributeiterator:
.. ref-code-block:: cpp
	:class: title-code-block

	getAttributeIterator ()

It always returns same instance. When used for the first time, the iterator is set to point to the first attribute of the entry. For subsequent calls, the method just return the iterator without altering its position.

.. _doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_1afb70ca32ff1f281efeff89dd894d2b0f:
.. _cid-korowai::component::ldap::adapter::extldap::resultentry::getdn:
.. ref-code-block:: cpp
	:class: title-code-block

	getDn ()

{ Returns Distinguished Name (DN) of the result entry

}



.. rubric:: Returns:

string Distinguished Name of the result entry

.. _doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_1afbe85ec4b9947cc951c67d63911cf0a4:
.. _cid-korowai::component::ldap::adapter::extldap::resultentry::getattributes:
.. ref-code-block:: cpp
	:class: title-code-block

	getAttributes ()

{ Returns entry attributes as an array. The keys in array are lower-case.

}



.. rubric:: Returns:

array :ref:`Entry <doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` 's attributes

