.. index:: pair: class; Korowai::Component::Ldap::Adapter::AbstractQuery
.. _doxid-d4/d2e/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_query:
.. _cid-korowai::component::ldap::adapter::abstractquery:

class Korowai::Component::Ldap::Adapter::AbstractQuery
======================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl :ref:`More...<details-doxid-d4/d2e/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_query>`

.. ref-code-block:: cpp
	:class: overview-code-block

	class AbstractQuery: public :ref:`Korowai::Component::Ldap::Adapter::QueryInterface<doxid-d1/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_query_interface>`

	    // direct descendants

	    class :ref:`Korowai::Component::Ldap::Adapter::ExtLdap::Query<doxid-d2/d9d/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_query>` 

	// methods

	:ref:`__construct<doxid-d4/d2e/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_query_1a668d52797aee90a20b1380d64983f24b>` (
	    string $base_dn,
	    string $filter,
	    array $options = array ()
	    )

	:ref:`getBaseDn<doxid-d4/d2e/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_query_1a9cece4593d7c30ec17181f1733fe4d98>` ()
	:ref:`getFilter<doxid-d4/d2e/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_query_1a2ce3342ac043ecadecff91b1321445f4>` ()
	:ref:`getOptions<doxid-d4/d2e/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_query_1a1a49b8dded6e91a52e2fd07195d334da>` ()
	:ref:`getResult<doxid-d4/d2e/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_query_1ae077eb8a032a325ceb939bfabfa5f472>` ()
	:ref:`execute<doxid-d4/d2e/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_query_1a1909f4b7f8129c7790cb75de2ffbe1e4>` ()
	static static :ref:`getDefaultOptions<doxid-d4/d2e/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_query_1a4c07c427694681161fb52687c4db0bb5>` ()

Inherited Members
-----------------

.. ref-code-block:: cpp
	:class: overview-inherited-code-block

	// methods

	:ref:`execute<doxid-d1/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_query_interface_1a1909f4b7f8129c7790cb75de2ffbe1e4>` ()
	:ref:`getResult<doxid-d1/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_query_interface_1ae077eb8a032a325ceb939bfabfa5f472>` ()

.. _details-doxid-d4/d2e/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_query:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-d4/d2e/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_query_1a668d52797aee90a20b1380d64983f24b:
.. _cid-korowai::component::ldap::adapter::abstractquery::__construct:
.. ref-code-block:: cpp
	:class: title-code-block

	__construct (
	    string $base_dn,
	    string $filter,
	    array $options = array ()
	    )

Constructs :ref:`AbstractQuery <doxid-d4/d2e/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_query>`

string string array



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $base_dn

        - 

    *
        - $filter

        - 

    *
        - $options

        -

.. _doxid-d4/d2e/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_query_1a9cece4593d7c30ec17181f1733fe4d98:
.. _cid-korowai::component::ldap::adapter::abstractquery::getbasedn:
.. ref-code-block:: cpp
	:class: title-code-block

	getBaseDn ()

Returns ``$base_dn`` provided to ``__construct()`` at creation time



.. rubric:: Returns:

string The ``$base_dn`` value provided to ``__construct()``

.. _doxid-d4/d2e/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_query_1a2ce3342ac043ecadecff91b1321445f4:
.. _cid-korowai::component::ldap::adapter::abstractquery::getfilter:
.. ref-code-block:: cpp
	:class: title-code-block

	getFilter ()

Returns ``$filter`` provided to ``__construct()`` at creation time



.. rubric:: Returns:

string The ``$filter`` value provided to ``__construct()``

.. _doxid-d4/d2e/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_query_1a1a49b8dded6e91a52e2fd07195d334da:
.. _cid-korowai::component::ldap::adapter::abstractquery::getoptions:
.. ref-code-block:: cpp
	:class: title-code-block

	getOptions ()

Get the options used by this query.

The returned array contains ``$options`` provided to ``__construct()`` , but also includes defaults applied internally by this object.



.. rubric:: Returns:

array Options used by this query

.. _doxid-d4/d2e/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_query_1ae077eb8a032a325ceb939bfabfa5f472:
.. _cid-korowai::component::ldap::adapter::abstractquery::getresult:
.. ref-code-block:: cpp
	:class: title-code-block

	getResult ()

{ Returns the result of last execution of the query, calls :ref:`execute() <doxid-d1/d88/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_query_interface_1a1909f4b7f8129c7790cb75de2ffbe1e4>` if necessary.

}



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - LdapException

        - 



.. rubric:: Returns:

:ref:`ResultInterface <doxid-dd/db9/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_interface>`

.. _doxid-d4/d2e/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_query_1a1909f4b7f8129c7790cb75de2ffbe1e4:
.. _cid-korowai::component::ldap::adapter::abstractquery::execute:
.. ref-code-block:: cpp
	:class: title-code-block

	execute ()

{ Executes query and returns result.

}



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - LdapException

        - 



.. rubric:: Returns:

:ref:`ResultInterface <doxid-dd/db9/interface_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_result_interface>`

.. _doxid-d4/d2e/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_abstract_query_1a4c07c427694681161fb52687c4db0bb5:
.. _cid-korowai::component::ldap::adapter::abstractquery::getdefaultoptions:
.. ref-code-block:: cpp
	:class: title-code-block

	static static getDefaultOptions ()

Returns defaults for query options



.. rubric:: Returns:

array Default options

