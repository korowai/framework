.. index:: pair: class; Korowai::Component::Ldap::Tests::LdapTest
.. _doxid-dd/da3/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_ldap_test:
.. _cid-korowai::component::ldap::tests::ldaptest:

class Korowai::Component::Ldap::Tests::LdapTest
===============================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl :ref:`More...<details-doxid-dd/da3/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_ldap_test>`

.. _doxid-dd/da3/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_ldap_test_1aac80808550cecb39f3f268dfedc2975a:
.. _cid-korowai::component::ldap::tests::ldaptest::test_createwithadapterfactory:
.. _doxid-dd/da3/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_ldap_test_1a8e871ae072982feac7e784628aa4335e:
.. _cid-korowai::component::ldap::tests::ldaptest::test_createwithconfig_defaults:
.. _doxid-dd/da3/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_ldap_test_1ab404f64acdf2d524fbed55174b535fa4:
.. _cid-korowai::component::ldap::tests::ldaptest::test_createwithconfig:
.. _doxid-dd/da3/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_ldap_test_1a52d3d2e0a528be7807a97943a54456d2:
.. _cid-korowai::component::ldap::tests::ldaptest::test_getadapter:
.. _doxid-dd/da3/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_ldap_test_1a64251ad2db84f22fb9715c5f3e11c42d:
.. _cid-korowai::component::ldap::tests::ldaptest::test_getbinding:
.. _doxid-dd/da3/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_ldap_test_1aa6d55361969ee83cfa4e95d6eeab9c8d:
.. _cid-korowai::component::ldap::tests::ldaptest::test_getentrymanager:
.. _doxid-dd/da3/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_ldap_test_1adb89586aca17162d211989c13dece0fb:
.. _cid-korowai::component::ldap::tests::ldaptest::test_bind_withoutargs:
.. _doxid-dd/da3/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_ldap_test_1a49b97d21d69d05b5e1b4645b0f56b4b6:
.. _cid-korowai::component::ldap::tests::ldaptest::test_bind_withoutpassword:
.. _doxid-dd/da3/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_ldap_test_1a991c3d621f604bddfa1904e876ad8763:
.. _cid-korowai::component::ldap::tests::ldaptest::test_bind:
.. _doxid-dd/da3/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_ldap_test_1a387275fbd36d80a9e0d4498b3ac16a97:
.. _cid-korowai::component::ldap::tests::ldaptest::test_isbound_true:
.. _doxid-dd/da3/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_ldap_test_1acbd10b77e4e6aae15b650001b517d6ad:
.. _cid-korowai::component::ldap::tests::ldaptest::test_isbound_false:
.. _doxid-dd/da3/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_ldap_test_1af324dd666c485e14364b36ff3418cd6b:
.. _cid-korowai::component::ldap::tests::ldaptest::test_unbind:
.. _doxid-dd/da3/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_ldap_test_1aec128805f97ee65c26d91281cca7982f:
.. _cid-korowai::component::ldap::tests::ldaptest::test_add:
.. _doxid-dd/da3/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_ldap_test_1a6338e7909ad42420446ac35098086747:
.. _cid-korowai::component::ldap::tests::ldaptest::test_update:
.. _doxid-dd/da3/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_ldap_test_1ac48a3e07e403a9fea11b61acb815235e:
.. _cid-korowai::component::ldap::tests::ldaptest::test_rename_withoutdeleteoldrdn:
.. _doxid-dd/da3/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_ldap_test_1a1c2edf2d70ac4fa55bdc3a31053bd6fe:
.. _cid-korowai::component::ldap::tests::ldaptest::test_rename:
.. _doxid-dd/da3/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_ldap_test_1a0c2d215a888dbb988d08d270711e0d21:
.. _cid-korowai::component::ldap::tests::ldaptest::test_delete:
.. _doxid-dd/da3/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_ldap_test_1ab1d9249a20171efc17480b2ba6f0838d:
.. _cid-korowai::component::ldap::tests::ldaptest::test_createquery_defaultoptions:
.. _doxid-dd/da3/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_ldap_test_1a7a256fabf62f9922e1148d9224b8ea5a:
.. _cid-korowai::component::ldap::tests::ldaptest::test_createquery_customoptions:
.. ref-code-block:: cpp
	:class: overview-code-block

	class LdapTest: public TestCase

	// methods

	test_createWithAdapterFactory ()
	test_createWithConfig_Defaults ()
	test_createWithConfig ()
	:ref:`test_createWithConfig_InexistentClass<doxid-dd/da3/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_ldap_test_1a3c0ca23c0e38344e5c45fb0417c1db97>` ()
	:ref:`test_createWithConfig_NotAFactoryClass<doxid-dd/da3/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_ldap_test_1a56a43b21d642de8ae880697864e301f1>` ()
	test_getAdapter ()
	test_getBinding ()
	test_getEntryManager ()
	test_bind_WithoutArgs ()
	test_bind_WithoutPassword ()
	test_bind ()
	test_isBound_True ()
	test_isBound_False ()
	test_unbind ()
	test_add ()
	test_update ()
	test_rename_WithoutDeleteOldRdn ()
	test_rename ()
	test_delete ()
	test_createQuery_DefaultOptions ()
	test_createQuery_CustomOptions ()

.. _details-doxid-dd/da3/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_ldap_test:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-dd/da3/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_ldap_test_1a3c0ca23c0e38344e5c45fb0417c1db97:
.. _cid-korowai::component::ldap::tests::ldaptest::test_createwithconfig_inexistentclass:
.. ref-code-block:: cpp
	:class: title-code-block

	test_createWithConfig_InexistentClass ()

Invalid argument 2 to :ref:`Korowai <doxid-d1/da0/namespace_korowai>` ::createWithConfig: Foobar is not a name of existing class

.. _doxid-dd/da3/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_ldap_test_1a56a43b21d642de8ae880697864e301f1:
.. _cid-korowai::component::ldap::tests::ldaptest::test_createwithconfig_notafactoryclass:
.. ref-code-block:: cpp
	:class: title-code-block

	test_createWithConfig_NotAFactoryClass ()

Invalid argument 2 to :ref:`Korowai <doxid-d1/da0/namespace_korowai>` ::createWithConfig: :ref:`Korowai <doxid-d1/da0/namespace_korowai>` is not an implementation of :ref:`Korowai <doxid-d1/da0/namespace_korowai>`

