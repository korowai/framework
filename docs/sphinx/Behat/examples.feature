Feature: Examples

  @initLdapDbBeforeScenario
  @initLdapDbAfterScenario
  Scenario Outline: Examples for korowai/ldaplib
    Given I executed doc example <example_file>
    Then I should see stdout from <stdout_file>
    And I should see stderr from <stderr_file>
    And I should see exit code <exit_code>

    Examples:
      | example_file                       | stdout_file                           | stderr_file                           | exit_code |
      | "lib/ldap/ldap_intro.php"          | "lib/ldap/ldap_intro.stdout"          | "lib/ldap/ldap_intro.stderr"          | 0         |
      | "lib/ldap/adapter_manual_inst.php" | "lib/ldap/adapter_manual_inst.stdout" | "lib/ldap/adapter_manual_inst.stderr" | 0         |
      | "lib/ldap/adapter_factory_1.php"   | "lib/ldap/adapter_factory_1.stdout"   | "lib/ldap/adapter_factory_1.stderr"   | 0         |
      | "lib/ldap/adapter_factory_2.php"   | "lib/ldap/adapter_factory_2.stdout"   | "lib/ldap/adapter_factory_2.stderr"   | 0         |
      | "lib/ldap/attribute_exception.php" | "lib/ldap/attribute_exception.stdout" | "lib/ldap/attribute_exception.stderr" | 1         |
      | "lib/ldap/ldap_exception_1.php"    | "lib/ldap/ldap_exception_1.stdout"    | "lib/ldap/ldap_exception_1.stderr"    | 1         |
      | "lib/ldap/ldap_exception_2.php"    | "lib/ldap/ldap_exception_2.stdout"    | "lib/ldap/ldap_exception_2.stderr"    | 2         |
      | "lib/ldap/mock_searchquery.php"    | "lib/ldap/mock_searchquery.stdout"    | "lib/ldap/mock_searchquery.stderr"    | 0         |

  Scenario Outline: Examples for korowai/basiclib
    Given I executed doc example <example_file>
    Then I should see stdout from <stdout_file>
    And I should see stderr from <stderr_file>
    And I should see exit code <exit_code>

    Examples:
      | example_file                              | stdout_file                                  | stderr_file                                  | exit_code |
      | "lib/basic/trivial_singleton.php"         | "lib/basic/trivial_singleton.stdout"         | "lib/basic/trivial_singleton.stderr"         | 0         |
      | "lib/basic/count_singleton.php"           | "lib/basic/count_singleton.stdout"           | "lib/basic/count_singleton.stderr"           | 0         |

  Scenario Outline: Examples for korowai/contextlib
    Given I executed doc example <example_file>
    Then I should see stdout from <stdout_file>
    And I should see stderr from <stderr_file>
    And I should see exit code <exit_code>

    Examples:
      | example_file                              | stdout_file                                  | stderr_file                                  | exit_code |
      | "lib/context/basic_with_usage.php"        | "lib/context/basic_with_usage.stdout"        | "lib/context/basic_with_usage.stderr"        | 0         |
      | "lib/context/trivial_value_wrapper.php"   | "lib/context/trivial_value_wrapper.stdout"   | "lib/context/trivial_value_wrapper.stderr"   | 0         |
      | "lib/context/default_context_manager.php" | "lib/context/default_context_manager.stdout" | "lib/context/default_context_manager.stderr" | 0         |
      | "lib/context/my_value_wrapper.php"        | "lib/context/my_value_wrapper.stdout"        | "lib/context/my_value_wrapper.stderr"        | 0         |
      | "lib/context/my_context_factory.php"      | "lib/context/my_context_factory.stdout"      | "lib/context/my_context_factory.stderr"      | 0         |
      | "lib/context/multiple_args.php"           | "lib/context/multiple_args.stdout"           | "lib/context/multiple_args.stderr"           | 0         |
      | "lib/context/exception_handling.php"      | "lib/context/exception_handling.stdout"      | "lib/context/exception_handling.stderr"      | 1         |
      | "lib/context/exit_true.php"               | "lib/context/exit_true.stdout"               | "lib/context/exit_true.stderr"               | 0         |

  Scenario Outline: Examples for korowai/errorib
    Given I executed doc example <example_file>
    Then I should see stdout from <stdout_file>
    And I should see stderr from <stderr_file>
    And I should see exit code <exit_code>

    Examples:
      | example_file                              | stdout_file                                   | stderr_file                                   | exit_code |
      | "lib/error/basic_example.php"             | "lib/error/basic_example.stdout"              | "lib/error/basic_example.stderr"              | 0         |
      | "lib/error/custom_error_handler.php"      | "lib/error/custom_error_handler.stdout"       | "lib/error/custom_error_handler.stderr"       | 0         |
      | "lib/error/simple_exception_thrower.php"  | "lib/error/simple_exception_thrower.stdout"   | "lib/error/simple_exception_thrower.stderr"   | 1         |
      | "lib/error/caller_error_handler.php"      | "lib/error/caller_error_handler.stdout"       | "lib/error/caller_error_handler.stderr"       | 0         |
      | "lib/error/caller_error_thrower.php"      | "lib/error/caller_error_thrower.stdout"       | "lib/error/caller_error_thrower.stderr"       | 1         |

  Scenario Outline: Examples for korowai/testing
    Given I tested <example_file> with PHPUnit
    Then I should see PHPUnit stdout from <stdout_file>
    And I should see stderr from <stderr_file>
    And I should see exit code <exit_code>

    Examples:
      | example_file                                  | stdout_file                                     | stderr_file                                     | exit_code |
      | "testing/AssertExtendsClassTest.php"          | "testing/AssertExtendsClassTest.stdout"         | "testing/AssertExtendsClassTest.stderr"         | 1         |
      | "testing/AssertHasPregCapturesTest.php"       | "testing/AssertHasPregCapturesTest.stdout"      | "testing/AssertHasPregCapturesTest.stderr"      | 1         |
      | "testing/AssertHasPropertiesSameAsTest.php"   | "testing/AssertHasPropertiesSameAsTest.stdout"  | "testing/AssertHasPropertiesSameAsTest.stderr"  | 1         |
      | "testing/AssertImplementsInterfaceTest.php"   | "testing/AssertImplementsInterfaceTest.stdout"  | "testing/AssertImplementsInterfaceTest.stderr"  | 1         |
      | "testing/AssertUsesTraitTest.php"             | "testing/AssertUsesTraitTest.stdout"            | "testing/AssertUsesTraitTest.stderr"            | 1         |
      | "testing/extendsClassTest.php"                | "testing/extendsClassTest.stdout"               | "testing/extendsClassTest.stderr"               | 1         |
      | "testing/hasPregCapturesTest.php"             | "testing/hasPregCapturesTest.stdout"            | "testing/hasPregCapturesTest.stderr"            | 1         |
      | "testing/hasPropertiesIdenticalToTest.php"    | "testing/hasPropertiesIdenticalToTest.stdout"   | "testing/hasPropertiesIdenticalToTest.stderr"   | 1         |
      | "testing/implementsInterfaceTest.php"         | "testing/implementsInterfaceTest.stdout"        | "testing/implementsInterfaceTest.stderr"        | 1         |
      | "testing/usesTraitTest.php"                   | "testing/usesTraitTest.stdout"                  | "testing/usesTraitTest.stderr"                  | 1         |
