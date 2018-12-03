.. index:: pair: class; Korowai::Component::Ldap::Adapter::ExtLdap::Result
.. _doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result:
.. _cid-korowai::component::ldap::adapter::extldap::result:

class Korowai::Component::Ldap::Adapter::ExtLdap::Result
========================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Wrapper for ldap result resource. :ref:`More...<details-doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>`

.. _doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1a6b65aad26508a8e0f800085f5edce30f:
.. _cid-korowai::component::ldap::adapter::extldap::result::isldapresultresource:
.. ref-code-block:: cpp
	:class: overview-code-block

	class Result: public :ref:`Korowai::Component::Ldap::Adapter::AbstractResult<doxid-d6/dfd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_result>`

	// methods

	static static isLdapResultResource ($arg)

	:ref:`__construct<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1ab553f426b8bf9ea8be2afc0765be275a>` (
	    $result,
	    :ref:`LdapLink<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link>` $link
	    )

	:ref:`__destruct<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1a421831a265621325e1fdd19aace0c758>` ()
	:ref:`isValid<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1a7b37efab7473a1effc29f8be2421f6e3>` ()
	:ref:`getLink<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1abea978f4dcd47c4289232744d3ed2f01>` ()
	:ref:`getResource<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1a8c5a689e9be7d35d0d01d0194637a7d2>` ()
	:ref:`control_paged_result_response<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1ab20bf139575478a085e9cc4ce064303b>` (&... $args)
	:ref:`count_entries<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1a962bc34ad5e75b71c28fed144ac1bc32>` ()
	:ref:`first_entry<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1aec052bed5b01aa7e85c038c4b1ce5441>` ()
	:ref:`count_references<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1aae7cbe7c02fd080f13cf8d2144b425f0>` ()
	:ref:`first_reference<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1a449e9dc5565f3855717e1c1279a32325>` ()
	:ref:`free_result<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1aad2d98d6beb3d6095405356c6107b473>` ()
	:ref:`get_entries<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1a365385b1850ee00cf6a838d5bd6222d6>` ()

	:ref:`parse_result<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1af4b1ee172a683de54811b38ba14e114e>` (
	    & $errcode,
	    &... $tail
	    )

	:ref:`sort<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1a2a7965834123afdac3a72083a2a3d9cc>` (string $sortfilter)
	:ref:`getResultEntryIterator<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1ad210d4050fa90483ec499abee6f13423>` ()
	:ref:`getResultReferenceIterator<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1abc5e72211389b0917809ddf2d8b76558>` ()

Inherited Members
-----------------

.. ref-code-block:: cpp
	:class: overview-inherited-code-block

	// methods

	:ref:`getResultEntryIterator<doxid-dd/db9/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_interface_1ad210d4050fa90483ec499abee6f13423>` ()
	:ref:`getResultReferenceIterator<doxid-dd/db9/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_interface_1abc5e72211389b0917809ddf2d8b76558>` ()
	:ref:`getEntries<doxid-dd/db9/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_interface_1a960b179d82797c28ab8ff33184c3eb3b>` (bool $use_keys = true)
	:ref:`getEntries<doxid-d6/dfd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_result_1a960b179d82797c28ab8ff33184c3eb3b>` (bool $use_keys = true)
	:ref:`getIterator<doxid-d6/dfd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_result_1a7a9f937c2958e6f4dd7b030f86fb70b7>` ()

.. _details-doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Wrapper for ldap result resource.

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1ab553f426b8bf9ea8be2afc0765be275a:
.. _cid-korowai::component::ldap::adapter::extldap::result::__construct:
.. ref-code-block:: cpp
	:class: title-code-block

	__construct (
	    $result,
	    :ref:`LdapLink<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link>` $link
	    )

Initializes new ``Result`` instance

It's assumed, that ``$result`` was created by ``$link`` . For example, ``$result`` may be a resource returned from ``\ldap_search($link->getResource(), ...)`` .

resource :ref:`LdapLink <doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link>`



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $result

        - An ldap result resource to be wrapped

    *
        - $link

        - An ldap link object related to the ``$result``

.. _doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1a421831a265621325e1fdd19aace0c758:
.. _cid-korowai::component::ldap::adapter::extldap::result::__destruct:
.. ref-code-block:: cpp
	:class: title-code-block

	__destruct ()

Destructs :ref:`Result <doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>`

.. _doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1a7b37efab7473a1effc29f8be2421f6e3:
.. _cid-korowai::component::ldap::adapter::extldap::result::isvalid:
.. ref-code-block:: cpp
	:class: title-code-block

	isValid ()

Checks whether the :ref:`Result <doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` represents a valid 'ldap result' resource.

.. _doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1abea978f4dcd47c4289232744d3ed2f01:
.. _cid-korowai::component::ldap::adapter::extldap::result::getlink:
.. ref-code-block:: cpp
	:class: title-code-block

	getLink ()

Returns the ``$link`` provided to ``__construct()`` at construction time



.. rubric:: Returns:

:ref:`LdapLink <doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link>` The ``$link`` provided to ``__construct()`` at construction time

.. _doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1a8c5a689e9be7d35d0d01d0194637a7d2:
.. _cid-korowai::component::ldap::adapter::extldap::result::getresource:
.. ref-code-block:: cpp
	:class: title-code-block

	getResource ()

Returns the ``$result`` provided to ``__construct()`` at construction time



.. rubric:: Returns:

resource The ``$result`` provided to ``__construct()`` at construction time

.. _doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1ab20bf139575478a085e9cc4ce064303b:
.. _cid-korowai::component::ldap::adapter::extldap::result::control_paged_result_response:
.. ref-code-block:: cpp
	:class: title-code-block

	control_paged_result_response (&... $args)

Retrieve the LDAP pagination cookie

:ref:`ldap_control_paged_result_response() <doxid->`

.. _doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1a962bc34ad5e75b71c28fed144ac1bc32:
.. _cid-korowai::component::ldap::adapter::extldap::result::count_entries:
.. ref-code-block:: cpp
	:class: title-code-block

	count_entries ()

Count the number of entries in a search

:ref:`ldap_count_entries() <doxid->`

.. _doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1aec052bed5b01aa7e85c038c4b1ce5441:
.. _cid-korowai::component::ldap::adapter::extldap::result::first_entry:
.. ref-code-block:: cpp
	:class: title-code-block

	first_entry ()

Return first result id

:ref:`ldap_first_entry() <doxid->`

.. _doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1aae7cbe7c02fd080f13cf8d2144b425f0:
.. _cid-korowai::component::ldap::adapter::extldap::result::count_references:
.. ref-code-block:: cpp
	:class: title-code-block

	count_references ()

Count the number of references in a search

:ref:`ldap_count_references() <doxid->`

.. _doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1a449e9dc5565f3855717e1c1279a32325:
.. _cid-korowai::component::ldap::adapter::extldap::result::first_reference:
.. ref-code-block:: cpp
	:class: title-code-block

	first_reference ()

Return first reference

:ref:`ldap_first_reference() <doxid->`

.. _doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1aad2d98d6beb3d6095405356c6107b473:
.. _cid-korowai::component::ldap::adapter::extldap::result::free_result:
.. ref-code-block:: cpp
	:class: title-code-block

	free_result ()

Free result memory

:ref:`ldap_free_result() <doxid->`

.. _doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1a365385b1850ee00cf6a838d5bd6222d6:
.. _cid-korowai::component::ldap::adapter::extldap::result::get_entries:
.. ref-code-block:: cpp
	:class: title-code-block

	get_entries ()

Get all result entries

:ref:`ldap_get_entries() <doxid->`

.. _doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1af4b1ee172a683de54811b38ba14e114e:
.. _cid-korowai::component::ldap::adapter::extldap::result::parse_result:
.. ref-code-block:: cpp
	:class: title-code-block

	parse_result (
	    & $errcode,
	    &... $tail
	    )

Extract information from result

:ref:`ldap_parse_result() <doxid->`

.. _doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1a2a7965834123afdac3a72083a2a3d9cc:
.. _cid-korowai::component::ldap::adapter::extldap::result::sort:
.. ref-code-block:: cpp
	:class: title-code-block

	sort (string $sortfilter)

Sort LDAP result entries on the client side

:ref:`ldap_sort() <doxid->`

.. _doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1ad210d4050fa90483ec499abee6f13423:
.. _cid-korowai::component::ldap::adapter::extldap::result::getresultentryiterator:
.. ref-code-block:: cpp
	:class: title-code-block

	getResultEntryIterator ()

{ Get iterator over result's entries

}



.. rubric:: Returns:

:ref:`ResultEntryIteratorInterface <doxid-d2/d10/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_entry_iterator_interface>` The iterator

.. _doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_1abc5e72211389b0917809ddf2d8b76558:
.. _cid-korowai::component::ldap::adapter::extldap::result::getresultreferenceiterator:
.. ref-code-block:: cpp
	:class: title-code-block

	getResultReferenceIterator ()

{ Get iterator over result's references

}



.. rubric:: Returns:

:ref:`ResultReferenceIteratorInterface <doxid-dd/d4f/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_reference_iterator_interface>` The iterator

