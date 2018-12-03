.. index:: pair: class; Korowai::Component::Ldap::Entry
.. _doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry:
.. _cid-korowai::component::ldap::entry:

class Korowai::Component::Ldap::Entry
=====================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Represents single ldap entry with DN and attributes

Paweł Tomulik ptomulik@meil.pw.edu.pl :ref:`More...<details-doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>`

.. ref-code-block:: cpp
	:class: overview-code-block

	// methods

	:ref:`__construct<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry_1aba58f2b9a79fd358105319330030f510>` (
	    string $dn,
	    array $attributes = array ()
	    )

	:ref:`getDn<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry_1afb70ca32ff1f281efeff89dd894d2b0f>` ()
	:ref:`setDn<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry_1a6c1a11601919ff4c75d404ec98970ae7>` (string $dn)
	:ref:`validateDn<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry_1a09f90bcb07b5f2bb3fa558733f0ee957>` (string $dn)
	:ref:`getAttributes<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry_1afbe85ec4b9947cc951c67d63911cf0a4>` ()
	:ref:`getAttribute<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry_1a68eac2277d384c1a9ff316eb14f52daf>` (string $name)
	:ref:`ensureAttributeExists<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry_1add16db3f9174736170a0c7d6303af85b>` (string $name)
	:ref:`hasAttribute<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry_1ae69efdd1408518a5ecaaa2fbfa44ccaa>` (string $name)
	:ref:`setAttributes<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry_1ad4c58c23f94923a1ba4ad37b24a80504>` (array $attributes)
	:ref:`validateAttributes<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry_1aa0ed12b0b4c325208063eff774c8dcdb>` (array $attributes)

	:ref:`setAttribute<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry_1a280f3e5994a0594a182a9df4679685f3>` (
	    string $name,
	    array $values
	    )

	:ref:`validateAttribute<doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry_1ac4b3a8b30a036e5970e0f217fab96aeb>` (
	    string $name,
	    array $values
	    )

.. _details-doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Represents single ldap entry with DN and attributes

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry_1aba58f2b9a79fd358105319330030f510:
.. _cid-korowai::component::ldap::entry::__construct:
.. ref-code-block:: cpp
	:class: title-code-block

	__construct (
	    string $dn,
	    array $attributes = array ()
	    )

:ref:`Entry <doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` 's constructor.

.. _doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry_1afb70ca32ff1f281efeff89dd894d2b0f:
.. _cid-korowai::component::ldap::entry::getdn:
.. ref-code-block:: cpp
	:class: title-code-block

	getDn ()

Retuns the entry's DN.



.. rubric:: Returns:

string

.. _doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry_1a6c1a11601919ff4c75d404ec98970ae7:
.. _cid-korowai::component::ldap::entry::setdn:
.. ref-code-block:: cpp
	:class: title-code-block

	setDn (string $dn)

Sets the entry's DN.

string



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $dn

        -

.. _doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry_1a09f90bcb07b5f2bb3fa558733f0ee957:
.. _cid-korowai::component::ldap::entry::validatedn:
.. ref-code-block:: cpp
	:class: title-code-block

	validateDn (string $dn)

Validates string provided as DN.

string



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $dn

        -

.. _doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry_1afbe85ec4b9947cc951c67d63911cf0a4:
.. _cid-korowai::component::ldap::entry::getattributes:
.. ref-code-block:: cpp
	:class: title-code-block

	getAttributes ()

Returns the complete array of attributes



.. rubric:: Returns:

array

.. _doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry_1a68eac2277d384c1a9ff316eb14f52daf:
.. _cid-korowai::component::ldap::entry::getattribute:
.. ref-code-block:: cpp
	:class: title-code-block

	getAttribute (string $name)

Returns a specific attribute's values

string



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $name

        - 

    *
        - AttributeException

        - 



.. rubric:: Returns:

array

.. _doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry_1add16db3f9174736170a0c7d6303af85b:
.. _cid-korowai::component::ldap::entry::ensureattributeexists:
.. ref-code-block:: cpp
	:class: title-code-block

	ensureAttributeExists (string $name)

Throws AttributeException if given attribute does not exist



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - AttributeException

        -

.. _doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry_1ae69efdd1408518a5ecaaa2fbfa44ccaa:
.. _cid-korowai::component::ldap::entry::hasattribute:
.. ref-code-block:: cpp
	:class: title-code-block

	hasAttribute (string $name)

Retuns whether an attribute exists.



.. rubric:: Returns:

bool

.. _doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry_1ad4c58c23f94923a1ba4ad37b24a80504:
.. _cid-korowai::component::ldap::entry::setattributes:
.. ref-code-block:: cpp
	:class: title-code-block

	setAttributes (array $attributes)

Sets attributes.

For each attribute in $attributes, if attribute already exists in :ref:`Entry <doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` , its values will be replaced with values provided in $attributes. If there is no attribute in :ref:`Entry <doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` , it'll be added to :ref:`Entry <doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` .

array



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $attributes

        - 

    *
        - AttributeException

        -

.. _doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry_1aa0ed12b0b4c325208063eff774c8dcdb:
.. _cid-korowai::component::ldap::entry::validateattributes:
.. ref-code-block:: cpp
	:class: title-code-block

	validateAttributes (array $attributes)

Check if the given array of attributes can be safely assigned to entry.

If not, an exception is thrown.

.. _doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry_1a280f3e5994a0594a182a9df4679685f3:
.. _cid-korowai::component::ldap::entry::setattribute:
.. ref-code-block:: cpp
	:class: title-code-block

	setAttribute (
	    string $name,
	    array $values
	    )

Sets values for the given attribute

string array



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $name

        - 

    *
        - $values

        -

.. _doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry_1ac4b3a8b30a036e5970e0f217fab96aeb:
.. _cid-korowai::component::ldap::entry::validateattribute:
.. ref-code-block:: cpp
	:class: title-code-block

	validateAttribute (
	    string $name,
	    array $values
	    )

Currently only check the types of attribute name and values

