.. index:: pair: class; Korowai::Component::Ldap::Tests::EntryTest
.. _doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test:
.. _cid-korowai::component::ldap::tests::entrytest:

class Korowai::Component::Ldap::Tests::EntryTest
================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl :ref:`More...<details-doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test>`

.. _doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1a348f6f05f62cfac61bf38714d18c1bc6:
.. _cid-korowai::component::ldap::tests::entrytest::test_constructnodn:
.. _doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1ae8a1e50e8c939007826252dd9ca17824:
.. _cid-korowai::component::ldap::tests::entrytest::test_construct_defaultattributes:
.. _doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1a37b5af79771d9a4e1fe54689d9abd112:
.. _cid-korowai::component::ldap::tests::entrytest::test_construct_1:
.. _doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1ae17509c4a80cce9cfa2da1fac142f526:
.. _cid-korowai::component::ldap::tests::entrytest::test_construct_2:
.. _doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1a3d45615e4bb8c9335cc85160eb407505:
.. _cid-korowai::component::ldap::tests::entrytest::test_setdn:
.. _doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1a97f156da0e6f8a0636be2d62b94d9031:
.. _cid-korowai::component::ldap::tests::entrytest::test_validatedn_valid:
.. _doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1a4af4fc5a99e2ba1a2dabcc774813c219:
.. _cid-korowai::component::ldap::tests::entrytest::test_getattribute_existent:
.. _doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1aadd15006817e4d56e0617e107eb45a39:
.. _cid-korowai::component::ldap::tests::entrytest::test_hasattribute_inexistent:
.. _doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1a2ac71d6788da933e625ce8db38da3b58:
.. _cid-korowai::component::ldap::tests::entrytest::test_hasattribute_existent:
.. _doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1a790706cff7d901b90832c465d78e3061:
.. _cid-korowai::component::ldap::tests::entrytest::test_setattributes_1:
.. _doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1a9175aed79a36cf5add08714bf64bf32b:
.. _cid-korowai::component::ldap::tests::entrytest::test_setattributes_2:
.. _doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1a9ebf4ac22568aee525666f8773b0aa5a:
.. _cid-korowai::component::ldap::tests::entrytest::test_setattribute:
.. _doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1a14162ce55f1826f60d713eaf3d5f4a59:
.. _cid-korowai::component::ldap::tests::entrytest::test_setattribute_invalid_4:
.. ref-code-block:: cpp
	:class: overview-code-block

	class EntryTest: public TestCase

	// methods

	test_constructNoDn ()
	:ref:`test_construct_InvalidDn<doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1adf2340421d8fe212c6934cd15a4cded5>` ()
	test_construct_DefaultAttributes ()
	test_construct_1 ()
	test_construct_2 ()
	:ref:`test_construct_InvalidAttributes_1<doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1afe2477b80a3016321d8b413773f2da1b>` ()
	:ref:`test_construct_InvalidAttributes_2<doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1a5a2f0c44a86af3d6b7ad948ec977c42d>` ()
	:ref:`test_construct_InvalidAttributes_3<doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1aeadbbfb1824a94ed21492fc34ae31837>` ()
	test_setDn ()
	:ref:`test_setDn_InvalidDn<doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1a81409bb947c1877989580a10e527acd9>` ()
	test_validateDn_Valid ()
	:ref:`test_validateDn_Invalid<doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1a9b0289fb789a2d662820bdf6ab31e85f>` ()
	:ref:`test_getAttribute_Inexistent<doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1ab6b09430f419d15e2e031ba76298b4a7>` ()
	test_getAttribute_Existent ()
	test_hasAttribute_Inexistent ()
	test_hasAttribute_Existent ()
	test_setAttributes_1 ()
	test_setAttributes_2 ()
	:ref:`test_setAttributes_Invalid_1<doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1aaea34bcec7cb028d9f9e3bf9ea6136f2>` ()
	:ref:`test_setAttributes_Invalid_2<doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1a45f7938a106c5db44afc0cd433467e26>` ()
	:ref:`test_setAttributes_Invalid_3<doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1a3f5c2b7b53fe998917a8e254dc69aa34>` ()
	test_setAttribute ()
	:ref:`test_setAttribute_Invalid_1<doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1aec9bf6a167b85a9b26de2c61fa083b2d>` ()
	:ref:`test_setAttribute_Invalid_2<doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1a33c5d0f0447703b6cb84c42ed867a466>` ()
	:ref:`test_setAttribute_Invalid_3<doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1a965b810c46b1efc7be4f79ced9d3340f>` ()
	test_setAttribute_Invalid_4 ()

.. _details-doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1adf2340421d8fe212c6934cd15a4cded5:
.. _cid-korowai::component::ldap::tests::entrytest::test_construct_invaliddn:
.. ref-code-block:: cpp
	:class: title-code-block

	test_construct_InvalidDn ()

/Argument 1 .+::__construct() .+ integer given/

.. _doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1afe2477b80a3016321d8b413773f2da1b:
.. _cid-korowai::component::ldap::tests::entrytest::test_construct_invalidattributes_1:
.. ref-code-block:: cpp
	:class: title-code-block

	test_construct_InvalidAttributes_1 ()

/Argument 2 .+::__construct() .+ string given/

.. _doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1a5a2f0c44a86af3d6b7ad948ec977c42d:
.. _cid-korowai::component::ldap::tests::entrytest::test_construct_invalidattributes_2:
.. ref-code-block:: cpp
	:class: title-code-block

	test_construct_InvalidAttributes_2 ()

/Argument 1 .+::validateAttribute() .+ integer given/

.. _doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1aeadbbfb1824a94ed21492fc34ae31837:
.. _cid-korowai::component::ldap::tests::entrytest::test_construct_invalidattributes_3:
.. ref-code-block:: cpp
	:class: title-code-block

	test_construct_InvalidAttributes_3 ()

/Argument 2 .+::validateAttribute() .+ string given/

.. _doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1a81409bb947c1877989580a10e527acd9:
.. _cid-korowai::component::ldap::tests::entrytest::test_setdn_invaliddn:
.. ref-code-block:: cpp
	:class: title-code-block

	test_setDn_InvalidDn ()

/Argument 1 .+::setDn() .+ integer given/

.. _doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1a9b0289fb789a2d662820bdf6ab31e85f:
.. _cid-korowai::component::ldap::tests::entrytest::test_validatedn_invalid:
.. ref-code-block:: cpp
	:class: title-code-block

	test_validateDn_Invalid ()

/Argument 1 .+::validateDn() .+ integer given/

.. _doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1ab6b09430f419d15e2e031ba76298b4a7:
.. _cid-korowai::component::ldap::tests::entrytest::test_getattribute_inexistent:
.. ref-code-block:: cpp
	:class: title-code-block

	test_getAttribute_Inexistent ()

:ref:`Korowai <doxid-d1/da0/namespace_korowai>` :ref:`Entry <doxid-d9/d2b/class_korowai_1_1_component_1_1_ldap_1_1_entry>` 'dc=example,dc=com' has no attribute 'userid'

.. _doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1aaea34bcec7cb028d9f9e3bf9ea6136f2:
.. _cid-korowai::component::ldap::tests::entrytest::test_setattributes_invalid_1:
.. ref-code-block:: cpp
	:class: title-code-block

	test_setAttributes_Invalid_1 ()

/Argument 1 .+::setAttributes() .+ string given/

.. _doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1a45f7938a106c5db44afc0cd433467e26:
.. _cid-korowai::component::ldap::tests::entrytest::test_setattributes_invalid_2:
.. ref-code-block:: cpp
	:class: title-code-block

	test_setAttributes_Invalid_2 ()

/Argument 1 .+::validateAttribute() .+ integer given/

.. _doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1a3f5c2b7b53fe998917a8e254dc69aa34:
.. _cid-korowai::component::ldap::tests::entrytest::test_setattributes_invalid_3:
.. ref-code-block:: cpp
	:class: title-code-block

	test_setAttributes_Invalid_3 ()

/Argument 2 .+::validateAttribute() .+ string given/

.. _doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1aec9bf6a167b85a9b26de2c61fa083b2d:
.. _cid-korowai::component::ldap::tests::entrytest::test_setattribute_invalid_1:
.. ref-code-block:: cpp
	:class: title-code-block

	test_setAttribute_Invalid_1 ()

/Argument 1 .+::setAttribute() .+ integer given/

.. _doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1a33c5d0f0447703b6cb84c42ed867a466:
.. _cid-korowai::component::ldap::tests::entrytest::test_setattribute_invalid_2:
.. ref-code-block:: cpp
	:class: title-code-block

	test_setAttribute_Invalid_2 ()

/Argument 2 .+::setAttribute() .+ integer given/

.. _doxid-d9/db8/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_entry_test_1a965b810c46b1efc7be4f79ced9d3340f:
.. _cid-korowai::component::ldap::tests::entrytest::test_setattribute_invalid_3:
.. ref-code-block:: cpp
	:class: title-code-block

	test_setAttribute_Invalid_3 ()

/Argument 2 .+::setAttribute() .+ string given/

