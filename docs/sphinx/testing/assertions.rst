.. index::
   single: Assertions
   single: Testing; Assertions
.. _testing.assertions:

Assertions
==========

This section lists the various assertion methods that are available. These
assertions are implemented in several traits that are included by the
:class:`Korowai\\Testing\\TestCase` class. The assertion methods are declared
as static and can be invoked from any context.

.. _testing.assertions.assertExtendsClass:

assertExtendsClass()
--------------------

Synopsis:

.. code:: php

  function assertExtendsClass(string $parent, mixed $object[, string $message = ''])

Reports an error identified by ``$message`` if ``$object`` does not extend the
``$parent`` class. The ``$object`` may be an ``object`` or a class name as
``string``:

- if ``$object`` is an ``object``, then its class, as returned by
  ``get_class($object)``, is examined against ``$parent``, the assertion
  succeeds only if the class extends the ``$parent`` class,
- otherwise, the necessary conditions for the assertion to succeed are that

  - ``$object`` is a string,
  - ``class_exists($object)`` is ``true``, and
  - the ``$object`` class extends the ``$parent`` class.

The method

.. code:: php

  function assertNotExtendsClass(string $parent, mixed $object[, string $message = ''])

is the inverse of this.

.. literalinclude:: ../examples/testing/AssertExtendsClassTest.php
   :linenos:
   :caption: Usage of assertExtendsClass()
   :name: testing.assertions.assertExtendsClass.example

.. literalinclude:: ../examples/testing/AssertExtendsClassTest.stdout
  :linenos:
  :language: stdout


.. _testing.assertions.assertHasPregCaptures:

assertHasPregCaptures()
-----------------------

.. _testing.assertions.assertHasPropertiesSameAs:

assertHasPropertiesSameAs()
---------------------------

.. _testing.assertions.assertImplementsInterface:

assertImplementsInterface()
---------------------------

.. _testing.assertions.assertUsesTrait:

assertUsesTrait()
-----------------

.. <!--- vim: set syntax=rst spell: -->
