.. index:: pair: interface; Korowai::Component::Ldap::Adapter::QueryInterface
.. _doxid-d1/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_query_interface:
.. _cid-korowai::component::ldap::adapter::queryinterface:

interface Korowai::Component::Ldap::Adapter::QueryInterface
===========================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl :ref:`More...<details-doxid-d1/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_query_interface>`

.. ref-code-block:: cpp
	:class: overview-code-block

	interface QueryInterface

	    // direct descendants

	    class :ref:`Korowai::Component::Ldap::Adapter::AbstractQuery<doxid-d4/d2e/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_query>` 

	// methods

	:ref:`execute<doxid-d1/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_query_interface_1a1909f4b7f8129c7790cb75de2ffbe1e4>` ()
	:ref:`getResult<doxid-d1/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_query_interface_1ae077eb8a032a325ceb939bfabfa5f472>` ()

.. _details-doxid-d1/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_query_interface:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-d1/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_query_interface_1a1909f4b7f8129c7790cb75de2ffbe1e4:
.. _cid-korowai::component::ldap::adapter::queryinterface::execute:
.. ref-code-block:: cpp
	:class: title-code-block

	execute ()

Executes query and returns result.



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - LdapException

        - 



.. rubric:: Returns:

:ref:`ResultInterface <doxid-dd/db9/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_interface>`

.. _doxid-d1/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_query_interface_1ae077eb8a032a325ceb939bfabfa5f472:
.. _cid-korowai::component::ldap::adapter::queryinterface::getresult:
.. ref-code-block:: cpp
	:class: title-code-block

	getResult ()

Returns the result of last execution of the query, calls :ref:`execute() <doxid-d1/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_query_interface_1a1909f4b7f8129c7790cb75de2ffbe1e4>` if necessary.



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - LdapException

        - 



.. rubric:: Returns:

:ref:`ResultInterface <doxid-dd/db9/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_interface>`

