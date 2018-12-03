.. index:: pair: class; Korowai::Component::Ldap::Adapter::ExtLdap::ResultAttributeIterator
.. _doxid-db/d65/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_attribute_iterator:
.. _cid-korowai::component::ldap::adapter::extldap::resultattributeiterator:

class Korowai::Component::Ldap::Adapter::ExtLdap::ResultAttributeIterator
=========================================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Iterates through an ldap result entry attributes. :ref:`More...<details-doxid-db/d65/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_attribute_iterator>`

.. ref-code-block:: cpp
	:class: overview-code-block

	class ResultAttributeIterator: public :ref:`Korowai::Component::Ldap::Adapter::ResultAttributeIteratorInterface<doxid-d1/dab/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_attribute_iterator_interface>`

	// methods

	:ref:`__construct<doxid-db/d65/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_attribute_iterator_1ac16d0ff98cbb87150dd312b36396371b>` (
	    :ref:`ResultEntry<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry>` $entry,
	    $attribute
	    )

	:ref:`getEntry<doxid-db/d65/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_attribute_iterator_1a9f2a7644c1943c9a09739b06c00179a8>` ()
	:ref:`getAttribute<doxid-db/d65/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_attribute_iterator_1a697d0938f151f16f1b245ba168054fee>` ()
	:ref:`current<doxid-db/d65/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_attribute_iterator_1af343507d1926e6ecf964625d41db528c>` ()
	:ref:`key<doxid-db/d65/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_attribute_iterator_1a729e946b4ef600e71740113c6d4332c0>` ()
	:ref:`next<doxid-db/d65/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_attribute_iterator_1acea62048bfee7b3cd80ed446c86fb78a>` ()
	:ref:`rewind<doxid-db/d65/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_attribute_iterator_1ae619dcf2218c21549cb65d875bbc6c9c>` ()
	:ref:`valid<doxid-db/d65/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_attribute_iterator_1abb9f0d6adf1eb9b3b55712056861a247>` ()

.. _details-doxid-db/d65/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_attribute_iterator:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Iterates through an ldap result entry attributes.

Only one instance of ``ResultAttributeIterator`` should be used for a given ``ResultEntry`` . The internal state (position) of the iterator is keept and managed by the ``"ldap entry"`` resource (encapsulated by our ``ResultEntry`` object which is provided as ``$entry`` argument to ``ResultAttributeIterator::__construct()`` ). This is a consequence of how PHP ldap extension implements attribute iteration the ``berptr`` argument to ``libldap`` functions ldap_first_attribute (3) and ldap_next_attribute (3) is stored by PHP ldap extension in an ``"ldap entry"`` resource and is inaccessible for user.

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-db/d65/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_attribute_iterator_1ac16d0ff98cbb87150dd312b36396371b:
.. _cid-korowai::component::ldap::adapter::extldap::resultattributeiterator::__construct:
.. ref-code-block:: cpp
	:class: title-code-block

	__construct (
	    :ref:`ResultEntry<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry>` $entry,
	    $attribute
	    )

Initializes the ``ResultAttributeIterator`` .

The ``$attribute`` should be a valid attribute name returned by either ``$entry->first_attribute()`` or ``$entry->next_attribute()`` or it should be null.

:ref:`ResultEntry <doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry>` string null



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $entry

        - An ldap entry containing the attributes

    *
        - $attribute

        - Name of the current attribute pointed to by Iterator

.. _doxid-db/d65/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_attribute_iterator_1a9f2a7644c1943c9a09739b06c00179a8:
.. _cid-korowai::component::ldap::adapter::extldap::resultattributeiterator::getentry:
.. ref-code-block:: cpp
	:class: title-code-block

	getEntry ()

Returns the ``$entry`` provided to ``__construct`` at creation time. :ref:`ResultEntry <doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry>` The ``$entry`` provided to ``__construct`` at creation time.

.. _doxid-db/d65/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_attribute_iterator_1a697d0938f151f16f1b245ba168054fee:
.. _cid-korowai::component::ldap::adapter::extldap::resultattributeiterator::getattribute:
.. ref-code-block:: cpp
	:class: title-code-block

	getAttribute ()

Returns the name of current attribute.



.. rubric:: Returns:

string|null The name of current attribute or ``null`` if the iterator is invalid (past the end).

.. _doxid-db/d65/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_attribute_iterator_1af343507d1926e6ecf964625d41db528c:
.. _cid-korowai::component::ldap::adapter::extldap::resultattributeiterator::current:
.. ref-code-block:: cpp
	:class: title-code-block

	current ()

Returns an array of values of the current attribute.

Should only be used on valid iterator.



.. rubric:: Returns:

array an array of values of the current attribute. :ref:`Iterator::current <doxid->`

.. _doxid-db/d65/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_attribute_iterator_1a729e946b4ef600e71740113c6d4332c0:
.. _cid-korowai::component::ldap::adapter::extldap::resultattributeiterator::key:
.. ref-code-block:: cpp
	:class: title-code-block

	key ()

Returns the key of the current element (name of current attribute). :ref:`Iterator::key <doxid->`



.. rubric:: Returns:

string|null The name of current attribute or ``null`` if the iterator is invalid (past the end).

.. _doxid-db/d65/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_attribute_iterator_1acea62048bfee7b3cd80ed446c86fb78a:
.. _cid-korowai::component::ldap::adapter::extldap::resultattributeiterator::next:
.. ref-code-block:: cpp
	:class: title-code-block

	next ()

Moves the current position to the next element

:ref:`Iterator::next <doxid->`

.. _doxid-db/d65/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_attribute_iterator_1ae619dcf2218c21549cb65d875bbc6c9c:
.. _cid-korowai::component::ldap::adapter::extldap::resultattributeiterator::rewind:
.. ref-code-block:: cpp
	:class: title-code-block

	rewind ()

Rewinds back to the first element of the iterator

:ref:`Iterator::rewind <doxid->`

.. _doxid-db/d65/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_attribute_iterator_1abb9f0d6adf1eb9b3b55712056861a247:
.. _cid-korowai::component::ldap::adapter::extldap::resultattributeiterator::valid:
.. ref-code-block:: cpp
	:class: title-code-block

	valid ()

Checks if current position is valid

:ref:`Iterator::valid <doxid->`

