.. index:: pair: class; Korowai::Component::Ldap::Behat::ExtLdapContext
.. _doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context:
.. _cid-korowai::component::ldap::behat::extldapcontext:

class Korowai::Component::Ldap::Behat::ExtLdapContext
=====================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Defines application features from the specific context. :ref:`More...<details-doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context>`

.. _doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a9631269047ab5eaff8b9d6d94a6d4e1d:
.. _cid-korowai::component::ldap::behat::extldapcontext::decodejsonpystringnode:
.. ref-code-block:: cpp
	:class: overview-code-block

	class ExtLdapContext: public Context

	// methods

	:ref:`__construct<doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a095c5d389db211932136b53f25f39685>` ()
	decodeJsonPyStringNode (PyStringNode $pystring)
	:ref:`decodeJsonString<doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1adb0623174f826fd436258ca41b53c024>` ($string)
	:ref:`iAmDisconnected<doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a01fa09cebe889aecb2101b84b67a8b19>` ()
	:ref:`iAmConnectedToUri<doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a9c39b6dc488268a7381e6ac65ab66919>` ($uri)
	:ref:`iAmConnectedUsingConfig<doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a58f1aeb1cc3990d9604b9183b45d256c>` ($config)
	:ref:`iAmBoundWithoutArguments<doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1afdb5a9a1557af43896144bc16a2ff5b2>` ()
	:ref:`iAmBoundWithBindDn<doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a21a16574b5bd3340aaee3ff3123b215d>` ($binddn)

	:ref:`iAmBoundWithBindDnAndPassword<doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a200e6822153a34985847734b1244a400>` (
	    $binddn,
	    $password
	    )

	:ref:`iCreateLdapLinkWithJsonConfig<doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a34c9cd938fdcd12c8ad4cb72742f0860>` ($config)
	:ref:`iBindWithoutArguments<doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a14d9a9a2bb206075ab587016d58f06d5>` ()
	:ref:`iBindWithBindDn<doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a17867b53906087d465c0526dbd9e7496>` ($binddn)

	:ref:`iBindWithBindDnAndPassword<doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1aa2ffcd8a352c7c463a6d772d663827bb>` (
	    $binddn,
	    $password
	    )

	:ref:`iQueryWithBaseDnAndFilter<doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1ab89ba8d441f63c164edcf1bfefb872fa>` (
	    $basedn,
	    $filter
	    )

	:ref:`iQueryWithBaseDnFilterAndOptions<doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a07776f249cd286f431a45e6f0f1059c0>` (
	    $basedn,
	    $filter,
	    $options
	    )

	:ref:`iShouldBeBound<doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a500c566d5f5eec6a1e593b56d53e8b95>` ()
	:ref:`iShouldSeeLdapExceptionWithMessage<doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a9cd72347abdb099382ee2f3071746b78>` ($arg1)
	:ref:`iShouldSeeLdapExceptionWithCode<doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1afd1f539ef5a1180f0db291700d1594f5>` ($arg1)
	:ref:`iShouldSeeInvalidOptionsExceptionWithMessage<doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1adb922655a92cec126e040678df2eb25a>` ($arg1)
	:ref:`iShouldSeeNoException<doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a1e7c57a7305c176a7fcccf6c417f0dda>` ()
	:ref:`iShouldHaveAValidLdapLink<doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a672927363795ee28b7d530e87a9546eb>` ()
	:ref:`iShouldHaveNoValidLdapLink<doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a4cbccfcef24c61914348b1ad67e75670>` ()
	:ref:`iShouldNotBeBound<doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a9ec489a5f697f98efa78c9843a213cac>` ()
	:ref:`iShouldHaveLastResultEntries<doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a70e9dd6428473ee0fe0b825978706d7b>` (PyStringNode $pystring)

.. _details-doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Defines application features from the specific context.

Methods
-------

.. _doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a095c5d389db211932136b53f25f39685:
.. _cid-korowai::component::ldap::behat::extldapcontext::__construct:
.. ref-code-block:: cpp
	:class: title-code-block

	__construct ()

Initializes context.

Every scenario gets its own context instance. You can also pass arbitrary arguments to the context constructor through behat.yml.

.. _doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1adb0623174f826fd436258ca41b53c024:
.. _cid-korowai::component::ldap::behat::extldapcontext::decodejsonstring:
.. ref-code-block:: cpp
	:class: title-code-block

	decodeJsonString ($string)

:config  :options

.. _doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a01fa09cebe889aecb2101b84b67a8b19:
.. _cid-korowai::component::ldap::behat::extldapcontext::iamdisconnected:
.. ref-code-block:: cpp
	:class: title-code-block

	iAmDisconnected ()

I am disconnected

.. _doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a9c39b6dc488268a7381e6ac65ab66919:
.. _cid-korowai::component::ldap::behat::extldapcontext::iamconnectedtouri:
.. ref-code-block:: cpp
	:class: title-code-block

	iAmConnectedToUri ($uri)

I am connected to uri :uri

.. _doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a58f1aeb1cc3990d9604b9183b45d256c:
.. _cid-korowai::component::ldap::behat::extldapcontext::iamconnectedusingconfig:
.. ref-code-block:: cpp
	:class: title-code-block

	iAmConnectedUsingConfig ($config)

I am connected using config :config

.. _doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1afdb5a9a1557af43896144bc16a2ff5b2:
.. _cid-korowai::component::ldap::behat::extldapcontext::iamboundwithoutarguments:
.. ref-code-block:: cpp
	:class: title-code-block

	iAmBoundWithoutArguments ()

I am bound without arguments

.. _doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a21a16574b5bd3340aaee3ff3123b215d:
.. _cid-korowai::component::ldap::behat::extldapcontext::iamboundwithbinddn:
.. ref-code-block:: cpp
	:class: title-code-block

	iAmBoundWithBindDn ($binddn)

I am bound with binddn :binddn

.. _doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a200e6822153a34985847734b1244a400:
.. _cid-korowai::component::ldap::behat::extldapcontext::iamboundwithbinddnandpassword:
.. ref-code-block:: cpp
	:class: title-code-block

	iAmBoundWithBindDnAndPassword (
	    $binddn,
	    $password
	    )

I am bound with binddn :binddn and password :password

.. _doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a34c9cd938fdcd12c8ad4cb72742f0860:
.. _cid-korowai::component::ldap::behat::extldapcontext::icreateldaplinkwithjsonconfig:
.. ref-code-block:: cpp
	:class: title-code-block

	iCreateLdapLinkWithJsonConfig ($config)

I create ldap link with config :config

.. _doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a14d9a9a2bb206075ab587016d58f06d5:
.. _cid-korowai::component::ldap::behat::extldapcontext::ibindwithoutarguments:
.. ref-code-block:: cpp
	:class: title-code-block

	iBindWithoutArguments ()

I bind without arguments

.. _doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a17867b53906087d465c0526dbd9e7496:
.. _cid-korowai::component::ldap::behat::extldapcontext::ibindwithbinddn:
.. ref-code-block:: cpp
	:class: title-code-block

	iBindWithBindDn ($binddn)

I bind with binddn :binddn

.. _doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1aa2ffcd8a352c7c463a6d772d663827bb:
.. _cid-korowai::component::ldap::behat::extldapcontext::ibindwithbinddnandpassword:
.. ref-code-block:: cpp
	:class: title-code-block

	iBindWithBindDnAndPassword (
	    $binddn,
	    $password
	    )

I bind with binddn :binddn and password :password

.. _doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1ab89ba8d441f63c164edcf1bfefb872fa:
.. _cid-korowai::component::ldap::behat::extldapcontext::iquerywithbasednandfilter:
.. ref-code-block:: cpp
	:class: title-code-block

	iQueryWithBaseDnAndFilter (
	    $basedn,
	    $filter
	    )

I query with basedn :basedn and filter :filter

.. _doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a07776f249cd286f431a45e6f0f1059c0:
.. _cid-korowai::component::ldap::behat::extldapcontext::iquerywithbasednfilterandoptions:
.. ref-code-block:: cpp
	:class: title-code-block

	iQueryWithBaseDnFilterAndOptions (
	    $basedn,
	    $filter,
	    $options
	    )

I query with basedn :basedn, filter :filter and options :options

.. _doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a500c566d5f5eec6a1e593b56d53e8b95:
.. _cid-korowai::component::ldap::behat::extldapcontext::ishouldbebound:
.. ref-code-block:: cpp
	:class: title-code-block

	iShouldBeBound ()

I should be bound

.. _doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a9cd72347abdb099382ee2f3071746b78:
.. _cid-korowai::component::ldap::behat::extldapcontext::ishouldseeldapexceptionwithmessage:
.. ref-code-block:: cpp
	:class: title-code-block

	iShouldSeeLdapExceptionWithMessage ($arg1)

I should see ldap exception with message :arg1

.. _doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1afd1f539ef5a1180f0db291700d1594f5:
.. _cid-korowai::component::ldap::behat::extldapcontext::ishouldseeldapexceptionwithcode:
.. ref-code-block:: cpp
	:class: title-code-block

	iShouldSeeLdapExceptionWithCode ($arg1)

I should see ldap exception with code :arg1

.. _doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1adb922655a92cec126e040678df2eb25a:
.. _cid-korowai::component::ldap::behat::extldapcontext::ishouldseeinvalidoptionsexceptionwithmessage:
.. ref-code-block:: cpp
	:class: title-code-block

	iShouldSeeInvalidOptionsExceptionWithMessage ($arg1)

I should see invalid options exception with message :arg1

.. _doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a1e7c57a7305c176a7fcccf6c417f0dda:
.. _cid-korowai::component::ldap::behat::extldapcontext::ishouldseenoexception:
.. ref-code-block:: cpp
	:class: title-code-block

	iShouldSeeNoException ()

I should see no exception

.. _doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a672927363795ee28b7d530e87a9546eb:
.. _cid-korowai::component::ldap::behat::extldapcontext::ishouldhaveavalidldaplink:
.. ref-code-block:: cpp
	:class: title-code-block

	iShouldHaveAValidLdapLink ()

I should have a valid LDAP link

.. _doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a4cbccfcef24c61914348b1ad67e75670:
.. _cid-korowai::component::ldap::behat::extldapcontext::ishouldhavenovalidldaplink:
.. ref-code-block:: cpp
	:class: title-code-block

	iShouldHaveNoValidLdapLink ()

I should have no valid LDAP link

.. _doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a9ec489a5f697f98efa78c9843a213cac:
.. _cid-korowai::component::ldap::behat::extldapcontext::ishouldnotbebound:
.. ref-code-block:: cpp
	:class: title-code-block

	iShouldNotBeBound ()

I should not be bound

.. _doxid-da/d4f/class_korowai_1_1_component_1_1_ldap_1_1_behat_1_1_ext_ldap_context_1a70e9dd6428473ee0fe0b825978706d7b:
.. _cid-korowai::component::ldap::behat::extldapcontext::ishouldhavelastresultentries:
.. ref-code-block:: cpp
	:class: title-code-block

	iShouldHaveLastResultEntries (PyStringNode $pystring)

I should have last result entries

