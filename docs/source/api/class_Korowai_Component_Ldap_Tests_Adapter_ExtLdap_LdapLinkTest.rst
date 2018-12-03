.. index:: pair: class; Korowai::Component::Ldap::Tests::Adapter::ExtLdap::LdapLinkTest
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest:

class Korowai::Component::Ldap::Tests::Adapter::ExtLdap::LdapLinkTest
=====================================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl :ref:`More...<details-doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test>`

.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a1f1481a472445e9fe5e6cbfbdfd3e6aa:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::getldapfunctionmock:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1aa6e0fc7e86fee80207d2ea19c03a95cf:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_isldaplinkresource_null:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a39af63af3f1330adcc38c7ee927d2fa9:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_isldaplinkresource_notresource:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1ae4b6700504618941eed94419564ac7df:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_isldaplinkresource:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a2a63265024713d84a45d3ac8b6d9d1c7:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_isldaplinkresource_closed:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a1dd0b6885d20c5fd694b92e0608a4fbf:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_getresource_null:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a7b86932dfc7460d44167d4c26d696685:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_getresource_ldaplink:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a6f4a8538e9ab9c07170eecd9a4b66d1b:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_isvalid_null:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a0fb6e0392bc308602dbe24093c9e5e7c:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_isvalid_notresource:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a7c2194e03e1a50e152087eed7f314f7b:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_isvalid:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a4a97c6072c8779bb869e06aab1f9c769:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_isvalid_closed:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1aec128805f97ee65c26d91281cca7982f:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_add:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a991c3d621f604bddfa1904e876ad8763:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_bind:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a7c72b5a64003174a8eaebaa075ff1579:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_bind_0args:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1aa4c855fc4d1fedfc7c34753e329633d4:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_bind_1arg:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a95110967554670f522b99a8d0baf8b21:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_close:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a83ac3fe44e483ae05cbd9460cec01190:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_compare:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a86e83bbe3f64c8aa08671b352fd7dc4c:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_connect_defaults:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a08efdc5272e946a32f6ee43c86e87865:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_connect_host:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a81198c7a307b9d400b766dd2b253c28e:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_connect_hostport:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1ac0af28ab267a7d9178887770e244d0e6:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_control_paged_result_response_1:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1add4618b5ecb1ed6ca1ff739f072d99ac:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_control_paged_result_response_2:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a26027783f3ea9ba2aff1653c9a38af4c:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_control_paged_result_response_3:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a5c7f5fc72683d6e0746b8e196925ce4d:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_control_paged_result_1:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a456d1e4c895df244206db0c5917f6720:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_control_paged_result_2:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a210eabfdfdaa4d307a490bae6dc3a39e:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_count_entries:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a0c2d215a888dbb988d08d270711e0d21:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_delete:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a581db66b725e02fd74451a2f51231ce6:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_dn2ufn:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a8675b52261485a5cbda2c4c2360c5a7e:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_err2str:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a2782b34eb4fb2edc6c08a5f4481a23f7:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_errno:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a10536485425bcb823628b6f9c99322f2:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_error:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a5bd54592ed6219cced2dc002df2b276f:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_escape:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a20cc8f8455d117012766468981a46f9d:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_explode_dn:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a6206a8d09fe1a2acee2282186887d5f5:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_first_attribute:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1aa17e7b84f092c24c12b596d69f3e2157:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_first_entry_1:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1ad75eed2c0e240d6ca315c3da5eadc54f:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_first_entry_2:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a302d1c1f72f62577ce8d1623d9af6a8d:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_first_reference_1:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a2022dbdc4f6a21da1a24e8059885c8d8:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_first_reference_2:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a764cc5aa87872833341441cdbf41773f:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_free_result:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a4225d6f8a9f65c48c884a24ade581b8a:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_get_attributes:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1ae68c46ace0d3be1a69959eb5ad07cabb:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_get_dn:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1aa56c8f1a943322c0d2d90c68002e983d:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_get_entries:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a8dd125be7050835319b7d06fd4464cb7:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_get_option:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a3018af2ca678091415707966901a80af:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_get_values_len:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a54bcf2f903349e0b189682f8af12034b:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_get_values:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a345342311586b8f0ab31ed6ab9076b75:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_list_1:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1aad8ad82bdd8593571d006259495f7832:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_list_2:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1ac31ea58fafab3e5b31049eb40cd8170e:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_mod_add:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1adfea8eeec3b6ab42e714d2ec31fc6300:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_mod_del:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a1be295d4f2602207d1d88b9d37a1752a:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_mod_replace:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1ab5d32816c1aa3ed7c3a2f81aa3efda96:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_modify_batch:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a47c95e27a95c45eb42aaac557dd8ac3b:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_modify:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1aa91eb2a1bc61bb9881032da8fc47d6d3:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_next_attribute:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a6701266581ff12eac563ff3debb76d4b:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_next_entry_1:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a2f622441aa00916ee9132a040c83b4ef:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_next_entry_2:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a14628b8a1c9c352fd027d331ad56c750:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_next_reference_1:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a2687bbd071a6626e5dd6c6692c7212c4:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_next_reference_2:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1ab13c16472060eca2819962eae1c05b1d:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_parse_reference:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a7b010478b1058bde4fd62da58ff702f5:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_parse_result:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a4858ab66bb2621ba3f3a6a8fe92fd7ec:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_read_1:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1aecb521f8bcb8fc4760d134b6d2cfc712:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_read_2:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a1c2edf2d70ac4fa55bdc3a31053bd6fe:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_rename:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a06a9ded95fe7d7c3ce73b9636bc130f8:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_sasl_bind:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a223425993c5dad694b11fa7196ba7a69:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_search_1:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a718bc278a59dd97bbd34248d0b57aa56:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_search_2:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a368203b8e57ba554dffde2837c2f9913:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_set_option:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a96a67afb2b64f2fca7ddd16e89979b57:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_set_rebind_proc:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a02542a2d62918fd2ee041987bf7b4c44:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_sort:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a8fcf8dd8e8e18fa8a919b587a1f9fe9b:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_start_tls:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1af324dd666c485e14364b36ff3418cd6b:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_unbind:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a5196dbc9b613164d341a1c6778be8ebe:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_destruct_uninitialized:
.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a67f7bbda8306a44ceec7aee6a117cb66:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_destruct_unbindsuccess:
.. ref-code-block:: cpp
	:class: overview-code-block

	class LdapLinkTest: public TestCase

	// methods

	getLdapFunctionMock (... $args)
	test_isLdapLinkResource_Null ()
	test_isLdapLinkResource_NotResource ()
	test_isLdapLinkResource ()
	test_isLdapLinkResource_Closed ()
	test_getResource_Null ()
	test_getResource_LdapLink ()
	test_isValid_Null ()
	test_isValid_NotResource ()
	test_isValid ()
	test_isValid_Closed ()
	test_add ()
	test_bind ()
	test_bind_0args ()
	test_bind_1arg ()
	test_close ()
	test_compare ()
	test_connect_Defaults ()
	test_connect_Host ()
	test_connect_HostPort ()
	test_control_paged_result_response_1 ()
	test_control_paged_result_response_2 ()
	test_control_paged_result_response_3 ()
	test_control_paged_result_1 ()
	test_control_paged_result_2 ()
	test_count_entries ()
	:ref:`test_count_references<doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a2c881e81c03a4fd95cea7e8856fa2644>` ()
	test_delete ()
	test_dn2ufn ()
	test_err2str ()
	test_errno ()
	test_error ()
	test_escape ()
	test_explode_dn ()
	test_first_attribute ()
	test_first_entry_1 ()
	test_first_entry_2 ()
	test_first_reference_1 ()
	test_first_reference_2 ()
	test_free_result ()
	test_get_attributes ()
	test_get_dn ()
	test_get_entries ()
	test_get_option ()
	test_get_values_len ()
	test_get_values ()
	test_list_1 ()
	test_list_2 ()
	test_mod_add ()
	test_mod_del ()
	test_mod_replace ()
	test_modify_batch ()
	test_modify ()
	test_next_attribute ()
	test_next_entry_1 ()
	test_next_entry_2 ()
	test_next_reference_1 ()
	test_next_reference_2 ()
	test_parse_reference ()
	test_parse_result ()
	test_read_1 ()
	test_read_2 ()
	test_rename ()
	test_sasl_bind ()
	test_search_1 ()
	test_search_2 ()
	test_set_option ()
	test_set_rebind_proc ()
	test_sort ()
	test_start_tls ()
	test_unbind ()
	test_destruct_Uninitialized ()
	test_destruct_UnbindSuccess ()

.. _details-doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-dc/d4a/class_korowai_1_1_component_1_1_ldap_1_1_tests_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_test_1a2c881e81c03a4fd95cea7e8856fa2644:
.. _cid-korowai::component::ldap::tests::adapter::extldap::ldaplinktest::test_count_references:
.. ref-code-block:: cpp
	:class: title-code-block

	test_count_references ()

BadMethodCallException  Not implemented

