.. index:: pair: class; Korowai::Component::Ldap::Adapter::ExtLdap::Binding
.. _doxid-de/d91/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_binding:
.. _cid-korowai::component::ldap::adapter::extldap::binding:

class Korowai::Component::Ldap::Adapter::ExtLdap::Binding
=========================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl :ref:`More...<details-doxid-de/d91/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_binding>`

.. _doxid-de/d91/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_binding_1a8faa622cca4ed84d19eb47c5ed2560b0:
.. _cid-korowai::component::ldap::adapter::extldap::binding::setoptionimpl:
.. ref-code-block:: cpp
	:class: overview-code-block

	class Binding: public :ref:`Korowai::Component::Ldap::Adapter::BindingInterface<doxid-dc/d90/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_binding_interface>`

	// methods

	:ref:`__construct<doxid-de/d91/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_binding_1ae513bd043b6a9db423d2fbb6ed205a71>` (:ref:`LdapLink<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link>` $link)
	:ref:`getLink<doxid-de/d91/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_binding_1abea978f4dcd47c4289232744d3ed2f01>` ()
	:ref:`isLinkValid<doxid-de/d91/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_binding_1a9af8a72023e8e78b7721bc4aba6b52dc>` ()
	:ref:`ensureLink<doxid-de/d91/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_binding_1acbdc0d8595fbc243c0c02079f8e707f9>` ()
	:ref:`errno<doxid-de/d91/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_binding_1ab2eeb64cab360a0f09923108b55c9099>` ()
	:ref:`isBound<doxid-de/d91/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_binding_1aa146f780c6982d8fb070a9bd579dacdc>` ()

	:ref:`bind<doxid-de/d91/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_binding_1aa30d268841f3bf5d79b89c2bd2749959>` (
	    string $dn = null,
	    string $password = null
	    )

	:ref:`getOption<doxid-de/d91/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_binding_1a0437f3a1463f1198a497001d3957037e>` (int $option)

	:ref:`setOption<doxid-de/d91/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_binding_1a9b1b28857ecd40b68a7c2df07c905ee6>` (
	    int $option,
	    $value
	    )

	:ref:`unbind<doxid-de/d91/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_binding_1a346fa8b27edda87ed42edbd0323e2b63>` ()

	setOptionImpl (
	    int $option,
	    $value
	    )

Inherited Members
-----------------

.. ref-code-block:: cpp
	:class: overview-inherited-code-block

	// methods

	:ref:`isBound<doxid-dc/d90/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_binding_interface_1aa146f780c6982d8fb070a9bd579dacdc>` ()

	:ref:`bind<doxid-dc/d90/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_binding_interface_1aa30d268841f3bf5d79b89c2bd2749959>` (
	    string $dn = null,
	    string $password = null
	    )

	:ref:`unbind<doxid-dc/d90/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_binding_interface_1a346fa8b27edda87ed42edbd0323e2b63>` ()

.. _details-doxid-de/d91/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_binding:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-de/d91/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_binding_1ae513bd043b6a9db423d2fbb6ed205a71:
.. _cid-korowai::component::ldap::adapter::extldap::binding::__construct:
.. ref-code-block:: cpp
	:class: title-code-block

	__construct (:ref:`LdapLink<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link>` $link)

Initializes the :ref:`Binding <doxid-de/d91/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_binding>` object with :ref:`LdapLink <doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link>` instance.

:ref:`LdapLink <doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link>`



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $link

        -

.. _doxid-de/d91/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_binding_1abea978f4dcd47c4289232744d3ed2f01:
.. _cid-korowai::component::ldap::adapter::extldap::binding::getlink:
.. ref-code-block:: cpp
	:class: title-code-block

	getLink ()

Returns a link resource.



.. rubric:: Returns:

resource

.. _doxid-de/d91/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_binding_1a9af8a72023e8e78b7721bc4aba6b52dc:
.. _cid-korowai::component::ldap::adapter::extldap::binding::islinkvalid:
.. ref-code-block:: cpp
	:class: title-code-block

	isLinkValid ()

Same as :ref:`getLink() <doxid-de/d91/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_binding_1abea978f4dcd47c4289232744d3ed2f01>` ->isValid();

.. _doxid-de/d91/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_binding_1acbdc0d8595fbc243c0c02079f8e707f9:
.. _cid-korowai::component::ldap::adapter::extldap::binding::ensurelink:
.. ref-code-block:: cpp
	:class: title-code-block

	ensureLink ()

Ensures that the link is initialised. If not, throws an exception.



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - LdapException

        -

.. _doxid-de/d91/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_binding_1ab2eeb64cab360a0f09923108b55c9099:
.. _cid-korowai::component::ldap::adapter::extldap::binding::errno:
.. ref-code-block:: cpp
	:class: title-code-block

	errno ()

If the link is valid, returns last error code related to link. Otherwise, returns -1.



.. rubric:: Returns:

int

.. _doxid-de/d91/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_binding_1aa146f780c6982d8fb070a9bd579dacdc:
.. _cid-korowai::component::ldap::adapter::extldap::binding::isbound:
.. ref-code-block:: cpp
	:class: title-code-block

	isBound ()

{ Check whether the connection was already bound or not.

}



.. rubric:: Returns:

bool

.. _doxid-de/d91/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_binding_1aa30d268841f3bf5d79b89c2bd2749959:
.. _cid-korowai::component::ldap::adapter::extldap::binding::bind:
.. ref-code-block:: cpp
	:class: title-code-block

	bind (
	    string $dn = null,
	    string $password = null
	    )

{ Binds the connection against a DN and password

string string

}



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $dn

        - The user's DN

    *
        - $password

        - The associated password

.. _doxid-de/d91/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_binding_1a0437f3a1463f1198a497001d3957037e:
.. _cid-korowai::component::ldap::adapter::extldap::binding::getoption:
.. ref-code-block:: cpp
	:class: title-code-block

	getOption (int $option)

Get LDAP option's value (as per ldap_get_option())

int



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $option

        - Option identifier (name)

    *
        - LdapException

        - 



.. rubric:: Returns:

mixed Option value

.. _doxid-de/d91/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_binding_1a9b1b28857ecd40b68a7c2df07c905ee6:
.. _cid-korowai::component::ldap::adapter::extldap::binding::setoption:
.. ref-code-block:: cpp
	:class: title-code-block

	setOption (
	    int $option,
	    $value
	    )

Set value to LDAP option

int mixed



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $option

        - Option identifier (name)

    *
        - $value

        - New value

    *
        - LdapException

        -

.. _doxid-de/d91/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_binding_1a346fa8b27edda87ed42edbd0323e2b63:
.. _cid-korowai::component::ldap::adapter::extldap::binding::unbind:
.. ref-code-block:: cpp
	:class: title-code-block

	unbind ()

Unbinds the link

After unbind the connection is no longer valid (and useful)



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - LdapException

        -

