.. index:: pair: class; Korowai::Component::Ldap::Adapter::AbstractResultEntry
.. _doxid-de/d4d/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_result_entry:
.. _cid-korowai::component::ldap::adapter::abstractresultentry:

class Korowai::Component::Ldap::Adapter::AbstractResultEntry
============================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl :ref:`More...<details-doxid-de/d4d/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_result_entry>`

.. ref-code-block:: cpp
	:class: overview-code-block

	class AbstractResultEntry: public :ref:`Korowai::Component::Ldap::Adapter::ResultEntryInterface<doxid-d3/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_entry_interface>`

	    // direct descendants

	    class :ref:`Korowai::Component::Ldap::Adapter::ExtLdap::ResultEntry<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry>` 

	// methods

	:ref:`toEntry<doxid-de/d4d/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_result_entry_1a6e2f2ed8067b1d18c93b70c387eb2d86>` ()

Inherited Members
-----------------

.. ref-code-block:: cpp
	:class: overview-inherited-code-block

	// methods

	:ref:`getDn<doxid-d3/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_entry_interface_1afb70ca32ff1f281efeff89dd894d2b0f>` ()
	:ref:`getAttributes<doxid-d3/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_entry_interface_1afbe85ec4b9947cc951c67d63911cf0a4>` ()
	:ref:`toEntry<doxid-d3/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_entry_interface_1a6e2f2ed8067b1d18c93b70c387eb2d86>` ()
	:ref:`getAttributeIterator<doxid-d3/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_entry_interface_1a8a282368be441fc092505ab441b6421a>` ()

.. _details-doxid-de/d4d/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_result_entry:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-de/d4d/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_result_entry_1a6e2f2ed8067b1d18c93b70c387eb2d86:
.. _cid-korowai::component::ldap::adapter::abstractresultentry::toentry:
.. ref-code-block:: cpp
	:class: title-code-block

	toEntry ()

{ Creates an ``Entry`` from this object. Equivalent to ``return new Entry($this->getDn(), $this->getAttributes())`` .

}



.. rubric:: Returns:

:ref:`Entry <doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` A new instance of :ref:`Entry <doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>`

