.. index:: pair: interface; Korowai::Component::Ldap::Adapter::AdapterFactoryInterface
.. _doxid-d4/d6c/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_factory_interface:
.. _cid-korowai::component::ldap::adapter::adapterfactoryinterface:

interface Korowai::Component::Ldap::Adapter::AdapterFactoryInterface
====================================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Creates instances of :ref:`AdapterInterface <doxid-d8/dca/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_interface>`

Paweł Tomulik ptomulik@meil.pw.edu.pl :ref:`More...<details-doxid-d4/d6c/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_factory_interface>`

.. ref-code-block:: cpp
	:class: overview-code-block

	interface AdapterFactoryInterface

	    // direct descendants

	    class :ref:`Korowai::Component::Ldap::Adapter::AbstractAdapterFactory<doxid-df/d74/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_adapter_factory>` 

	// methods

	:ref:`configure<doxid-d4/d6c/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_factory_interface_1a0d24f712c26947e67dd748012a918926>` (array $config)
	:ref:`createAdapter<doxid-d4/d6c/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_factory_interface_1ae99fe9819d0d3a9ac4d5688506148e96>` ()

.. _details-doxid-d4/d6c/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_factory_interface:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Creates instances of :ref:`AdapterInterface <doxid-d8/dca/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_interface>`

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-d4/d6c/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_factory_interface_1a0d24f712c26947e67dd748012a918926:
.. _cid-korowai::component::ldap::adapter::adapterfactoryinterface::configure:
.. ref-code-block:: cpp
	:class: title-code-block

	configure (array $config)

Set configuration for later use by :ref:`createAdapter() <doxid-d4/d6c/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_factory_interface_1ae99fe9819d0d3a9ac4d5688506148e96>` .

array



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $config

        - Configuration options used to configure every new adapter instance created by :ref:`createAdapter() <doxid-d4/d6c/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_factory_interface_1ae99fe9819d0d3a9ac4d5688506148e96>` .

.. _doxid-d4/d6c/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_factory_interface_1ae99fe9819d0d3a9ac4d5688506148e96:
.. _cid-korowai::component::ldap::adapter::adapterfactoryinterface::createadapter:
.. ref-code-block:: cpp
	:class: title-code-block

	createAdapter ()

Creates and returns an LDAP adapter

The returned adapter is configured with config provided to :ref:`configure() <doxid-d4/d6c/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_factory_interface_1a0d24f712c26947e67dd748012a918926>` . Several instances of :ref:`AdapterInterface <doxid-d8/dca/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_interface>` may be created with same config.



.. rubric:: Returns:

:ref:`AdapterInterface <doxid-d8/dca/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_interface>`

