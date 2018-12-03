.. index:: pair: interface; Korowai::Component::Ldap::Adapter::BindingInterface
.. _doxid-dc/d90/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_binding_interface:
.. _cid-korowai::component::ldap::adapter::bindinginterface:

interface Korowai::Component::Ldap::Adapter::BindingInterface
=============================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Represents and changes bind state of an ldap link. :ref:`More...<details-doxid-dc/d90/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_binding_interface>`

.. ref-code-block:: cpp
	:class: overview-code-block

	interface BindingInterface

	    // direct descendants

	    class :ref:`Korowai::Component::Ldap::Adapter::ExtLdap::Binding<doxid-de/d91/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_binding>` 
	    interface :ref:`Korowai::Component::Ldap::LdapInterface<doxid-df/d00/interface_korowai_1_1_component_1_1_ldap_1_1_ldap_interface>` 

	// methods

	:ref:`isBound<doxid-dc/d90/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_binding_interface_1aa146f780c6982d8fb070a9bd579dacdc>` ()

	:ref:`bind<doxid-dc/d90/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_binding_interface_1aa30d268841f3bf5d79b89c2bd2749959>` (
	    string $dn = null,
	    string $password = null
	    )

	:ref:`unbind<doxid-dc/d90/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_binding_interface_1a346fa8b27edda87ed42edbd0323e2b63>` ()

.. _details-doxid-dc/d90/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_binding_interface:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Represents and changes bind state of an ldap link.

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-dc/d90/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_binding_interface_1aa146f780c6982d8fb070a9bd579dacdc:
.. _cid-korowai::component::ldap::adapter::bindinginterface::isbound:
.. ref-code-block:: cpp
	:class: title-code-block

	isBound ()

Check whether the connection was already bound or not.



.. rubric:: Returns:

bool

.. _doxid-dc/d90/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_binding_interface_1aa30d268841f3bf5d79b89c2bd2749959:
.. _cid-korowai::component::ldap::adapter::bindinginterface::bind:
.. ref-code-block:: cpp
	:class: title-code-block

	bind (
	    string $dn = null,
	    string $password = null
	    )

Binds the connection against a DN and password

string string



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $dn

        - The user's DN

    *
        - $password

        - The associated password

.. _doxid-dc/d90/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_binding_interface_1a346fa8b27edda87ed42edbd0323e2b63:
.. _cid-korowai::component::ldap::adapter::bindinginterface::unbind:
.. ref-code-block:: cpp
	:class: title-code-block

	unbind ()

Unbinds the connection

