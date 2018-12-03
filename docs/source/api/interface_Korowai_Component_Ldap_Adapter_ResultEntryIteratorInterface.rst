.. index:: pair: interface; Korowai::Component::Ldap::Adapter::ResultEntryIteratorInterface
.. _doxid-d2/d10/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_entry_iterator_interface:
.. _cid-korowai::component::ldap::adapter::resultentryiteratorinterface:

interface Korowai::Component::Ldap::Adapter::ResultEntryIteratorInterface
=========================================================================

.. toctree::
	:hidden:



Iterates through entries returned by an ldap search query.

Paweł Tomulik ptomulik@meil.pw.edu.pl

.. ref-code-block:: cpp
	:class: overview-code-block

	interface ResultEntryIteratorInterface: public Iterator

	    // direct descendants

	    class :ref:`Korowai::Component::Ldap::Adapter::ExtLdap::ResultEntryIterator<doxid-df/d5c/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_iterator>` 
	    class :ref:`Korowai::Component::Ldap::Tests::Adapter::_FakeResultEntryIterator<doxid-d6/d40/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1___fake_result_entry_iterator>` 

