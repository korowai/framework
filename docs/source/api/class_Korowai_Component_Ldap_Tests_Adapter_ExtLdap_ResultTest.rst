.. index:: pair: class; Korowai::Component::Ldap::Tests::Adapter::ExtLdap::ResultTest
.. _doxid-dc/d5c/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_result_test:
.. _cid-korowai::component::ldap::tests::adapter::extldap::resulttest:

class Korowai::Component::Ldap::Tests::Adapter::ExtLdap::ResultTest
===================================================================

.. toctree::
	:hidden:



Paweł Tomulik ptomulik@meil.pw.edu.pl

.. _doxid-dc/d5c/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_result_test_1a1f1481a472445e9fe5e6cbfbdfd3e6aa:
.. _cid-korowai::component::ldap::tests::adapter::extldap::resulttest::getldapfunctionmock:
.. _doxid-dc/d5c/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_result_test_1ac47aa67219890482cbb8e631f9e4fdcb:
.. _cid-korowai::component::ldap::tests::adapter::extldap::resulttest::test_getresource:
.. _doxid-dc/d5c/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_result_test_1a19e4997035207446954f87f4a6c930f7:
.. _cid-korowai::component::ldap::tests::adapter::extldap::resulttest::test_getlink:
.. _doxid-dc/d5c/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_result_test_1a5b9f5101ab6bb5fe9228e9104200efa0:
.. _cid-korowai::component::ldap::tests::adapter::extldap::resulttest::test_control_paged_result_response:
.. _doxid-dc/d5c/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_result_test_1a210eabfdfdaa4d307a490bae6dc3a39e:
.. _cid-korowai::component::ldap::tests::adapter::extldap::resulttest::test_count_entries:
.. _doxid-dc/d5c/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_result_test_1a4eca9f0d05f60e56667f5e534d0aedc4:
.. _cid-korowai::component::ldap::tests::adapter::extldap::resulttest::test_first_entry:
.. _doxid-dc/d5c/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_result_test_1ac8b6c3e104937cccc24b84fdc30cb837:
.. _cid-korowai::component::ldap::tests::adapter::extldap::resulttest::test_first_reference:
.. _doxid-dc/d5c/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_result_test_1a764cc5aa87872833341441cdbf41773f:
.. _cid-korowai::component::ldap::tests::adapter::extldap::resulttest::test_free_result:
.. _doxid-dc/d5c/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_result_test_1aa56c8f1a943322c0d2d90c68002e983d:
.. _cid-korowai::component::ldap::tests::adapter::extldap::resulttest::test_get_entries:
.. _doxid-dc/d5c/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_result_test_1a7b010478b1058bde4fd62da58ff702f5:
.. _cid-korowai::component::ldap::tests::adapter::extldap::resulttest::test_parse_result:
.. _doxid-dc/d5c/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_result_test_1a02542a2d62918fd2ee041987bf7b4c44:
.. _cid-korowai::component::ldap::tests::adapter::extldap::resulttest::test_sort:
.. _doxid-dc/d5c/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_result_test_1a644da233a250c62088770388fc91215d:
.. _cid-korowai::component::ldap::tests::adapter::extldap::resulttest::test_getresultentryiterator:
.. _doxid-dc/d5c/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_result_test_1af74359947982643c4088dd855dda0e63:
.. _cid-korowai::component::ldap::tests::adapter::extldap::resulttest::test_getresultentryiterator_nullfirstentry:
.. _doxid-dc/d5c/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_result_test_1a18a04bbf9fba5f9eae742179c535508e:
.. _cid-korowai::component::ldap::tests::adapter::extldap::resulttest::test_getresultreferenceiterator:
.. _doxid-dc/d5c/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_result_test_1a4ea4a8df39edd4ab01cdc423204c5fe7:
.. _cid-korowai::component::ldap::tests::adapter::extldap::resulttest::test_getresultreferenceiterator_nullfirstreference:
.. _doxid-dc/d5c/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_result_test_1ad9a18443f46f02783b8e9a368cd84bdb:
.. _cid-korowai::component::ldap::tests::adapter::extldap::resulttest::test_destruct_invalid:
.. _doxid-dc/d5c/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_result_test_1a2e062069220e1ac329324193fdfd4ad8:
.. _cid-korowai::component::ldap::tests::adapter::extldap::resulttest::test_destruct_valid:
.. ref-code-block:: cpp
	:class: overview-code-block

	class ResultTest: public TestCase

	// methods

	getLdapFunctionMock (... $args)
	test_getResource ()
	test_getLink ()
	test_control_paged_result_response ()
	test_count_entries ()
	test_first_entry ()
	test_first_reference ()
	test_free_result ()
	test_get_entries ()
	test_parse_result ()
	test_sort ()
	test_getResultEntryIterator ()
	test_getResultEntryIterator_NullFirstEntry ()
	test_getResultReferenceIterator ()
	test_getResultReferenceIterator_NullFirstReference ()
	test_destruct_Invalid ()
	test_destruct_Valid ()

