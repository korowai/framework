.. index:: pair: class; Korowai::Component::Ldap::Tests::Adapter::ExtLdap::EntryManagerTest
.. _doxid-dc/dd6/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_test:
.. _cid-korowai::component::ldap::tests::adapter::extldap::entrymanagertest:

class Korowai::Component::Ldap::Tests::Adapter::ExtLdap::EntryManagerTest
=========================================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl :ref:`More...<details-doxid-dc/dd6/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_test>`

.. _doxid-dc/dd6/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_test_1a1f1481a472445e9fe5e6cbfbdfd3e6aa:
.. _cid-korowai::component::ldap::tests::adapter::extldap::entrymanagertest::getldapfunctionmock:
.. _doxid-dc/dd6/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_test_1a2adaa0b1c9b4e54d182428a725ad23aa:
.. _cid-korowai::component::ldap::tests::adapter::extldap::entrymanagertest::createldaplinkmock:
.. _doxid-dc/dd6/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_test_1ae75f4e0c3451d7d4c35c4815ef17c833:
.. _cid-korowai::component::ldap::tests::adapter::extldap::entrymanagertest::test_construct:
.. _doxid-dc/dd6/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_test_1a19e4997035207446954f87f4a6c930f7:
.. _cid-korowai::component::ldap::tests::adapter::extldap::entrymanagertest::test_getlink:
.. _doxid-dc/dd6/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_test_1aec128805f97ee65c26d91281cca7982f:
.. _cid-korowai::component::ldap::tests::adapter::extldap::entrymanagertest::test_add:
.. _doxid-dc/dd6/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_test_1a6338e7909ad42420446ac35098086747:
.. _cid-korowai::component::ldap::tests::adapter::extldap::entrymanagertest::test_update:
.. _doxid-dc/dd6/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_test_1a8467b05d11436fe464de03602d4ad465:
.. _cid-korowai::component::ldap::tests::adapter::extldap::entrymanagertest::test_rename_default:
.. _doxid-dc/dd6/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_test_1a956002a8c40ed03e652e56145fa559d4:
.. _cid-korowai::component::ldap::tests::adapter::extldap::entrymanagertest::test_rename_deleteoldrdn:
.. _doxid-dc/dd6/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_test_1a1d522d810caf5730eb53a09799fb2ff9:
.. _cid-korowai::component::ldap::tests::adapter::extldap::entrymanagertest::test_rename_leaveoldrdn:
.. _doxid-dc/dd6/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_test_1a0c2d215a888dbb988d08d270711e0d21:
.. _cid-korowai::component::ldap::tests::adapter::extldap::entrymanagertest::test_delete:
.. ref-code-block:: cpp
	:class: overview-code-block

	class EntryManagerTest: public TestCase

	// methods

	getLdapFunctionMock (... $args)

	createLdapLinkMock (
	    $valid,
	    $unbind = true
	    )

	test_construct ()
	test_getLink ()
	test_add ()
	:ref:`test_add_UninitializedLink<doxid-dc/dd6/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_test_1a22cb8c46be3f201057a77f24efc99147>` ()
	:ref:`test_add_Failure<doxid-dc/dd6/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_test_1a8d063afcd18664f968f48259c48bb9d0>` ()
	test_update ()
	:ref:`test_update_Invalid<doxid-dc/dd6/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_test_1aec7c51919efd6bc3fada7964f981b6e4>` ()
	:ref:`test_update_Failure<doxid-dc/dd6/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_test_1a523a5eb5b6fb16e204c2bb42eb71eebe>` ()
	test_rename_Default ()
	test_rename_DeleteOldRdn ()
	test_rename_LeaveOldRdn ()
	:ref:`test_rename_Invalid<doxid-dc/dd6/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_test_1ac05abf5df9b6705c0fbacb845ab1d422>` ()
	:ref:`test_rename_Failure<doxid-dc/dd6/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_test_1ae16b89e58dd840c531ec942a2b727ef2>` ()
	test_delete ()
	:ref:`test_delete_Invalid<doxid-dc/dd6/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_test_1a4307bc7546d5590bfc1c26190b744600>` ()
	:ref:`test_delete_Failure<doxid-dc/dd6/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_test_1abc00a3f2b05a05dca95f3773040d658b>` ()

.. _details-doxid-dc/dd6/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_test:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-dc/dd6/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_test_1a22cb8c46be3f201057a77f24efc99147:
.. _cid-korowai::component::ldap::tests::adapter::extldap::entrymanagertest::test_add_uninitializedlink:
.. ref-code-block:: cpp
	:class: title-code-block

	test_add_UninitializedLink ()

-1  Uninitialized LDAP link

.. _doxid-dc/dd6/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_test_1a8d063afcd18664f968f48259c48bb9d0:
.. _cid-korowai::component::ldap::tests::adapter::extldap::entrymanagertest::test_add_failure:
.. ref-code-block:: cpp
	:class: title-code-block

	test_add_Failure ()

2  Error message

.. _doxid-dc/dd6/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_test_1aec7c51919efd6bc3fada7964f981b6e4:
.. _cid-korowai::component::ldap::tests::adapter::extldap::entrymanagertest::test_update_invalid:
.. ref-code-block:: cpp
	:class: title-code-block

	test_update_Invalid ()

-1  Uninitialized LDAP link

.. _doxid-dc/dd6/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_test_1a523a5eb5b6fb16e204c2bb42eb71eebe:
.. _cid-korowai::component::ldap::tests::adapter::extldap::entrymanagertest::test_update_failure:
.. ref-code-block:: cpp
	:class: title-code-block

	test_update_Failure ()

2  Error message

.. _doxid-dc/dd6/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_test_1ac05abf5df9b6705c0fbacb845ab1d422:
.. _cid-korowai::component::ldap::tests::adapter::extldap::entrymanagertest::test_rename_invalid:
.. ref-code-block:: cpp
	:class: title-code-block

	test_rename_Invalid ()

-1  Uninitialized LDAP link

.. _doxid-dc/dd6/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_test_1ae16b89e58dd840c531ec942a2b727ef2:
.. _cid-korowai::component::ldap::tests::adapter::extldap::entrymanagertest::test_rename_failure:
.. ref-code-block:: cpp
	:class: title-code-block

	test_rename_Failure ()

2  Error message

.. _doxid-dc/dd6/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_test_1a4307bc7546d5590bfc1c26190b744600:
.. _cid-korowai::component::ldap::tests::adapter::extldap::entrymanagertest::test_delete_invalid:
.. ref-code-block:: cpp
	:class: title-code-block

	test_delete_Invalid ()

-1  Uninitialized LDAP link

.. _doxid-dc/dd6/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_entry_manager_test_1abc00a3f2b05a05dca95f3773040d658b:
.. _cid-korowai::component::ldap::tests::adapter::extldap::entrymanagertest::test_delete_failure:
.. ref-code-block:: cpp
	:class: title-code-block

	test_delete_Failure ()

2  Error message

