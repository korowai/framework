.. index:: pair: class; Korowai::Component::Ldap::Tests::Adapter::ExtLdap::AdapterFactoryTest
.. _doxid-d6/d16/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_adapter_factory_test:
.. _cid-korowai::component::ldap::tests::adapter::extldap::adapterfactorytest:

class Korowai::Component::Ldap::Tests::Adapter::ExtLdap::AdapterFactoryTest
===========================================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl :ref:`More...<details-doxid-d6/d16/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_adapter_factory_test>`

.. _doxid-d6/d16/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_adapter_factory_test_1a1f1481a472445e9fe5e6cbfbdfd3e6aa:
.. _cid-korowai::component::ldap::tests::adapter::extldap::adapterfactorytest::getldapfunctionmock:
.. _doxid-d6/d16/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_adapter_factory_test_1a5bce8099176b6a4fb44d8171c0d9158f:
.. _cid-korowai::component::ldap::tests::adapter::extldap::adapterfactorytest::test_createadapter:
.. ref-code-block:: cpp
	:class: overview-code-block

	class AdapterFactoryTest: public TestCase

	// methods

	getLdapFunctionMock (... $args)
	:ref:`test_construct_ExtLdapNotLoaded<doxid-d6/d16/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_adapter_factory_test_1ae893ac266587e1fa87fb2bb61542d57f>` ()
	:ref:`test_createAdapter_ConnectFailure_1<doxid-d6/d16/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_adapter_factory_test_1a6a823aeefeac97a9e1fb3c52e5a4cbb3>` ()
	:ref:`test_createAdapter_ConnectFailure_2<doxid-d6/d16/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_adapter_factory_test_1af0a67e46829cc48550eb25103efcaf93>` ()
	:ref:`test_createAdapter_SetOptionFailure<doxid-d6/d16/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_adapter_factory_test_1a13378a14daf90fc453e125d7c272170f>` ()
	test_createAdapter ()

.. _details-doxid-d6/d16/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_adapter_factory_test:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-d6/d16/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_adapter_factory_test_1ae893ac266587e1fa87fb2bb61542d57f:
.. _cid-korowai::component::ldap::tests::adapter::extldap::adapterfactorytest::test_construct_extldapnotloaded:
.. ref-code-block:: cpp
	:class: title-code-block

	test_construct_ExtLdapNotLoaded ()

The LDAP PHP extension is not enabled  -1

.. _doxid-d6/d16/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_adapter_factory_test_1a6a823aeefeac97a9e1fb3c52e5a4cbb3:
.. _cid-korowai::component::ldap::tests::adapter::extldap::adapterfactorytest::test_createadapter_connectfailure_1:
.. ref-code-block:: cpp
	:class: title-code-block

	test_createAdapter_ConnectFailure_1 ()

Error message  -1

.. _doxid-d6/d16/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_adapter_factory_test_1af0a67e46829cc48550eb25103efcaf93:
.. _cid-korowai::component::ldap::tests::adapter::extldap::adapterfactorytest::test_createadapter_connectfailure_2:
.. ref-code-block:: cpp
	:class: title-code-block

	test_createAdapter_ConnectFailure_2 ()

Failed to create LDAP connection  -1

.. _doxid-d6/d16/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_adapter_factory_test_1a13378a14daf90fc453e125d7c272170f:
.. _cid-korowai::component::ldap::tests::adapter::extldap::adapterfactorytest::test_createadapter_setoptionfailure:
.. ref-code-block:: cpp
	:class: title-code-block

	test_createAdapter_SetOptionFailure ()

Error message  123

