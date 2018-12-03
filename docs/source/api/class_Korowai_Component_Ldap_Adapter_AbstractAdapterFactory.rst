.. index:: pair: class; Korowai::Component::Ldap::Adapter::AbstractAdapterFactory
.. _doxid-df/d74/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_adapter_factory:
.. _cid-korowai::component::ldap::adapter::abstractadapterfactory:

class Korowai::Component::Ldap::Adapter::AbstractAdapterFactory
===============================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Abstract base class for :ref:`Adapter <doxid-db/db7/namespace_korowai_1_1_component_1_1_ldap_1_1_adapter>` factories. :ref:`More...<details-doxid-df/d74/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_adapter_factory>`

.. ref-code-block:: cpp
	:class: overview-code-block

	class AbstractAdapterFactory: public :ref:`Korowai::Component::Ldap::Adapter::AdapterFactoryInterface<doxid-d4/d6c/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_factory_interface>`

	    // direct descendants

	    class :ref:`Korowai::Component::Ldap::Adapter::ExtLdap::AdapterFactory<doxid-db/da8/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_adapter_factory>` 

	// methods

	:ref:`__construct<doxid-df/d74/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_adapter_factory_1af8ba1449b07ddfb7242e8eb1e7078505>` (array $config = null)
	:ref:`configure<doxid-df/d74/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_adapter_factory_1a0d24f712c26947e67dd748012a918926>` (array $config)
	:ref:`getConfig<doxid-df/d74/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_adapter_factory_1a628300eb8464467d9344c7c59cc8770b>` ()

Inherited Members
-----------------

.. ref-code-block:: cpp
	:class: overview-inherited-code-block

	// methods

	:ref:`configure<doxid-d4/d6c/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_factory_interface_1a0d24f712c26947e67dd748012a918926>` (array $config)
	:ref:`createAdapter<doxid-d4/d6c/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_factory_interface_1ae99fe9819d0d3a9ac4d5688506148e96>` ()

.. _details-doxid-df/d74/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_adapter_factory:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Abstract base class for :ref:`Adapter <doxid-db/db7/namespace_korowai_1_1_component_1_1_ldap_1_1_adapter>` factories.

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-df/d74/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_adapter_factory_1af8ba1449b07ddfb7242e8eb1e7078505:
.. _cid-korowai::component::ldap::adapter::abstractadapterfactory::__construct:
.. ref-code-block:: cpp
	:class: title-code-block

	__construct (array $config = null)

Creates an :ref:`AbstractAdapterFactory <doxid-df/d74/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_adapter_factory>`

array



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $config

        - A config to be passed to :ref:`configure() <doxid-df/d74/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_adapter_factory_1a0d24f712c26947e67dd748012a918926>` (if present).

.. _doxid-df/d74/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_adapter_factory_1a0d24f712c26947e67dd748012a918926:
.. _cid-korowai::component::ldap::adapter::abstractadapterfactory::configure:
.. ref-code-block:: cpp
	:class: title-code-block

	configure (array $config)

{ Set configuration for later use by :ref:`createAdapter() <doxid-d4/d6c/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_factory_interface_1ae99fe9819d0d3a9ac4d5688506148e96>` .

array

}



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $config

        - Configuration options used to configure every new adapter instance created by :ref:`createAdapter() <doxid-d4/d6c/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_adapter_factory_interface_1ae99fe9819d0d3a9ac4d5688506148e96>` .

.. _doxid-df/d74/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_adapter_factory_1a628300eb8464467d9344c7c59cc8770b:
.. _cid-korowai::component::ldap::adapter::abstractadapterfactory::getconfig:
.. ref-code-block:: cpp
	:class: title-code-block

	getConfig ()

Return configuration array previously set with :ref:`configure() <doxid-df/d74/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_adapter_factory_1a0d24f712c26947e67dd748012a918926>` .

If configuration is not set yet, null is returned.



.. rubric:: Returns:

array|null

