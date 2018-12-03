.. index:: pair: interface; Korowai::Component::Ldap::Adapter::ResultInterface
.. _doxid-dd/db9/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_interface:
.. _cid-korowai::component::ldap::adapter::resultinterface:

interface Korowai::Component::Ldap::Adapter::ResultInterface
============================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl :ref:`More...<details-doxid-dd/db9/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_interface>`

.. ref-code-block:: cpp
	:class: overview-code-block

	interface ResultInterface: public IteratorAggregate

	    // direct descendants

	    class :ref:`Korowai::Component::Ldap::Adapter::AbstractResult<doxid-d6/dfd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_result>` 

	// methods

	:ref:`getResultEntryIterator<doxid-dd/db9/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_interface_1ad210d4050fa90483ec499abee6f13423>` ()
	:ref:`getResultReferenceIterator<doxid-dd/db9/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_interface_1abc5e72211389b0917809ddf2d8b76558>` ()
	:ref:`getEntries<doxid-dd/db9/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_interface_1a960b179d82797c28ab8ff33184c3eb3b>` (bool $use_keys = true)

.. _details-doxid-dd/db9/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_interface:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-dd/db9/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_interface_1ad210d4050fa90483ec499abee6f13423:
.. _cid-korowai::component::ldap::adapter::resultinterface::getresultentryiterator:
.. ref-code-block:: cpp
	:class: title-code-block

	getResultEntryIterator ()

Get iterator over result's entries



.. rubric:: Returns:

:ref:`ResultEntryIteratorInterface <doxid-d2/d10/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_entry_iterator_interface>` The iterator

.. _doxid-dd/db9/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_interface_1abc5e72211389b0917809ddf2d8b76558:
.. _cid-korowai::component::ldap::adapter::resultinterface::getresultreferenceiterator:
.. ref-code-block:: cpp
	:class: title-code-block

	getResultReferenceIterator ()

Get iterator over result's references



.. rubric:: Returns:

:ref:`ResultReferenceIteratorInterface <doxid-dd/d4f/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_reference_iterator_interface>` The iterator

.. _doxid-dd/db9/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_interface_1a960b179d82797c28ab8ff33184c3eb3b:
.. _cid-korowai::component::ldap::adapter::resultinterface::getentries:
.. ref-code-block:: cpp
	:class: title-code-block

	getEntries (bool $use_keys = true)

Get an array of Entries from ldap result



.. rubric:: Returns:

array Entries

