.. index::
   single: Assertions
   single: Testing; Assertions
.. _testing.assertions:

Assertions
==========

This section lists the various assertion methods that are available. The
methods are included in :class:`Korowai\\Testing\\TestCase` class. The
assertion methods are declared as static and can be invoked from any context.

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
  :language: none


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
  :language: none

.. _testing.assertions.assertHasPropertiesSameAs:

assertHasPropertiesSameAs()
---------------------------

Synopsis:

.. code:: php

  function assertHasPropertiesSameAs(array $expected, object $object[, string $message = ''[, callable $getters = null]])

Reports an error identified by ``$message`` if ``$object``'s properties do not
match expectations prescribed in the ``$expected`` array. Property is defined
as either an attribute value or a value returned by object's method that is
callable without arguments. The method compares only properties described in
``$expected``, so ``$expected = []`` accepts any ``$object``. Additional
parameter ``$getters`` provides a callback that defines mappings between
property names and corresponding getter methods for particular objects.

The arguments are:

- ``$expected`` - an array of *key => value* pairs with property names as keys
  and their expected values as values, if *key* ends with ``"()"`` then the
  property is assumed to be a method, for example ``$expected = ['foo()' =>
  'F']`` requires method ``foo()`` to return ``'F'``,
- ``$object`` - an object to be examined,
- ``$message`` - optional failure message,
- ``$getters`` - a function that defines mappings between property names and
  their getter method names for particular objects. The prototype of the
  getters method is

  .. code:: php

      function getters(object $object) : array;

  the method must return an array which maps property names onto their
  corresponding method names for given ``$object``.

The method

.. code:: php

  function assertNotHasPropertiesSameAs(array $expected, array $matches[, string $message = ''[, callable $getters = null]])

is the inverse of this.

.. literalinclude:: ../examples/testing/AssertHasPropertiesSameAsTest.php
   :linenos:
   :caption: Usage of assertHasPropertiesSameAs()
   :name: testing.assertions.assertHasPropertiesSameAs.example

.. literalinclude:: ../examples/testing/AssertHasPropertiesSameAsTest.stdout
  :linenos:
  :language: none

.. _testing.assertions.assertImplementsInterface:

assertImplementsInterface()
---------------------------

Synopsis:

.. code:: php

  function assertImplementsInterface(string $interface, mixed $object[, string $message = ''])

Reports an error identified by ``$message`` if ``$object`` does not implement
the ``$interface``. The ``$object`` may be an ``object`` or a class/interface
name as ``string``:

- if ``$object`` is an ``object``, then its class, as returned by
  ``get_class($object)``, is examined against ``$interface``, the assertion
  succeeds only if the class implements the ``$interface``,
- otherwise, the necessary conditions for the assertion to succeed are that

  - ``$object`` is a string,
  - ``class_exists($object)`` is ``true`` or ``interface_exists($object)`` is
    ``true``, and
  - the ``$object`` implements the ``$interface``.

The method

.. code:: php

  function assertNotImplementsInterface(string $interface, mixed $object[, string $message = ''])

is the inverse of this.

.. literalinclude:: ../examples/testing/AssertImplementsInterfaceTest.php
   :linenos:
   :caption: Usage of assertImplementsInterface()
   :name: testing.assertions.assertImplementsInterface.example

.. literalinclude:: ../examples/testing/AssertImplementsInterfaceTest.stdout
  :linenos:
  :language: none


.. _testing.assertions.assertUsesTrait:

assertUsesTrait()
-----------------

Synopsis:

.. code:: php

  function assertUsesTrait(string $trait, mixed $object[, string $message = ''])

Reports an error identified by ``$message`` if ``$object`` does not use the
``$trait``. The ``$object`` may be an ``object`` or a class name as ``string``:

- if ``$object`` is an ``object``, then its class, as returned by
  ``get_class($object)``, is examined against ``$trait``, the assertion
  succeeds only if the class uses the ``$trait``,
- otherwise, the necessary conditions for the assertion to succeed are that

  - ``$object`` is a string,
  - ``class_exists($object)`` is ``true``, and
  - the ``$object`` implements the ``$trait``.

The method

.. code:: php

  function assertNotUsesTrait(string $trait, mixed $object[, string $message = ''])

is the inverse of this.

.. literalinclude:: ../examples/testing/AssertUsesTraitTest.php
   :linenos:
   :caption: Usage of assertUsesTrait()
   :name: testing.assertions.assertUsesTrait.example

.. literalinclude:: ../examples/testing/AssertUsesTraitTest.stdout
  :linenos:
  :language: none

.. _preg_match(): https://www.php.net/manual/en/function.preg-match.php

.. <!--- vim: set syntax=rst spell: -->
