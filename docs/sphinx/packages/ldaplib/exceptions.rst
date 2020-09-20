.. index::
   single: Ldap; Exceptions
   single: Lib; Ldap; Exceptions


Ldap Exceptions
===============

:ref:`Ldap Library <ldaplib>` uses exceptions to report most of
errors. Exceptions used by :ref:`ldaplib` are defined in
:namespace:`Korowai\\Lib\\Ldap\\Exception <Korowai\\Lib\\Ldap\\Exception>`
namespace. The following exception classes are currently defined:

.. list-table:: Ldap library's exceptions
   :header-rows: 1
   :widths: 1 1 2

   * - Exception
     - Base Exception
     - Thrown when
   * - :class:`Korowai\\Lib\\Ldap\\Exception\\AttributeException`
     - :phpclass:`\OutOfRangeException`
     - accessing nonexistent attribute of an LDAP :class:`Korowai\\Lib\\Ldap\\Entry`
   * - :class:`Korowai\\Lib\\Ldap\\Exception\\LdapException`
     - :phpclass:`\ErrorException`
     - an error occurs during an LDAP operation


AttributeException
------------------

Derived from `OutOfRangeException <https://php.net/OutOfRangeException>`_.
It's being thrown when accessing nonexistent attribute of an
LDAP :class:`Korowai\\Lib\\Ldap\\Entry`. For example

.. literalinclude:: ../../examples/ldaplib/attribute_exception.php
   :linenos:
   :start-after: [getAttributeInexistent]
   :lines: 1


LdapException
-------------

Derived from `ErrorException <https://php.net/ErrorException>`_. It's being
thrown when an LDAP operation fails. The exception message and code are taken
from the LDAP backend.

.. literalinclude:: ../../examples/ldaplib/ldap_exception_1.php
   :linenos:
   :start-after: [try-catch]
   :end-before: [/try-catch]

The output from above example is the following

.. literalinclude:: ../../examples/ldaplib/ldap_exception_1.stderr
   :linenos:
   :language: none

To handle particular LDAP errors in an application, exception code may be used

.. literalinclude:: ../../examples/ldaplib/ldap_exception_2.php
   :linenos:
   :start-after: [try-catch]
   :end-before: [/try-catch]

The output from above example is the following

.. literalinclude:: ../../examples/ldaplib/ldap_exception_2.stderr
   :linenos:
   :language: none

Standard LDAP result codes (including error codes) are defined in several
documents including `RFC 4511`_, `RFC 3928`_, `RFC 3909`_, `RFC 4528`_, and
`RFC 4370`_. An authoritative source of LDAP result codes is the `IANA registry`_.
A useful list of LDAP return codes may also be found on `LDAP Wiki`_.


.. _IANA registry: https://www.iana.org/assignments/ldap-parameters/ldap-parameters.xhtml#ldap-parameters-6
.. _LDAP Wiki: https://ldapwiki.com/wiki/LDAP%20Result%20Codes
.. _RFC 4511: https://tools.ietf.org/html/rfc4511#section-4.1.9
.. _RFC 3928: http://www.iana.org/go/rfc3928#section-3.5
.. _RFC 3909: http://www.iana.org/go/rfc3909#section-2.3
.. _RFC 4528: https://tools.ietf.org/html/rfc4528#section-5.3
.. _RFC 4370: https://tools.ietf.org/html/rfc4370

.. <!--- vim: set syntax=rst spell: -->
