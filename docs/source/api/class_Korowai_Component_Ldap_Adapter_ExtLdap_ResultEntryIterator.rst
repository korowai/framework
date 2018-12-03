.. index:: pair: class; Korowai::Component::Ldap::Adapter::ExtLdap::ResultEntryIterator
.. _doxid-df/d5c/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_iterator:
.. _cid-korowai::component::ldap::adapter::extldap::resultentryiterator:

class Korowai::Component::Ldap::Adapter::ExtLdap::ResultEntryIterator
=====================================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl :ref:`More...<details-doxid-df/d5c/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_iterator>`

.. ref-code-block:: cpp
	:class: overview-code-block

	class ResultEntryIterator: public :ref:`Korowai::Component::Ldap::Adapter::ResultEntryIteratorInterface<doxid-d2/d10/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_entry_iterator_interface>`

	// methods

	:ref:`__construct<doxid-df/d5c/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_iterator_1a7b6cec0debd9ab99a5c5a6fe31db0431>` (
	    :ref:`Result<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` $result,
	    $entry
	    )

	:ref:`getResult<doxid-df/d5c/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_iterator_1ae077eb8a032a325ceb939bfabfa5f472>` ()
	:ref:`getEntry<doxid-df/d5c/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_iterator_1a9f2a7644c1943c9a09739b06c00179a8>` ()
	:ref:`current<doxid-df/d5c/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_iterator_1af343507d1926e6ecf964625d41db528c>` ()
	:ref:`key<doxid-df/d5c/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_iterator_1a729e946b4ef600e71740113c6d4332c0>` ()
	:ref:`next<doxid-df/d5c/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_iterator_1acea62048bfee7b3cd80ed446c86fb78a>` ()
	:ref:`rewind<doxid-df/d5c/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_iterator_1ae619dcf2218c21549cb65d875bbc6c9c>` ()
	:ref:`valid<doxid-df/d5c/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_iterator_1abb9f0d6adf1eb9b3b55712056861a247>` ()

.. _details-doxid-df/d5c/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_iterator:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-df/d5c/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_iterator_1a7b6cec0debd9ab99a5c5a6fe31db0431:
.. _cid-korowai::component::ldap::adapter::extldap::resultentryiterator::__construct:
.. ref-code-block:: cpp
	:class: title-code-block

	__construct (
	    :ref:`Result<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` $result,
	    $entry
	    )

Constructs :ref:`ResultEntryIterator <doxid-df/d5c/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_iterator>`

:ref:`Result <doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` :ref:`ResultEntry <doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry>` null The ``$result`` object is used by ``rewind()`` method.



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $result

        - The ldap search result which provides first entry in the entry chain

    *
        - $entry

        - The current entry in the chain or ``null`` to create an invalid (past the end) iterator

.. _doxid-df/d5c/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_iterator_1ae077eb8a032a325ceb939bfabfa5f472:
.. _cid-korowai::component::ldap::adapter::extldap::resultentryiterator::getresult:
.. ref-code-block:: cpp
	:class: title-code-block

	getResult ()

Returns the ``$result`` provided to ``__construct()`` when the object was created.



.. rubric:: Returns:

:ref:`Result <doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` The result object provided as ``$result`` argument to ``__construct()`` .

.. _doxid-df/d5c/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_iterator_1a9f2a7644c1943c9a09739b06c00179a8:
.. _cid-korowai::component::ldap::adapter::extldap::resultentryiterator::getentry:
.. ref-code-block:: cpp
	:class: title-code-block

	getEntry ()

Returns the ``$entry`` provided to ``__construct()`` at creation



.. rubric:: Returns:

mixed The ``$entry`` provided to ``__construct()`` at creation

.. _doxid-df/d5c/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_iterator_1af343507d1926e6ecf964625d41db528c:
.. _cid-korowai::component::ldap::adapter::extldap::resultentryiterator::current:
.. ref-code-block:: cpp
	:class: title-code-block

	current ()

Return the current element, that is the current entry

.. _doxid-df/d5c/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_iterator_1a729e946b4ef600e71740113c6d4332c0:
.. _cid-korowai::component::ldap::adapter::extldap::resultentryiterator::key:
.. ref-code-block:: cpp
	:class: title-code-block

	key ()

Return the key of the current element, that is DN of the current entry

.. _doxid-df/d5c/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_iterator_1acea62048bfee7b3cd80ed446c86fb78a:
.. _cid-korowai::component::ldap::adapter::extldap::resultentryiterator::next:
.. ref-code-block:: cpp
	:class: title-code-block

	next ()

Move forward to next element

.. _doxid-df/d5c/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_iterator_1ae619dcf2218c21549cb65d875bbc6c9c:
.. _cid-korowai::component::ldap::adapter::extldap::resultentryiterator::rewind:
.. ref-code-block:: cpp
	:class: title-code-block

	rewind ()

Rewind the iterator to the first element

.. _doxid-df/d5c/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry_iterator_1abb9f0d6adf1eb9b3b55712056861a247:
.. _cid-korowai::component::ldap::adapter::extldap::resultentryiterator::valid:
.. ref-code-block:: cpp
	:class: title-code-block

	valid ()

Checks if current position is valid

