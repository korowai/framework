.. index:: pair: class; Korowai::Component::Ldap::Tests::Adapter::ExtLdap::QueryTest
.. _doxid-de/d1a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_query_test:
.. _cid-korowai::component::ldap::tests::adapter::extldap::querytest:

class Korowai::Component::Ldap::Tests::Adapter::ExtLdap::QueryTest
==================================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl :ref:`More...<details-doxid-de/d1a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_query_test>`

.. _doxid-de/d1a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_query_test_1a1f1481a472445e9fe5e6cbfbdfd3e6aa:
.. _cid-korowai::component::ldap::tests::adapter::extldap::querytest::getldapfunctionmock:
.. _doxid-de/d1a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_query_test_1a2adaa0b1c9b4e54d182428a725ad23aa:
.. _cid-korowai::component::ldap::tests::adapter::extldap::querytest::createldaplinkmock:
.. _doxid-de/d1a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_query_test_1ae75f4e0c3451d7d4c35c4815ef17c833:
.. _cid-korowai::component::ldap::tests::adapter::extldap::querytest::test_construct:
.. _doxid-de/d1a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_query_test_1a19e4997035207446954f87f4a6c930f7:
.. _cid-korowai::component::ldap::tests::adapter::extldap::querytest::test_getlink:
.. _doxid-de/d1a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_query_test_1ae46c6d110711341c2475540da0a2a21e:
.. _cid-korowai::component::ldap::tests::adapter::extldap::querytest::test_execute_base:
.. _doxid-de/d1a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_query_test_1a0fcda649fbdd4f4d7d656d4c6a57232b:
.. _cid-korowai::component::ldap::tests::adapter::extldap::querytest::test_execute_one:
.. _doxid-de/d1a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_query_test_1a63b33a75e0694a0eb0ea88a78f7ac5d3:
.. _cid-korowai::component::ldap::tests::adapter::extldap::querytest::test_execute_sub:
.. _doxid-de/d1a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_query_test_1aa040925bd97a5137e6fb268d75c6e9a3:
.. _cid-korowai::component::ldap::tests::adapter::extldap::querytest::test_getresult_base:
.. _doxid-de/d1a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_query_test_1a5c00e2d1882bdc9be213bffda97e1331:
.. _cid-korowai::component::ldap::tests::adapter::extldap::querytest::test_getresult_one:
.. _doxid-de/d1a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_query_test_1a1fec10ddb86da4130528300d591cea60:
.. _cid-korowai::component::ldap::tests::adapter::extldap::querytest::test_getresult_sub:
.. ref-code-block:: cpp
	:class: overview-code-block

	class QueryTest: public TestCase

	// methods

	getLdapFunctionMock (... $args)

	createLdapLinkMock (
	    $valid,
	    $unbind = true
	    )

	test_construct ()
	test_getLink ()
	test_execute_base ()
	test_execute_one ()
	test_execute_sub ()
	:ref:`test_execute_UninitializedLink<doxid-de/d1a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_query_test_1a6fa074b66e37c3956c4098a198def937>` ()
	:ref:`test_execute_Failure<doxid-de/d1a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_query_test_1ad61ef51a5c97a6bc684a6f0fec0da0bb>` ()
	test_getResult_base ()
	test_getResult_one ()
	test_getResult_sub ()

.. _details-doxid-de/d1a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_query_test:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-de/d1a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_query_test_1a6fa074b66e37c3956c4098a198def937:
.. _cid-korowai::component::ldap::tests::adapter::extldap::querytest::test_execute_uninitializedlink:
.. ref-code-block:: cpp
	:class: title-code-block

	test_execute_UninitializedLink ()

-1  Uninitialized LDAP link

.. _doxid-de/d1a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_query_test_1ad61ef51a5c97a6bc684a6f0fec0da0bb:
.. _cid-korowai::component::ldap::tests::adapter::extldap::querytest::test_execute_failure:
.. ref-code-block:: cpp
	:class: title-code-block

	test_execute_Failure ()

2  Error message

