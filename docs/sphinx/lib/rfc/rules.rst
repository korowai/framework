.. index::
   single: Rules
   single: Rfc; Rules

.. _lib.rfc.rules-and-rulesets:

Rules and rulesets
==================

Grammar rules defined across RFC documents are organized in korowai as rulesets
(see :class:`Korowai\\Lib\\Rfc\\StaticRuleSetInterface`). A grammar from an RFC
document is usually expressed as a single ruleset class. Rules in rulesets
are identified by their names, the names, by convention, correspond to the rule
names from RFC document converted to UPPER_CASE. For example, rules from
RFC2253_ are gathered in a ruleset class named
:class:`Korowai\\Lib\\Rfc\\Rfc2253`.

.. literalinclude:: ../../examples/lib/rfc/rfc_rules.php
    :linenos:
    :start-after: [use]
    :end-before: [/use]

A few initial ABNF rules from this RFC document are listed below

.. code-block:: abnf

    distinguishedName = [name]    ; may be empty string
    name = name-component * (", " name-component)
    name-component = attributeTypeAndValue * ("+" attributeTypeAndValue)
    attributeTypeAndValue = attributeType "=" attributeValue
    ; ...

These RFC rules have corresponding rules in the
:class:`Korowai\\Lib\\Rfc\\Rfc2253` ruleset class - a class implementing
:class:`Korowai\\Lib\\Rfc\\StaticRuleSetInterface`. For example the
``distinguishedName`` rule is accessible by

.. literalinclude:: ../../examples/lib/rfc/rfc_rules.php
    :linenos:
    :start-after: [dnRule]
    :end-before: [/dnRule]

The returned string is a perl-compatible regular expression (PCRE) which work
with ``pcre_*`` functions, for example:

.. literalinclude:: ../../examples/lib/rfc/rfc_rules.php
    :linenos:
    :start-after: [preg_match]
    :end-before: [/preg_match]


A rule may define capture groups, such that we can easily extract specific
parts of the string being scanned with ``preg_match()``. By convention, the
rule may define *value captures* and *error captures*. Value captures are
capture groups that extract semantic values from string being scanned. Error
captures come into play when we match a string that is considered erroneous
(and our expression is designed to match such a string for better error
diagnostics). The array of all defined capture group names may be retrieved
with :method:`Korowai\\Lib\\Rfc\\Rule::captures` method. The array of value
captures is returned by :method:`Korowai\\Lib\\Rfc\\Rule::valueCaptures`. Error
captures are listed with :method:`Korowai\\Lib\\Rfc\\Rule::errorCaptures`.

In our example, the ``DISTINGUISHED_NAME`` rule of
:class:`Korowai\\Lib\\Rfc\\Rfc2253` defines only one capture group, named
``'dn'``. It's a value group. The rule does not define any error captures:


.. literalinclude:: ../../examples/lib/rfc/rfc_rules.php
    :linenos:
    :start-after: [captures]
    :end-before: [/captures]


Substrings caught by capture groups get stored in ``$matches`` and may be
easily processed with :method:`Korowai\\Lib\\Rfc::findCapturedValues` and
:method:`Korowai\\Lib\\Rfc::findCapturedErorrs`.

.. literalinclude:: ../../examples/lib/rfc/rfc_rules.php
    :linenos:
    :start-after: [captured]
    :end-before: [/captured]

The output from the above example shall be

.. literalinclude:: ../../examples/lib/rfc/rfc_rules.stdout
    :linenos:
    :language: none


.. _RFC2253: https://tools.ietf.org/html/rfc2253

.. <!--- vim: set syntax=rst spell: -->
