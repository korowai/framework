Feature: Examples

  @initLdapDbBeforeScenario
  @initLdapDbAfterScenario
  @ldablib
  Scenario Outline: Examples for korowai/ldaplib
    Given I executed doc example <example_file>
    Then I should see stdout from <stdout_file>
    And I should see stderr from <stderr_file>
    And I should see exit code <exit_code>

    Examples:
      | example_file                      | stdout_file                          | stderr_file                          | exit_code |
      | "ldaplib/ldap_intro.php"          | "ldaplib/ldap_intro.stdout"          | "ldaplib/ldap_intro.stderr"          | 0         |
      | "ldaplib/adapter_manual_inst.php" | "ldaplib/adapter_manual_inst.stdout" | "ldaplib/adapter_manual_inst.stderr" | 0         |
      | "ldaplib/adapter_factory_1.php"   | "ldaplib/adapter_factory_1.stdout"   | "ldaplib/adapter_factory_1.stderr"   | 0         |
      | "ldaplib/adapter_factory_2.php"   | "ldaplib/adapter_factory_2.stdout"   | "ldaplib/adapter_factory_2.stderr"   | 0         |
      | "ldaplib/attribute_exception.php" | "ldaplib/attribute_exception.stdout" | "ldaplib/attribute_exception.stderr" | 1         |
      | "ldaplib/ldap_exception_1.php"    | "ldaplib/ldap_exception_1.stdout"    | "ldaplib/ldap_exception_1.stderr"    | 1         |
      | "ldaplib/ldap_exception_2.php"    | "ldaplib/ldap_exception_2.stdout"    | "ldaplib/ldap_exception_2.stderr"    | 2         |
      | "ldaplib/mock_searchquery.php"    | "ldaplib/mock_searchquery.stdout"    | "ldaplib/mock_searchquery.stderr"    | 0         |

  @basiclib
  Scenario Outline: Examples for korowai/basiclib
    Given I executed doc example <example_file>
    Then I should see stdout from <stdout_file>
    And I should see stderr from <stderr_file>
    And I should see exit code <exit_code>

    Examples:
      | example_file                             | stdout_file                                 | stderr_file                                 | exit_code |
      | "basiclib/trivial_singleton.php"         | "basiclib/trivial_singleton.stdout"         | "basiclib/trivial_singleton.stderr"         | 0         |
      | "basiclib/count_singleton.php"           | "basiclib/count_singleton.stdout"           | "basiclib/count_singleton.stderr"           | 0         |

  @contextlib
  Scenario Outline: Examples for korowai/contextlib
    Given I executed doc example <example_file>
    Then I should see stdout from <stdout_file>
    And I should see stderr from <stderr_file>
    And I should see exit code <exit_code>

    Examples:
      | example_file                             | stdout_file                                 | stderr_file                                 | exit_code |
      | "contextlib/basic_with_usage.php"        | "contextlib/basic_with_usage.stdout"        | "contextlib/basic_with_usage.stderr"        | 0         |
      | "contextlib/trivial_value_wrapper.php"   | "contextlib/trivial_value_wrapper.stdout"   | "contextlib/trivial_value_wrapper.stderr"   | 0         |
      | "contextlib/default_context_manager.php" | "contextlib/default_context_manager.stdout" | "contextlib/default_context_manager.stderr" | 0         |
      | "contextlib/my_value_wrapper.php"        | "contextlib/my_value_wrapper.stdout"        | "contextlib/my_value_wrapper.stderr"        | 0         |
      | "contextlib/my_context_factory.php"      | "contextlib/my_context_factory.stdout"      | "contextlib/my_context_factory.stderr"      | 0         |
      | "contextlib/multiple_args.php"           | "contextlib/multiple_args.stdout"           | "contextlib/multiple_args.stderr"           | 0         |
      | "contextlib/exception_handling.php"      | "contextlib/exception_handling.stdout"      | "contextlib/exception_handling.stderr"      | 1         |
      | "contextlib/exit_true.php"               | "contextlib/exit_true.stdout"               | "contextlib/exit_true.stderr"               | 0         |

  @errorlib
  Scenario Outline: Examples for korowai/errorlib
    Given I executed doc example <example_file>
    Then I should see stdout from <stdout_file>
    And I should see stderr from <stderr_file>
    And I should see exit code <exit_code>

    Examples:
      | example_file                             | stdout_file                                  | stderr_file                                  | exit_code |
      | "errorlib/basic_example.php"             | "errorlib/basic_example.stdout"              | "errorlib/basic_example.stderr"              | 0         |
      | "errorlib/custom_error_handler.php"      | "errorlib/custom_error_handler.stdout"       | "errorlib/custom_error_handler.stderr"       | 0         |
      | "errorlib/simple_exception_thrower.php"  | "errorlib/simple_exception_thrower.stdout"   | "errorlib/simple_exception_thrower.stderr"   | 1         |
      | "errorlib/caller_error_handler.php"      | "errorlib/caller_error_handler.stdout"       | "errorlib/caller_error_handler.stderr"       | 0         |
      | "errorlib/caller_error_thrower.php"      | "errorlib/caller_error_thrower.stdout"       | "errorlib/caller_error_thrower.stderr"       | 1         |

  @rfclib
  Scenario Outline: Examples for korowai/rfclib
    Given I executed doc example <example_file>
    Then I should see stdout from <stdout_file>
    And I should see stderr from <stderr_file>
    And I should see exit code <exit_code>

    Examples:
      | example_file                             | stdout_file                                  | stderr_file                                  | exit_code |
      | "rfclib/rfc_rules.php"                   | "rfclib/rfc_rules.stdout"                    | "rfclib/rfc_rules.stderr"                    | 0         |
      | "rfclib/rfc2849/version_spec.php"        | "rfclib/rfc2849/version_spec.stdout"         | "rfclib/rfc2849/version_spec.stderr"         | 0         |
      | "rfclib/rfc2849/dn_spec.php"             | "rfclib/rfc2849/dn_spec.stdout"              | "rfclib/rfc2849/dn_spec.stderr"              | 0         |
      | "rfclib/rfc2849/changerecord_init.php"   | "rfclib/rfc2849/changerecord_init.stdout"    | "rfclib/rfc2849/changerecord_init.stderr"    | 0         |
      | "rfclib/rfc2849/mod_spec_init.php"       | "rfclib/rfc2849/mod_spec_init.stdout"        | "rfclib/rfc2849/mod_spec_init.stderr"        | 0         |
      | "rfclib/rfc2849/control.php"             | "rfclib/rfc2849/control.stdout"              | "rfclib/rfc2849/control.stderr"              | 0         |
      | "rfclib/rfc2849/newrdn_spec.php"         | "rfclib/rfc2849/newrdn_spec.stdout"          | "rfclib/rfc2849/newrdn_spec.stderr"          | 0         |
      | "rfclib/rfc2849/newsuperior_spec.php"    | "rfclib/rfc2849/newsuperior_spec.stdout"     | "rfclib/rfc2849/newsuperior_spec.stderr"     | 0         |
      | "rfclib/rfc2849/attrval_spec.php"        | "rfclib/rfc2849/attrval_spec.stdout"         | "rfclib/rfc2849/attrval_spec.stderr"         | 0         |
      | "rfclib/rfc2849/value_spec.php"          | "rfclib/rfc2849/value_spec.stdout"           | "rfclib/rfc2849/value_spec.stderr"           | 0         |

  @testing
  Scenario Outline: Examples for korowai/testing
    Given I tested <example_file> with PHPUnit
    Then I should see PHPUnit stdout from <stdout_file>
    And I should see stderr from <stderr_file>
    And I should see exit code <exit_code>

    Examples:
      | example_file                                            | stdout_file                                               | stderr_file                                               | exit_code |
      | "testing/AssertExtendsClassTest.php"                    | "testing/AssertExtendsClassTest.stdout"                   | "testing/AssertExtendsClassTest.stderr"                   | 1         |
      | "testing/AssertHasPregCapturesTest.php"                 | "testing/AssertHasPregCapturesTest.stdout"                | "testing/AssertHasPregCapturesTest.stderr"                | 1         |
      | "testing/AssertObjectPropertiesIdenticalToTest.php"  | "testing/AssertObjectPropertiesIdenticalToTest.stdout" | "testing/AssertObjectPropertiesIdenticalToTest.stderr" | 1         |
      | "testing/AssertImplementsInterfaceTest.php"             | "testing/AssertImplementsInterfaceTest.stdout"            | "testing/AssertImplementsInterfaceTest.stderr"            | 1         |
      | "testing/AssertUsesTraitTest.php"                       | "testing/AssertUsesTraitTest.stdout"                      | "testing/AssertUsesTraitTest.stderr"                      | 1         |
      | "testing/extendsClassTest.php"                          | "testing/extendsClassTest.stdout"                         | "testing/extendsClassTest.stderr"                         | 1         |
      | "testing/hasPregCapturesTest.php"                       | "testing/hasPregCapturesTest.stdout"                      | "testing/hasPregCapturesTest.stderr"                      | 1         |
      | "testing/objectPropertiesIdenticalToTest.php"        | "testing/objectPropertiesIdenticalToTest.stdout"       | "testing/objectPropertiesIdenticalToTest.stderr"       | 1         |
      | "testing/implementsInterfaceTest.php"                   | "testing/implementsInterfaceTest.stdout"                  | "testing/implementsInterfaceTest.stderr"                  | 1         |
      | "testing/usesTraitTest.php"                             | "testing/usesTraitTest.stdout"                            | "testing/usesTraitTest.stderr"                            | 1         |
