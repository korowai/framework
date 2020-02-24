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

Synopsis:

.. code:: php

  function assertHasPregCaptures(array $expected, array $matches[, string $message = ''])

Reports an error identified by ``$message`` if PCRE captures found in
``$matches`` (an array supposedly returned from `preg_match()`_) do not agree
with the expectations prescribed in the ``$expected`` array. The method
verifies only groups described in ``$expected``, so ``$expected = []``
accepts any array of ``$matches``. Expectations are formulated as follows:

- ``$expected = ['foo' => true]`` requires ``$matches['foo']`` to be present,
- ``$expected = ['foo' => false]`` requires ``$matches['foo']`` to be absent,
- ``$expected = ['foo' => 'FOO']`` requires that ``$matches['foo'] === 'FOO'``,

A capture group ``foo`` is considered absent if:

- ``$matches['foo']`` is not set, or
- ``$matches['foo'] === null``, or
- ``$matches['foo'] === [null, ...]``.

.. note::

    The presence/absence checks work only with ``$matches`` returned from
    `preg_match()`_ when invoked with the ``PREG_UNMATCHED_AS_NULL`` flag.

The method

.. code:: php

  function assertNotHasPregCaptures(array $expected, array $matches[, string $message = ''])

is the inverse of this.

.. literalinclude:: ../examples/testing/AssertHasPregCapturesTest.php
   :linenos:
   :caption: Usage of assertHasPregCaptures()
   :name: testing.assertions.assertHasPregCaptures.example

.. literalinclude:: ../examples/testing/AssertHasPregCapturesTest.stdout
  :linenos:
  :language: stdout

.. _testing.assertions.assertHasPropertiesSameAs:

assertHasPropertiesSameAs()
---------------------------

.. _testing.assertions.assertImplementsInterface:

assertImplementsInterface()
---------------------------

.. _testing.assertions.assertUsesTrait:

assertUsesTrait()
-----------------

.. _preg_match(): https://www.php.net/manual/en/function.preg-match.php

.. <!--- vim: set syntax=rst spell: -->
