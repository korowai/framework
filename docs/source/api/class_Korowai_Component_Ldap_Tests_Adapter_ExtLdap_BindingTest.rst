.. index:: pair: class; Korowai::Component::Ldap::Tests::Adapter::ExtLdap::BindingTest
.. _doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test:
.. _cid-korowai::component::ldap::tests::adapter::extldap::bindingtest:

class Korowai::Component::Ldap::Tests::Adapter::ExtLdap::BindingTest
====================================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl :ref:`More...<details-doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test>`

.. _doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1a1f1481a472445e9fe5e6cbfbdfd3e6aa:
.. _cid-korowai::component::ldap::tests::adapter::extldap::bindingtest::getldapfunctionmock:
.. _doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1a2adaa0b1c9b4e54d182428a725ad23aa:
.. _cid-korowai::component::ldap::tests::adapter::extldap::bindingtest::createldaplinkmock:
.. _doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1ae75f4e0c3451d7d4c35c4815ef17c833:
.. _cid-korowai::component::ldap::tests::adapter::extldap::bindingtest::test_construct:
.. _doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1a19e4997035207446954f87f4a6c930f7:
.. _cid-korowai::component::ldap::tests::adapter::extldap::bindingtest::test_getlink:
.. _doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1a6985cd5e3ac24a2ed0bd327e1e2af74e:
.. _cid-korowai::component::ldap::tests::adapter::extldap::bindingtest::test_isbound_unbound:
.. _doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1a6a1350535899ee1c380244d308d95812:
.. _cid-korowai::component::ldap::tests::adapter::extldap::bindingtest::test_errno_1:
.. _doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1ad4b7e7f06d8789429706a39684d96c3a:
.. _cid-korowai::component::ldap::tests::adapter::extldap::bindingtest::test_errno_2:
.. _doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1a599153620f576cd22b3ec666faeaed60:
.. _cid-korowai::component::ldap::tests::adapter::extldap::bindingtest::test_bind_uninitialized_2:
.. _doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1a11c2f94bc511b95765d0633a8bc9eb84:
.. _cid-korowai::component::ldap::tests::adapter::extldap::bindingtest::test_bind_noargs:
.. _doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1a478e2a5eee49122276d934ac118ceaa7:
.. _cid-korowai::component::ldap::tests::adapter::extldap::bindingtest::test_bind_onlybinddn:
.. _doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1afdd047fde00dc4b96593a5545bca2826:
.. _cid-korowai::component::ldap::tests::adapter::extldap::bindingtest::test_bind_binddnandpassword:
.. _doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1a04e4ddad08c7c1416c423be8eea75073:
.. _cid-korowai::component::ldap::tests::adapter::extldap::bindingtest::test_bind_failure_2:
.. _doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1acc4e16488e592c32b3f5ff4adc0d76f1:
.. _cid-korowai::component::ldap::tests::adapter::extldap::bindingtest::test_getoption:
.. _doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1adc60beaec07717c3730fc212df38ee70:
.. _cid-korowai::component::ldap::tests::adapter::extldap::bindingtest::test_setoption:
.. _doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1af324dd666c485e14364b36ff3418cd6b:
.. _cid-korowai::component::ldap::tests::adapter::extldap::bindingtest::test_unbind:
.. _doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1ab8cdeda18bd80e24aea1b3b34ed0653a:
.. _cid-korowai::component::ldap::tests::adapter::extldap::bindingtest::test_unbind_uninitialized_2:
.. _doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1a81e3d2c0a2b734b991ff5968fc2b3d12:
.. _cid-korowai::component::ldap::tests::adapter::extldap::bindingtest::test_unbind_failure_2:
.. ref-code-block:: cpp
	:class: overview-code-block

	class BindingTest: public TestCase

	// methods

	getLdapFunctionMock (... $args)

	createLdapLinkMock (
	    $valid,
	    $unbind = true
	    )

	test_construct ()
	test_getLink ()
	test_isBound_Unbound ()
	test_errno_1 ()
	test_errno_2 ()
	:ref:`test_bind_Uninitialized_1<doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1a9b15161c81fa1c92f3a77f105eaed521>` ()
	test_bind_Uninitialized_2 ()
	test_bind_NoArgs ()
	test_bind_OnlyBindDn ()
	test_bind_BindDnAndPassword ()
	:ref:`test_bind_Failure_1<doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1aee1a27c0b6aa5526c451dd6d465af1ee>` ()
	test_bind_Failure_2 ()
	:ref:`test_getOption_Uninitialized<doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1a4b748fda1b88541387d6ddf68813a27d>` ()
	:ref:`test_getOption_Failure<doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1ad27745f05451b8fbf2de1a126d612336>` ()
	test_getOption ()
	:ref:`test_setOption_Uninitialized<doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1ad7f08e929ba99db0724c3a8af267f1aa>` ()
	:ref:`test_setOption_Failure<doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1a12e36bea5697e6a2acabf221227637ed>` ()
	test_setOption ()
	test_unbind ()
	:ref:`test_unbind_Uninitialized_1<doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1ab8e80b1f95887cb52c517ea3662418ce>` ()
	test_unbind_Uninitialized_2 ()
	:ref:`test_unbind_Failure_1<doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1a49dc730dfbcdf1096f2c8509777efd01>` ()
	test_unbind_Failure_2 ()

.. _details-doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1a9b15161c81fa1c92f3a77f105eaed521:
.. _cid-korowai::component::ldap::tests::adapter::extldap::bindingtest::test_bind_uninitialized_1:
.. ref-code-block:: cpp
	:class: title-code-block

	test_bind_Uninitialized_1 ()

-1  Uninitialized LDAP link

.. _doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1aee1a27c0b6aa5526c451dd6d465af1ee:
.. _cid-korowai::component::ldap::tests::adapter::extldap::bindingtest::test_bind_failure_1:
.. ref-code-block:: cpp
	:class: title-code-block

	test_bind_Failure_1 ()

2  Error message

.. _doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1a4b748fda1b88541387d6ddf68813a27d:
.. _cid-korowai::component::ldap::tests::adapter::extldap::bindingtest::test_getoption_uninitialized:
.. ref-code-block:: cpp
	:class: title-code-block

	test_getOption_Uninitialized ()

-1  Uninitialized LDAP link

.. _doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1ad27745f05451b8fbf2de1a126d612336:
.. _cid-korowai::component::ldap::tests::adapter::extldap::bindingtest::test_getoption_failure:
.. ref-code-block:: cpp
	:class: title-code-block

	test_getOption_Failure ()

2  Error message

.. _doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1ad7f08e929ba99db0724c3a8af267f1aa:
.. _cid-korowai::component::ldap::tests::adapter::extldap::bindingtest::test_setoption_uninitialized:
.. ref-code-block:: cpp
	:class: title-code-block

	test_setOption_Uninitialized ()

-1  Uninitialized LDAP link

.. _doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1a12e36bea5697e6a2acabf221227637ed:
.. _cid-korowai::component::ldap::tests::adapter::extldap::bindingtest::test_setoption_failure:
.. ref-code-block:: cpp
	:class: title-code-block

	test_setOption_Failure ()

2  Error message

.. _doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1ab8e80b1f95887cb52c517ea3662418ce:
.. _cid-korowai::component::ldap::tests::adapter::extldap::bindingtest::test_unbind_uninitialized_1:
.. ref-code-block:: cpp
	:class: title-code-block

	test_unbind_Uninitialized_1 ()

-1  Uninitialized LDAP link

.. _doxid-d7/d2f/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_binding_test_1a49dc730dfbcdf1096f2c8509777efd01:
.. _cid-korowai::component::ldap::tests::adapter::extldap::bindingtest::test_unbind_failure_1:
.. ref-code-block:: cpp
	:class: title-code-block

	test_unbind_Failure_1 ()

2  Error message

