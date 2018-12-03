.. index:: pair: interface; Korowai::Component::Ldap::Adapter::ResultEntryInterface
.. _doxid-d3/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_entry_interface:
.. _cid-korowai::component::ldap::adapter::resultentryinterface:

interface Korowai::Component::Ldap::Adapter::ResultEntryInterface
=================================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl :ref:`More...<details-doxid-d3/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_entry_interface>`

.. ref-code-block:: cpp
	:class: overview-code-block

	interface ResultEntryInterface

	    // direct descendants

	    class :ref:`Korowai::Component::Ldap::Adapter::AbstractResultEntry<doxid-de/d4d/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_result_entry>` 

	// methods

	:ref:`getDn<doxid-d3/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_entry_interface_1afb70ca32ff1f281efeff89dd894d2b0f>` ()
	:ref:`getAttributes<doxid-d3/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_entry_interface_1afbe85ec4b9947cc951c67d63911cf0a4>` ()
	:ref:`toEntry<doxid-d3/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_entry_interface_1a6e2f2ed8067b1d18c93b70c387eb2d86>` ()
	:ref:`getAttributeIterator<doxid-d3/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_entry_interface_1a8a282368be441fc092505ab441b6421a>` ()

.. _details-doxid-d3/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_entry_interface:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-d3/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_entry_interface_1afb70ca32ff1f281efeff89dd894d2b0f:
.. _cid-korowai::component::ldap::adapter::resultentryinterface::getdn:
.. ref-code-block:: cpp
	:class: title-code-block

	getDn ()

Returns Distinguished Name (DN) of the result entry



.. rubric:: Returns:

string Distinguished Name of the result entry

.. _doxid-d3/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_entry_interface_1afbe85ec4b9947cc951c67d63911cf0a4:
.. _cid-korowai::component::ldap::adapter::resultentryinterface::getattributes:
.. ref-code-block:: cpp
	:class: title-code-block

	getAttributes ()

Returns entry attributes as an array. The keys in array are lower-case.



.. rubric:: Returns:

array :ref:`Entry <doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` 's attributes

.. _doxid-d3/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_entry_interface_1a6e2f2ed8067b1d18c93b70c387eb2d86:
.. _cid-korowai::component::ldap::adapter::resultentryinterface::toentry:
.. ref-code-block:: cpp
	:class: title-code-block

	toEntry ()

Creates an ``Entry`` from this object. Equivalent to ``return new Entry($this->getDn(), $this->getAttributes())`` .



.. rubric:: Returns:

:ref:`Entry <doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` A new instance of :ref:`Entry <doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>`

.. _doxid-d3/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_entry_interface_1a8a282368be441fc092505ab441b6421a:
.. _cid-korowai::component::ldap::adapter::resultentryinterface::getattributeiterator:
.. ref-code-block:: cpp
	:class: title-code-block

	getAttributeIterator ()

Returns an iterator over entry's attributes.



.. rubric:: Returns:

:ref:`ResultAttributeIteratorInterface <doxid-d1/dab/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_attribute_iterator_interface>` Attribute iterator

