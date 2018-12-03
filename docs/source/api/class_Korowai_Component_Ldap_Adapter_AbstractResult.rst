.. index:: pair: class; Korowai::Component::Ldap::Adapter::AbstractResult
.. _doxid-d6/dfd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_result:
.. _cid-korowai::component::ldap::adapter::abstractresult:

class Korowai::Component::Ldap::Adapter::AbstractResult
=======================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl :ref:`More...<details-doxid-d6/dfd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_result>`

.. ref-code-block:: cpp
	:class: overview-code-block

	class AbstractResult: public :ref:`Korowai::Component::Ldap::Adapter::ResultInterface<doxid-dd/db9/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_interface>`

	    // direct descendants

	    class :ref:`Korowai::Component::Ldap::Adapter::ExtLdap::Result<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` 

	// methods

	:ref:`getEntries<doxid-d6/dfd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_result_1a960b179d82797c28ab8ff33184c3eb3b>` (bool $use_keys = true)
	:ref:`getIterator<doxid-d6/dfd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_result_1a7a9f937c2958e6f4dd7b030f86fb70b7>` ()

Inherited Members
-----------------

.. ref-code-block:: cpp
	:class: overview-inherited-code-block

	// methods

	:ref:`getResultEntryIterator<doxid-dd/db9/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_interface_1ad210d4050fa90483ec499abee6f13423>` ()
	:ref:`getResultReferenceIterator<doxid-dd/db9/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_interface_1abc5e72211389b0917809ddf2d8b76558>` ()
	:ref:`getEntries<doxid-dd/db9/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_interface_1a960b179d82797c28ab8ff33184c3eb3b>` (bool $use_keys = true)

.. _details-doxid-d6/dfd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_result:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-d6/dfd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_result_1a960b179d82797c28ab8ff33184c3eb3b:
.. _cid-korowai::component::ldap::adapter::abstractresult::getentries:
.. ref-code-block:: cpp
	:class: title-code-block

	getEntries (bool $use_keys = true)

{ Get an array of Entries from ldap result

}



.. rubric:: Returns:

array Entries

.. _doxid-d6/dfd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_result_1a7a9f937c2958e6f4dd7b030f86fb70b7:
.. _cid-korowai::component::ldap::adapter::abstractresult::getiterator:
.. ref-code-block:: cpp
	:class: title-code-block

	getIterator ()

Makes the ``Result`` object iterable

