.. index:: pair: class; Korowai::Component::Ldap::Tests::Adapter::AbstractQueryTest
.. _doxid-d1/d5a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_abstract_query_test:
.. _cid-korowai::component::ldap::tests::adapter::abstractquerytest:

class Korowai::Component::Ldap::Tests::Adapter::AbstractQueryTest
=================================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl :ref:`More...<details-doxid-d1/d5a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_abstract_query_test>`

.. _doxid-d1/d5a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_abstract_query_test_1a4c07c427694681161fb52687c4db0bb5:
.. _cid-korowai::component::ldap::tests::adapter::abstractquerytest::getdefaultoptions:
.. _doxid-d1/d5a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_abstract_query_test_1a8f0fc9e2e6023526572b48f793d70fe6:
.. _cid-korowai::component::ldap::tests::adapter::abstractquerytest::getdefaultoptionsresolved:
.. _doxid-d1/d5a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_abstract_query_test_1a850d588552b416349d2f05282ed03214:
.. _cid-korowai::component::ldap::tests::adapter::abstractquerytest::test_getdefaultoptions:
.. _doxid-d1/d5a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_abstract_query_test_1a4bf6ef2843ba1df1c70d7b2f4bb73e1e:
.. _cid-korowai::component::ldap::tests::adapter::abstractquerytest::test_defaultoptions:
.. _doxid-d1/d5a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_abstract_query_test_1a66d1126ec03f12d7e2a4255779c14a1c:
.. _cid-korowai::component::ldap::tests::adapter::abstractquerytest::test_scope:
.. _doxid-d1/d5a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_abstract_query_test_1ae9b2a1e25bcf3a0546e7f20f2208ee0d:
.. _cid-korowai::component::ldap::tests::adapter::abstractquerytest::test_deref:
.. _doxid-d1/d5a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_abstract_query_test_1a11c5f375575abff67a8d41b08e94deb5:
.. _cid-korowai::component::ldap::tests::adapter::abstractquerytest::test_attributes:
.. _doxid-d1/d5a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_abstract_query_test_1aa4678a9d07966242cd47e597a9ea1d17:
.. _cid-korowai::component::ldap::tests::adapter::abstractquerytest::test_getbasedn:
.. _doxid-d1/d5a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_abstract_query_test_1a58d57b06ca6091c79ba2cdf34c278465:
.. _cid-korowai::component::ldap::tests::adapter::abstractquerytest::test_getfilter:
.. _doxid-d1/d5a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_abstract_query_test_1a60f1abe4c89caa3ece42ececaf14efbb:
.. _cid-korowai::component::ldap::tests::adapter::abstractquerytest::test_getresult:
.. _doxid-d1/d5a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_abstract_query_test_1a1127765c95d3f5e5e70236118caa0a8d:
.. _cid-korowai::component::ldap::tests::adapter::abstractquerytest::test_execute:
.. ref-code-block:: cpp
	:class: overview-code-block

	class AbstractQueryTest: public TestCase

	// methods

	static static getDefaultOptions ()
	static static getDefaultOptionsResolved ()
	test_getDefaultOptions ()
	test_defaultOptions ()
	test_scope ()
	:ref:`test_scope_Invalid<doxid-d1/d5a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_abstract_query_test_1a6843b80f743fc4861b852fd78012e74d>` ()
	test_deref ()
	:ref:`test_deref_Invalid<doxid-d1/d5a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_abstract_query_test_1aba890dd515d6ed07a98384ddfdba2aec>` ()
	test_attributes ()
	test_getBaseDn ()
	test_getFilter ()
	test_getResult ()
	test_execute ()

.. _details-doxid-d1/d5a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_abstract_query_test:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-d1/d5a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_abstract_query_test_1a6843b80f743fc4861b852fd78012e74d:
.. _cid-korowai::component::ldap::tests::adapter::abstractquerytest::test_scope_invalid:
.. ref-code-block:: cpp
	:class: title-code-block

	test_scope_Invalid ()

The option "scope" with value "foo" is invalid.

.. _doxid-d1/d5a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_abstract_query_test_1aba890dd515d6ed07a98384ddfdba2aec:
.. _cid-korowai::component::ldap::tests::adapter::abstractquerytest::test_deref_invalid:
.. ref-code-block:: cpp
	:class: title-code-block

	test_deref_Invalid ()

The option "deref" with value "foo" is invalid.

