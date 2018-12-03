.. index:: pair: class; Korowai::Component::Ldap::Adapter::ExtLdap::ResultReferenceIterator
.. _doxid-df/d9b/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference_iterator:
.. _cid-korowai::component::ldap::adapter::extldap::resultreferenceiterator:

class Korowai::Component::Ldap::Adapter::ExtLdap::ResultReferenceIterator
=========================================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl :ref:`More...<details-doxid-df/d9b/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference_iterator>`

.. _doxid-df/d9b/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference_iterator_1a763efbb62631a687efd98b52e990306c:
.. _cid-korowai::component::ldap::adapter::extldap::resultreferenceiterator::__construct:
.. _doxid-df/d9b/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference_iterator_1ae077eb8a032a325ceb939bfabfa5f472:
.. _cid-korowai::component::ldap::adapter::extldap::resultreferenceiterator::getresult:
.. _doxid-df/d9b/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference_iterator_1a1f54573d48e07a7250b74f80b8493f1d:
.. _cid-korowai::component::ldap::adapter::extldap::resultreferenceiterator::getreference:
.. ref-code-block:: cpp
	:class: overview-code-block

	class ResultReferenceIterator: public :ref:`Korowai::Component::Ldap::Adapter::ResultReferenceIteratorInterface<doxid-dd/d4f/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_reference_iterator_interface>`

	// methods

	__construct (
	    :ref:`Result<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` $result,
	    $reference
	    )

	getResult ()
	getReference ()
	:ref:`current<doxid-df/d9b/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference_iterator_1af343507d1926e6ecf964625d41db528c>` ()
	:ref:`key<doxid-df/d9b/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference_iterator_1a729e946b4ef600e71740113c6d4332c0>` ()
	:ref:`next<doxid-df/d9b/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference_iterator_1acea62048bfee7b3cd80ed446c86fb78a>` ()
	:ref:`rewind<doxid-df/d9b/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference_iterator_1ae619dcf2218c21549cb65d875bbc6c9c>` ()
	:ref:`valid<doxid-df/d9b/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference_iterator_1abb9f0d6adf1eb9b3b55712056861a247>` ()

.. _details-doxid-df/d9b/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference_iterator:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-df/d9b/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference_iterator_1af343507d1926e6ecf964625d41db528c:
.. _cid-korowai::component::ldap::adapter::extldap::resultreferenceiterator::current:
.. ref-code-block:: cpp
	:class: title-code-block

	current ()

Return the current element, that is the current reference

.. _doxid-df/d9b/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference_iterator_1a729e946b4ef600e71740113c6d4332c0:
.. _cid-korowai::component::ldap::adapter::extldap::resultreferenceiterator::key:
.. ref-code-block:: cpp
	:class: title-code-block

	key ()

Return the key of the current element, that is DN of the current reference

.. _doxid-df/d9b/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference_iterator_1acea62048bfee7b3cd80ed446c86fb78a:
.. _cid-korowai::component::ldap::adapter::extldap::resultreferenceiterator::next:
.. ref-code-block:: cpp
	:class: title-code-block

	next ()

Move forward to next element

.. _doxid-df/d9b/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference_iterator_1ae619dcf2218c21549cb65d875bbc6c9c:
.. _cid-korowai::component::ldap::adapter::extldap::resultreferenceiterator::rewind:
.. ref-code-block:: cpp
	:class: title-code-block

	rewind ()

Rewind the iterator to the first element

.. _doxid-df/d9b/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference_iterator_1abb9f0d6adf1eb9b3b55712056861a247:
.. _cid-korowai::component::ldap::adapter::extldap::resultreferenceiterator::valid:
.. ref-code-block:: cpp
	:class: title-code-block

	valid ()

Checks if current position is valid

