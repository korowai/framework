.. index:: pair: class; Korowai::Component::Ldap::Adapter::ExtLdap::LdapLink
.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink:

class Korowai::Component::Ldap::Adapter::ExtLdap::LdapLink
==========================================================

.. toctree::
	:hidden:



Overview
~~~~~~~~

Wrapper class for "ldap link" resource. :ref:`More...<details-doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link>`

.. ref-code-block:: cpp
	:class: overview-code-block

	// methods

	static static :ref:`isLdapLinkResource<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a8af5147a5cbca519cd56c97b2e27436b>` ($arg)
	static static :ref:`connect<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a86d0d5adecb325bb9e42850455b9e8bd>` (... $args)
	static static :ref:`dn2ufn<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a26d1f0e144acb54131fc8d93bd4a096d>` ($dn)
	static static :ref:`err2str<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a3b33f368ba0ab867ffe95decddcd0e13>` ($errno)

	static static :ref:`escape<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a2daa7e8b5d76d0a61b8a7533c7671a49>` (
	    $value,
	    ... $tail
	    )

	static static :ref:`explode_dn<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a4765bbf25c016a3f2aa1951224e89abd>` (
	    $dn,
	    $with_attrib
	    )

	static static :ref:`free_result<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1aa17905489fd24661cfcaf45604009537>` (:ref:`Result<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` $result)
	:ref:`__construct<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a7e17b9dfabbc8d34aafda65b1b0d3a9f>` ($link)
	:ref:`__destruct<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a421831a265621325e1fdd19aace0c758>` ()
	:ref:`getResource<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a8c5a689e9be7d35d0d01d0194637a7d2>` ()
	:ref:`isValid<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a7b37efab7473a1effc29f8be2421f6e3>` ()

	:ref:`add<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a039413de6dd5d0886582fc15438f5fbd>` (
	    $dn,
	    $entry
	    )

	:ref:`bind<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a0971d408b56eff97dd4de48788cd8ce1>` (
	    $bind_rdn = null,
	    $bind_password = null
	    )

	:ref:`close<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1aa69c8bf1f1dcf4e72552efff1fe3e87e>` ()

	:ref:`compare<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a10af402771ced58c377ebd97c253eb20>` (
	    $dn,
	    $attribute,
	    $value
	    )

	:ref:`control_paged_result_response<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1ac3e4c24bfcabdd37bbca649c782f3359>` (
	    :ref:`Result<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` $result,
	    &... $tail
	    )

	:ref:`control_paged_result<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a850d002f16b9889b3678df9154a15297>` (
	    $pagesize,
	    ... $tail
	    )

	:ref:`count_entries<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1ab6e8e50775335d8237984d746b4a0cf4>` (:ref:`Result<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` $result)
	:ref:`count_references<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a7ded8b7d835bd0563e34df01e4535270>` (:ref:`Result<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` $result)
	:ref:`delete<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a571ebd2a69d7da5f6176ef65d32ec459>` ($dn)
	:ref:`errno<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1ab2eeb64cab360a0f09923108b55c9099>` ()
	:ref:`error<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a43b8d30b879d4f09ceb059b02af2bc02>` ()
	:ref:`first_attribute<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a270c1f422d10999937f55932d715cba5>` (:ref:`ResultEntry<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry>` $result_entry)
	:ref:`first_entry<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1aa39f44d4f70c943655849bfb56819272>` (:ref:`Result<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` $result)
	:ref:`first_reference<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a35b225aa8487abd8f73000fa1657ec5e>` (:ref:`Result<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` $result)
	:ref:`get_attributes<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1af1c841c7e019d550ac9e4974ec889018>` (:ref:`ResultEntry<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry>` $result_entry)
	:ref:`get_dn<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1aba04b40822370e478375c4cc822ed9c1>` (:ref:`ResultEntry<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry>` $result_entry)
	:ref:`get_entries<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a69508f4c7732594d851cc90e5adfe129>` (:ref:`Result<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` $result)

	:ref:`get_option<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a0968ccadbf9dfae8a2108ff80a8b20f5>` (
	    $option,
	    & $retval
	    )

	:ref:`get_values_len<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a3b7477df2f372b68dde479c32851011e>` (
	    :ref:`ResultEntry<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry>` $result_entry,
	    string $attribute
	    )

	:ref:`get_values<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a418acec21d7a1138bcfc642ae1a56701>` (
	    :ref:`ResultEntry<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry>` $result_entry,
	    $attribute
	    )

	:ref:`list<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1af32488b7bee999252dcd868fbf5cfbf7>` (
	    $base_dn,
	    $filter,
	    ... $tail
	    )

	:ref:`mod_add<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1ad56dd037e90a4cff14fe2fedad52e032>` (
	    $dn,
	    $entry
	    )

	:ref:`mod_del<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a7d0e912dcf8b821fbf4b7207ba397036>` (
	    $dn,
	    $entry
	    )

	:ref:`mod_replace<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1ae2d04b6aee6cdfbb743c457ae52aa57d>` (
	    $dn,
	    $entry
	    )

	:ref:`modify_batch<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a82c124f2206ea4db74abae89f3dc2971>` (
	    $dn,
	    $entry
	    )

	:ref:`modify<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a0a9e5c7638c8896d786f3e47a2fe3dcc>` (
	    $dn,
	    $entry
	    )

	:ref:`next_attribute<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a93147cdf1b5b6fe13ac9fa91c1a46b73>` (:ref:`ResultEntry<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry>` $result_entry)
	:ref:`next_entry<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a0755e41a79777037c0223dfe31d0d611>` (:ref:`ResultEntry<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry>` $result_entry)
	:ref:`next_reference<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a37a5255ad2e070f78b35f19494a55e8b>` (:ref:`ResultReference<doxid-d6/d01/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference>` $reference)

	:ref:`parse_reference<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1aee86b53aafca6ca6a76fc537b2c34d6a>` (
	    :ref:`ResultReference<doxid-d6/d01/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference>` $reference,
	    & $referrals
	    )

	:ref:`parse_result<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1ac56c52960b5724aefd842ae24c1593b3>` (
	    :ref:`Result<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` $result,
	    & $errcode,
	    &... $tail
	    )

	:ref:`read<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1ab9a9e8ef2a9fb0a58ed1c24327f8bb8e>` (
	    $base_dn,
	    $filter,
	    ... $tail
	    )

	:ref:`rename<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1aae59090b12b8d6bfc623881bdacf7a78>` (
	    $dn,
	    $newrdn,
	    $newparent,
	    $deleteoldrdn
	    )

	:ref:`sasl_bind<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a48aaad46c927fb96a57096802f664461>` (... $args)

	:ref:`search<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1aefdfe0f14f0063b7e529407259600e44>` (
	    $base_dn,
	    $filter,
	    ... $tail
	    )

	:ref:`set_option<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1acefc635c792e38dde0570c5e546ec661>` (
	    $option,
	    $newval
	    )

	:ref:`set_rebind_proc<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a30a87c4ebad784d33f22f4dc5c61ae07>` ($callback)

	:ref:`sort<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a8a1c20f13740dc2b1f3d790b5e421940>` (
	    :ref:`Result<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` $result,
	    $sortfilter
	    )

	:ref:`start_tls<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a3ef638211e0d3c3f3c4b1f400116e30c>` ()
	:ref:`unbind<doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a346fa8b27edda87ed42edbd0323e2b63>` ()

.. _details-doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link:

Detailed Documentation
~~~~~~~~~~~~~~~~~~~~~~

Wrapper class for "ldap link" resource.

The "ldap link" resource handle is returned by ldap_connect().

Paweł Tomulik ptomulik@meil.pw.edu.pl

Methods
-------

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a8af5147a5cbca519cd56c97b2e27436b:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::isldaplinkresource:
.. ref-code-block:: cpp
	:class: title-code-block

	static static isLdapLinkResource ($arg)

Return whether $arg is a valid "ldap link" resource.

mixed :ref:`http://php.net/manual/en/resource.php <doxid->`



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $arg

        - An argument to be examined.



.. rubric:: Returns:

bool

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a86d0d5adecb325bb9e42850455b9e8bd:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::connect:
.. ref-code-block:: cpp
	:class: title-code-block

	static static connect (... $args)

Connect to an LDAP server

:ref:`ldap_connect() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a26d1f0e144acb54131fc8d93bd4a096d:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::dn2ufn:
.. ref-code-block:: cpp
	:class: title-code-block

	static static dn2ufn ($dn)

Convert DN to User Friendly Naming format

string



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $dn

        - :ref:`ldap_dn2ufn() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a3b33f368ba0ab867ffe95decddcd0e13:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::err2str:
.. ref-code-block:: cpp
	:class: title-code-block

	static static err2str ($errno)

Convert LDAP error number into string error message

int



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $errno

        - :ref:`ldap_err2str() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a2daa7e8b5d76d0a61b8a7533c7671a49:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::escape:
.. ref-code-block:: cpp
	:class: title-code-block

	static static escape (
	    $value,
	    ... $tail
	    )

Escape a string for use in an LDAP filter or DN

string



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $value

        - :ref:`ldap_escape() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a4765bbf25c016a3f2aa1951224e89abd:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::explode_dn:
.. ref-code-block:: cpp
	:class: title-code-block

	static static explode_dn (
	    $dn,
	    $with_attrib
	    )

Splits DN into its component parts

string :ref:`ldap_explode_dn() <doxid->`



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $dn

        - 

    *
        - int

        - with_attrib

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1aa17905489fd24661cfcaf45604009537:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::free_result:
.. ref-code-block:: cpp
	:class: title-code-block

	static static free_result (:ref:`Result<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` $result)

Free result memory

:ref:`Result <doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>`



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $result

        - :ref:`ldap_free_result() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a7e17b9dfabbc8d34aafda65b1b0d3a9f:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::__construct:
.. ref-code-block:: cpp
	:class: title-code-block

	__construct ($link)

Constructs :ref:`LdapLink <doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link>`

resource



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $link

        - Should be a resource returned by ldap_connect(). :ref:`ldap_connect() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a421831a265621325e1fdd19aace0c758:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::__destruct:
.. ref-code-block:: cpp
	:class: title-code-block

	__destruct ()

Destructs :ref:`LdapLink <doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link>`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a8c5a689e9be7d35d0d01d0194637a7d2:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::getresource:
.. ref-code-block:: cpp
	:class: title-code-block

	getResource ()

Returns resource provided to :ref:`__construct() <doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a7e17b9dfabbc8d34aafda65b1b0d3a9f>` .



.. rubric:: Returns:

resource|null

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a7b37efab7473a1effc29f8be2421f6e3:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::isvalid:
.. ref-code-block:: cpp
	:class: title-code-block

	isValid ()

Return whether $this->link is a valid "ldap link" resource.



.. rubric:: Returns:

bool

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a039413de6dd5d0886582fc15438f5fbd:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::add:
.. ref-code-block:: cpp
	:class: title-code-block

	add (
	    $dn,
	    $entry
	    )

Add entries to LDAP directory

string array



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $dn

        - 

    *
        - $entry

        - :ref:`ldap_add() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a0971d408b56eff97dd4de48788cd8ce1:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::bind:
.. ref-code-block:: cpp
	:class: title-code-block

	bind (
	    $bind_rdn = null,
	    $bind_password = null
	    )

Bind to LDAP directory

string string



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $bind_rdn

        - 

    *
        - $bind_password

        - :ref:`ldap_bind() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1aa69c8bf1f1dcf4e72552efff1fe3e87e:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::close:
.. ref-code-block:: cpp
	:class: title-code-block

	close ()

Same as ldap_close

:ref:`ldap_close() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a10af402771ced58c377ebd97c253eb20:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::compare:
.. ref-code-block:: cpp
	:class: title-code-block

	compare (
	    $dn,
	    $attribute,
	    $value
	    )

Compare value of attribute found in entry specified with DN

string string string



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $dn

        - 

    *
        - $attribute

        - 

    *
        - $value

        - :ref:`ldap_compare() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1ac3e4c24bfcabdd37bbca649c782f3359:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::control_paged_result_response:
.. ref-code-block:: cpp
	:class: title-code-block

	control_paged_result_response (
	    :ref:`Result<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` $result,
	    &... $tail
	    )

Retrieve the LDAP pagination cookie

:ref:`Result <doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>`



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $result

        - 

    *
        - $tail

        - :ref:`ldap_control_paged_result_response() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a850d002f16b9889b3678df9154a15297:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::control_paged_result:
.. ref-code-block:: cpp
	:class: title-code-block

	control_paged_result (
	    $pagesize,
	    ... $tail
	    )

Send LDAP pagination control

int



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $pagesize

        - :ref:`ldap_control_paged_result() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1ab6e8e50775335d8237984d746b4a0cf4:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::count_entries:
.. ref-code-block:: cpp
	:class: title-code-block

	count_entries (:ref:`Result<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` $result)

Count the number of entries in a search

:ref:`Result <doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>`



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $result

        - :ref:`ldap_count_entries() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a7ded8b7d835bd0563e34df01e4535270:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::count_references:
.. ref-code-block:: cpp
	:class: title-code-block

	count_references (:ref:`Result<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` $result)

Count the number of references in a search

:ref:`Result <doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>`



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $result

        - :ref:`ldap_count_references() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a571ebd2a69d7da5f6176ef65d32ec459:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::delete:
.. ref-code-block:: cpp
	:class: title-code-block

	delete ($dn)

Delete an entry from a directory

string



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $dn

        - :ref:`ldap_delete() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1ab2eeb64cab360a0f09923108b55c9099:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::errno:
.. ref-code-block:: cpp
	:class: title-code-block

	errno ()

Return the LDAP error number of the last LDAP command

:ref:`ldap_errno() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a43b8d30b879d4f09ceb059b02af2bc02:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::error:
.. ref-code-block:: cpp
	:class: title-code-block

	error ()

Return the LDAP error message of the last LDAP command

:ref:`ldap_error() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a270c1f422d10999937f55932d715cba5:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::first_attribute:
.. ref-code-block:: cpp
	:class: title-code-block

	first_attribute (:ref:`ResultEntry<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry>` $result_entry)

Return first attribute

:ref:`ResultEntry <doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry>`



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $result_entry

        - :ref:`ldap_first_attribute() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1aa39f44d4f70c943655849bfb56819272:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::first_entry:
.. ref-code-block:: cpp
	:class: title-code-block

	first_entry (:ref:`Result<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` $result)

Return first result id

:ref:`Result <doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>`



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $result

        - :ref:`ldap_first_entry() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a35b225aa8487abd8f73000fa1657ec5e:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::first_reference:
.. ref-code-block:: cpp
	:class: title-code-block

	first_reference (:ref:`Result<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` $result)

Return first reference

:ref:`Result <doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>`



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $result

        - :ref:`ldap_first_reference() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1af1c841c7e019d550ac9e4974ec889018:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::get_attributes:
.. ref-code-block:: cpp
	:class: title-code-block

	get_attributes (:ref:`ResultEntry<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry>` $result_entry)

Get attributes from a search result entry

:ref:`ResultEntry <doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry>`



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $result_entry

        - :ref:`ldap_get_attributes() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1aba04b40822370e478375c4cc822ed9c1:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::get_dn:
.. ref-code-block:: cpp
	:class: title-code-block

	get_dn (:ref:`ResultEntry<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry>` $result_entry)

Get the DN of a result entry

:ref:`ResultEntry <doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry>`



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $result_entry

        - :ref:`ldap_get_dn() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a69508f4c7732594d851cc90e5adfe129:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::get_entries:
.. ref-code-block:: cpp
	:class: title-code-block

	get_entries (:ref:`Result<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` $result)

Get all result entries

:ref:`Result <doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>`



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $result

        - :ref:`ldap_get_entries() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a0968ccadbf9dfae8a2108ff80a8b20f5:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::get_option:
.. ref-code-block:: cpp
	:class: title-code-block

	get_option (
	    $option,
	    & $retval
	    )

Get the current value for given option

int mixed



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $option

        - 

    *
        - $retval

        - :ref:`ldap_get_option() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a3b7477df2f372b68dde479c32851011e:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::get_values_len:
.. ref-code-block:: cpp
	:class: title-code-block

	get_values_len (
	    :ref:`ResultEntry<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry>` $result_entry,
	    string $attribute
	    )

Get all binary values from a result entry

:ref:`ldap_get_values_len() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a418acec21d7a1138bcfc642ae1a56701:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::get_values:
.. ref-code-block:: cpp
	:class: title-code-block

	get_values (
	    :ref:`ResultEntry<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry>` $result_entry,
	    $attribute
	    )

Get all values from a result entry

:ref:`ResultEntry <doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry>` string



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $result_entry

        - 

    *
        - $attribute

        - :ref:`ldap_get_values() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1af32488b7bee999252dcd868fbf5cfbf7:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::list:
.. ref-code-block:: cpp
	:class: title-code-block

	list (
	    $base_dn,
	    $filter,
	    ... $tail
	    )

Single-level search

string string



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $base_dn

        - 

    *
        - $filter

        - :ref:`ldap_list() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1ad56dd037e90a4cff14fe2fedad52e032:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::mod_add:
.. ref-code-block:: cpp
	:class: title-code-block

	mod_add (
	    $dn,
	    $entry
	    )

Add attribute values to current attributes

string array



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $dn

        - 

    *
        - $entry

        - :ref:`ldap_mod_add() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a7d0e912dcf8b821fbf4b7207ba397036:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::mod_del:
.. ref-code-block:: cpp
	:class: title-code-block

	mod_del (
	    $dn,
	    $entry
	    )

Delete attribute values from current attributes

string array



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $dn

        - 

    *
        - $entry

        - :ref:`ldap_mod_del() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1ae2d04b6aee6cdfbb743c457ae52aa57d:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::mod_replace:
.. ref-code-block:: cpp
	:class: title-code-block

	mod_replace (
	    $dn,
	    $entry
	    )

Replace attribute values with new ones

string array



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $dn

        - 

    *
        - $entry

        - :ref:`ldap_mod_replace() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a82c124f2206ea4db74abae89f3dc2971:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::modify_batch:
.. ref-code-block:: cpp
	:class: title-code-block

	modify_batch (
	    $dn,
	    $entry
	    )

Batch and execute modifications on an LDAP entry

string array



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $dn

        - 

    *
        - $entry

        - :ref:`ldap_modify_batch() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a0a9e5c7638c8896d786f3e47a2fe3dcc:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::modify:
.. ref-code-block:: cpp
	:class: title-code-block

	modify (
	    $dn,
	    $entry
	    )

Modify an LDAP entry

string array



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $dn

        - 

    *
        - $entry

        - :ref:`ldap_modify() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a93147cdf1b5b6fe13ac9fa91c1a46b73:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::next_attribute:
.. ref-code-block:: cpp
	:class: title-code-block

	next_attribute (:ref:`ResultEntry<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry>` $result_entry)

Get the next attribute in result

:ref:`ResultEntry <doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry>`



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $result_entry

        - :ref:`ldap_next_attribute() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a0755e41a79777037c0223dfe31d0d611:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::next_entry:
.. ref-code-block:: cpp
	:class: title-code-block

	next_entry (:ref:`ResultEntry<doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry>` $result_entry)

Get next result entry

:ref:`ResultEntry <doxid-df/d0a/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_entry>`



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $result_entry

        - :ref:`ldap_next_entry() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a37a5255ad2e070f78b35f19494a55e8b:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::next_reference:
.. ref-code-block:: cpp
	:class: title-code-block

	next_reference (:ref:`ResultReference<doxid-d6/d01/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference>` $reference)

Get next reference

:ref:`ResultReference <doxid-d6/d01/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference>`



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $reference

        - :ref:`ldap_next_reference() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1aee86b53aafca6ca6a76fc537b2c34d6a:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::parse_reference:
.. ref-code-block:: cpp
	:class: title-code-block

	parse_reference (
	    :ref:`ResultReference<doxid-d6/d01/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference>` $reference,
	    & $referrals
	    )

Extract information from reference entry

:ref:`ResultReference <doxid-d6/d01/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result_reference>` array



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $reference

        - 

    *
        - &$referrals

        - :ref:`ldap_parse_reference() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1ac56c52960b5724aefd842ae24c1593b3:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::parse_result:
.. ref-code-block:: cpp
	:class: title-code-block

	parse_result (
	    :ref:`Result<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` $result,
	    & $errcode,
	    &... $tail
	    )

Extract information from result

:ref:`Result <doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` int



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $result

        - 

    *
        - &$errcode

        - :ref:`ldap_parse_result() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1ab9a9e8ef2a9fb0a58ed1c24327f8bb8e:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::read:
.. ref-code-block:: cpp
	:class: title-code-block

	read (
	    $base_dn,
	    $filter,
	    ... $tail
	    )

Read an entry

string string



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $base_dn

        - 

    *
        - $filter

        - :ref:`ldap_read() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1aae59090b12b8d6bfc623881bdacf7a78:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::rename:
.. ref-code-block:: cpp
	:class: title-code-block

	rename (
	    $dn,
	    $newrdn,
	    $newparent,
	    $deleteoldrdn
	    )

Modify the name of an entry

string string string bool



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $dn

        - 

    *
        - $newrdn

        - 

    *
        - $newparent

        - 

    *
        - $deleteoldrdn

        - :ref:`ldap_rename() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a48aaad46c927fb96a57096802f664461:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::sasl_bind:
.. ref-code-block:: cpp
	:class: title-code-block

	sasl_bind (... $args)

Bind to LDAP directory using SASL

:ref:`ldap_sasl_bind() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1aefdfe0f14f0063b7e529407259600e44:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::search:
.. ref-code-block:: cpp
	:class: title-code-block

	search (
	    $base_dn,
	    $filter,
	    ... $tail
	    )

Search LDAP tree

string string



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $base_dn

        - 

    *
        - $filter

        - :ref:`ldap_search() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1acefc635c792e38dde0570c5e546ec661:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::set_option:
.. ref-code-block:: cpp
	:class: title-code-block

	set_option (
	    $option,
	    $newval
	    )

Set the value of the given option

int mixed



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $option

        - 

    *
        - $newval

        - :ref:`ldap_set_option() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a30a87c4ebad784d33f22f4dc5c61ae07:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::set_rebind_proc:
.. ref-code-block:: cpp
	:class: title-code-block

	set_rebind_proc ($callback)

Set a callback function to do re-binds on referral chasing

callable



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $callback

        - :ref:`ldap_set_rebind_proc() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a8a1c20f13740dc2b1f3d790b5e421940:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::sort:
.. ref-code-block:: cpp
	:class: title-code-block

	sort (
	    :ref:`Result<doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` $result,
	    $sortfilter
	    )

Sort LDAP result entries on the client side

:ref:`Result <doxid-df/d98/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_result>` string



.. rubric:: Parameters:

.. list-table::
    :widths: 20 80

    *
        - $result

        - 

    *
        - $sortfilter

        - :ref:`ldap_sort() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a3ef638211e0d3c3f3c4b1f400116e30c:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::start_tls:
.. ref-code-block:: cpp
	:class: title-code-block

	start_tls ()

Start TLS

:ref:`ldap_start_tls() <doxid->`

.. _doxid-dc/ddd/class_korowai_1_1_component_1_1_ldap_1_1_adapter_1_1_ext_ldap_1_1_ldap_link_1a346fa8b27edda87ed42edbd0323e2b63:
.. _cid-korowai::component::ldap::adapter::extldap::ldaplink::unbind:
.. ref-code-block:: cpp
	:class: title-code-block

	unbind ()

Unbind from LDAP directory

:ref:`ldap_unbind() <doxid->`

