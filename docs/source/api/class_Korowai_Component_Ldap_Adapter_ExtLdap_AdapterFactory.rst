.. index:: pair: class; Korowai::Component::Ldap::Adapter::ExtLdap::AdapterFactory
.. _doxid-db/da8/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_adapter_factory:
.. _cid-korowai::component::ldap::adapter::extldap::adapterfactory:

class Korowai::Component::Ldap::Adapter::ExtLdap::AdapterFactory
================================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl :ref:`More...<details-doxid-db/da8/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_adapter_factory>`

.. ref-code-block:: cpp
	:class: overview-code-block

	class AdapterFactory: public :ref:`Korowai::Component::Ldap::Adapter::AbstractAdapterFactory<doxid-df/d74/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_adapter_factory>`

	// methods

	:ref:`__construct<doxid-db/da8/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_adapter_factory_1af8ba1449b07ddfb7242e8eb1e7078505>` (array $config = null)
	:ref:`createAdapter<doxid-db/da8/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_adapter_factory_1ae99fe9819d0d3a9ac4d5688506148e96>` ()

Inherited Members
-----------------

.. ref-code-block:: cpp
	:class: overview-inherited-code-block

	// methods

	:ref:`configure<doxid-d4/d6c/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_factory_interface_1a0d24f712c26947e67dd748012a918926>` (array $config)
	:ref:`createAdapter<doxid-d4/d6c/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_factory_interface_1ae99fe9819d0d3a9ac4d5688506148e96>` ()
	:ref:`__construct<doxid-df/d74/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_adapter_factory_1af8ba1449b07ddfb7242e8eb1e7078505>` (array $config = null)
	:ref:`configure<doxid-df/d74/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_adapter_factory_1a0d24f712c26947e67dd748012a918926>` (array $config)
	:ref:`getConfig<doxid-df/d74/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_adapter_factory_1a628300eb8464467d9344c7c59cc8770b>` ()

.. _details-doxid-db/da8/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_adapter_factory:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-db/da8/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_adapter_factory_1af8ba1449b07ddfb7242e8eb1e7078505:
.. _cid-korowai::component::ldap::adapter::extldap::adapterfactory::__construct:
.. ref-code-block:: cpp
	:class: title-code-block

	__construct (array $config = null)

Creates instance of :ref:`AdapterFactory <doxid-db/da8/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_adapter_factory>`



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - LdapException

        -

.. _doxid-db/da8/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_adapter_factory_1ae99fe9819d0d3a9ac4d5688506148e96:
.. _cid-korowai::component::ldap::adapter::extldap::adapterfactory::createadapter:
.. ref-code-block:: cpp
	:class: title-code-block

	createAdapter ()

{ Creates and returns an LDAP adapter

The returned adapter is configured with config provided to :ref:`configure() <doxid-d4/d6c/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_factory_interface_1a0d24f712c26947e67dd748012a918926>` . Several instances of :ref:`AdapterInterface <doxid-d8/dca/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_interface>` may be created with same config.

}



.. rubric:: Returns:

:ref:`AdapterInterface <doxid-d8/dca/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_interface>`

