.. index::
   single: Assertions
   single: Testing; Assertions
.. _testing.assertions:

Assertions
==========

This section lists the various assertion methods that are available. The
methods are included in :class:`Korowai\\Testing\\TestCase` class. The
assertion methods are declared as static and can be invoked from any context.

.. _testing.assertions.assertClassPropertiesEqualTo:

assertClassPropertiesEqualTo()
------------------------------

Synopsis:

.. code:: php

  function assertClassPropertiesEqualTo(array $expected, string $class[, string $message = ''])

Reports an error identified by ``$message`` if properties of ``$class`` are not
equal to ``$expected`` ones (tested with ``==`` operator).
A property is either a static attribute value or a value returned by class's
static method that is callable without arguments. The method compares only
properties described in ``$expected``, so ``$expected = []`` accepts any
existing ``$class``. If ``$class`` does not exists, the constraint fails as
well.

The arguments are:

- ``$expected`` - an associative array with property names as keys and their
  expected values as values, if a key ends with ``"()"``, then the property is
  assumed to be a method, for example ``$expected = ['foo()' => 'F']`` requires
  method ``foo()`` to return ``'F'``,
- ``$class`` - name of the class to be examined,
- ``$message`` - optional failure message,

The method

.. code:: php

  function assertNotClassPropertiesEqualTo(array $expected, string $class[, string $message = ''])

is the inverse of this.

.. literalinclude:: ../../examples/testing/AssertClassPropertiesEqualToTest.php
   :linenos:
   :caption: Usage of assertClassPropertiesEqualTo()
   :name: testing.assertions.assertClassPropertiesEqualTo.example

.. literalinclude:: ../../examples/testing/AssertClassPropertiesEqualToTest.stdout
  :linenos:
  :language: none

.. _testing.assertions.assertClassPropertiesEqualTo:

assertClassPropertiesIdenticalTo()
----------------------------------

Synopsis:

.. code:: php

  function assertClassPropertiesIdenticalTo(array $expected, string $class[, string $message = ''])

Reports an error identified by ``$message`` if properties of ``$class``'s are
not identical to ``$expected`` ones (tested with ``===`` operator).
A property is either a static attribute value or a value returned by
``$class``'s static method that is callable without arguments. The method
compares only properties described in ``$expected``, so ``$expected = []``
accepts any existing ``$class``. If ``$class`` does not exist, the constraint
fails as well.

The arguments are:

- ``$expected`` - an associative array with property names as keys and their
  expected values as values, if a key ends with ``"()"``, then the property is
  assumed to be a method, for example ``$expected = ['foo()' => 'F']`` requires
  method ``foo()`` to return ``'F'``,
- ``$class`` - name of the class to be examined,
- ``$message`` - optional failure message,

The method

.. code:: php

  function assertNotClassPropertiesIdenticalTo(array $expected, string $class[, string $message = ''])

is the inverse of this.

.. literalinclude:: ../../examples/testing/AssertClassPropertiesIdenticalToTest.php
   :linenos:
   :caption: Usage of assertClassPropertiesIdenticalTo()
   :name: testing.assertions.assertClassPropertiesIdenticalTo.example

.. literalinclude:: ../../examples/testing/AssertClassPropertiesIdenticalToTest.stdout
  :linenos:
  :language: none

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

.. literalinclude:: ../../examples/testing/AssertExtendsClassTest.php
   :linenos:
   :caption: Usage of assertExtendsClass()
   :name: testing.assertions.assertExtendsClass.example

.. literalinclude:: ../../examples/testing/AssertExtendsClassTest.stdout
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

.. literalinclude:: ../../examples/testing/AssertHasPregCapturesTest.php
   :linenos:
   :caption: Usage of assertHasPregCaptures()
   :name: testing.assertions.assertHasPregCaptures.example

.. literalinclude:: ../../examples/testing/AssertHasPregCapturesTest.stdout
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

.. literalinclude:: ../../examples/testing/AssertImplementsInterfaceTest.php
   :linenos:
   :caption: Usage of assertImplementsInterface()
   :name: testing.assertions.assertImplementsInterface.example

.. literalinclude:: ../../examples/testing/AssertImplementsInterfaceTest.stdout
  :linenos:
  :language: none

.. _testing.assertions.assertObjectPropertiesEqualTo:

assertObjectPropertiesEqualTo()
-------------------------------

Synopsis:

.. code:: php

  function assertObjectPropertiesEqualTo(array $expected, object $object[, string $message = ''])

Reports an error identified by ``$message`` if ``$object``'s properties are not
equal to ``$expected`` ones (tested with ``==`` operator).
A property is either an attribute value or a value returned by object's method
that is callable without arguments. The method compares only properties
described in ``$expected``, so ``$expected = []`` accepts any ``$object``.

The arguments are:

- ``$expected`` - an associative array with property names as keys and their
  expected values as values, if a key ends with ``"()"``, then the property is
  assumed to be a method, for example ``$expected = ['foo()' => 'F']`` requires
  method ``foo()`` to return ``'F'``,
- ``$object`` - an object to be examined,
- ``$message`` - optional failure message,

The method

.. code:: php

  function assertNotObjectPropertiesEqualTo(array $expected, object $object[, string $message = ''])

is the inverse of this.

.. literalinclude:: ../../examples/testing/AssertObjectPropertiesEqualToTest.php
   :linenos:
   :caption: Usage of assertObjectPropertiesEqualTo()
   :name: testing.assertions.assertObjectPropertiesEqualTo.example

.. literalinclude:: ../../examples/testing/AssertObjectPropertiesEqualToTest.stdout
  :linenos:
  :language: none

.. _testing.assertions.assertObjectPropertiesEqualTo:

assertObjectPropertiesIdenticalTo()
-----------------------------------

Synopsis:

.. code:: php

  function assertObjectPropertiesIdenticalTo(array $expected, object $object[, string $message = ''])

Reports an error identified by ``$message`` if ``$object``'s properties are not
identical with ``$expected`` ones (tested with ``===`` operator).
A property is either an attribute value or a value returned by object's method
that is callable without arguments. The method compares only properties
described in ``$expected``, so ``$expected = []`` accepts any ``$object``.

The arguments are:

- ``$expected`` - an associative array with property names as keys and their
  expected values as values, if a key ends with ``"()"``, then the property is
  assumed to be a method, for example ``$expected = ['foo()' => 'F']`` requires
  method ``foo()`` to return ``'F'``,
- ``$object`` - an object to be examined,
- ``$message`` - optional failure message,

The method

.. code:: php

  function assertNotObjectPropertiesIdenticalTo(array $expected, array $matches[, string $message = ''])

is the inverse of this.

.. literalinclude:: ../../examples/testing/AssertObjectPropertiesIdenticalToTest.php
   :linenos:
   :caption: Usage of assertObjectPropertiesIdenticalTo()
   :name: testing.assertions.assertObjectPropertiesIdenticalTo.example

.. literalinclude:: ../../examples/testing/AssertObjectPropertiesIdenticalToTest.stdout
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

.. literalinclude:: ../../examples/testing/AssertUsesTraitTest.php
   :linenos:
   :caption: Usage of assertUsesTrait()
   :name: testing.assertions.assertUsesTrait.example

.. literalinclude:: ../../examples/testing/AssertUsesTraitTest.stdout
  :linenos:
  :language: none

.. _preg_match(): https://www.php.net/manual/en/function.preg-match.php

.. <!--- vim: set syntax=rst spell: -->
