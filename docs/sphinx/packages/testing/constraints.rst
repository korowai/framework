.. index::
   single: Constraints
   single: Testing; Constraints
.. _testing.constraints:

Constraints
===========
This section lists the various constraint methods that are available. The
methods are included in :class:`Korowai\\Testing\\TestCase` class. The
constraint methods are declared as static and can be invoked from any context.

PHPUnit constraints are objects designed to accept or reject PHP values.
Constraint objects are useful when defining new assertions (e.g, composite
assertion based on multiple constraints) or creating test doubles (accepting
arguments that satisfy custom constraints). The methods of
:class:`Korowai\\Testing\\TestCase` class documented below create and return
constraint objects.


.. _testing.constraints.classPropertiesEqualTo:

classPropertiesEqualTo
-----------------------

Synopsis:

.. code:: php

  function classPropertiesEqualTo(array $expected)

Creates :class:`Korowai\\Testing\\Constraint\\ClassPropertiesEqualTo` constraint.

The constraint accepts classes having selected properties equal to
``$expected``.

.. literalinclude:: ../../examples/testing/classPropertiesEqualToTest.php
   :linenos:
   :caption: Usage of classPropertiesEqualTo()
   :name: testing.assertions.classPropertiesEqualTo.example

.. literalinclude:: ../../examples/testing/classPropertiesEqualToTest.stdout
  :linenos:
  :language: none

The constraint may be used recursively, i.e. it may be used to require given
property to be an class with prescribed properties.


.. _testing.constraints.classPropertiesIdenticalTo:

classPropertiesIdenticalTo
---------------------------

Synopsis:

.. code:: php

  function classPropertiesIdenticalTo(array $expected)

Creates :class:`Korowai\\Testing\\Constraint\\ClassPropertiesIdenticalTo` constraint.

The constraint accepts classes having selected properties identical to
``$expected``.

.. literalinclude:: ../../examples/testing/classPropertiesIdenticalToTest.php
   :linenos:
   :caption: Usage of classPropertiesIdenticalTo()
   :name: testing.assertions.classPropertiesIdenticalTo.example

.. literalinclude:: ../../examples/testing/classPropertiesIdenticalToTest.stdout
  :linenos:
  :language: none

The constraint may be used recursively, i.e. it may be used to require given
property to be an class with prescribed properties.

.. _testing.constraints.extendsClass:

extendsClass
------------

Synopsis:

.. code:: php

  function extendsClass(string $parent)

Creates :class:`Korowai\\Testing\\Constraint\\ExtendsClass` constraint.

The constraint accepts objects (and classes) that extend ``$parent`` class. The
examined ``$subject`` may be an ``object`` or a class name as ``string``:

- if the ``$subject`` is an ``object``, then this object's class, as returned
  by ``get_class($subject)``, is examined against ``$parent``, the constraint
  returns ``true`` only if the class extends the ``$parent`` class,
- otherwise, the necessary conditions for the constraint to be satisfied are
  that

  - ``$subject`` is a string,
  - ``class_exists($subject)`` is ``true``, and
  - the ``$subject`` class extends the ``$parent`` class.

.. literalinclude:: ../../examples/testing/extendsClassTest.php
   :linenos:
   :caption: Usage of extendsClass()
   :name: testing.assertions.extendsClass.example

.. literalinclude:: ../../examples/testing/extendsClassTest.stdout
  :linenos:
  :language: none


.. _testing.constraints.hasPregCaptures:

hasPregCaptures
---------------

Synopsis:

.. code:: php

  function hasPregCaptures(array $expected)

Creates :class:`Korowai\\Testing\\Constraint\\HasPregCaptures` constraint.

The constraint accepts arrays of matches returned from ``preg_match()`` having
capture groups as specified in ``$expected``. Only entries present in
``$expected`` are checked, so ``$expected = []`` accepts any array. Special
values may be used in the expectations:

- ``['foo' => false]`` asserts that group ``'foo'`` was not captured,
- ``['foo' => true]`` asserts that group ``'foo'`` was captured,
- ``['foo' => 'FOO']`` asserts that group ``'foo'`` was captured and its value equals ``'FOO'``.

Boolean expectations (``['foo' => true]`` or ``['foo' => false]`` work properly
only with arrays obtained from ``preg_match()`` invoked with
``PREG_UNMATCHED_AS_NULL`` flag.

.. literalinclude:: ../../examples/testing/hasPregCapturesTest.php
   :linenos:
   :caption: Usage of hasPregCaptures()
   :name: testing.assertions.hasPregCaptures.example

.. literalinclude:: ../../examples/testing/hasPregCapturesTest.stdout
  :linenos:
  :language: none


.. _testing.constraints.implementsInterface:

implementsInterface
-------------------

Synopsis:

.. code:: php

  function implementsInterface(array $interface)

Creates :class:`Korowai\\Testing\\Constraint\\ImplementsInterfaceConstraint` constraint.

The constraint accepts objects (and classes/interfaces) that implement given
``$interface``.

.. literalinclude:: ../../examples/testing/implementsInterfaceTest.php
   :linenos:
   :caption: Usage of implementsInterface()
   :name: testing.assertions.implementsInterface.example

.. literalinclude:: ../../examples/testing/implementsInterfaceTest.stdout
  :linenos:
  :language: none


.. _testing.constraints.objectPropertiesEqualTo:

objectPropertiesEqualTo
-----------------------

Synopsis:

.. code:: php

  function objectPropertiesEqualTo(array $expected)

Creates :class:`Korowai\\Testing\\Constraint\\ObjectPropertiesEqualTo` constraint.

The constraint accepts objects having selected properties equal to
``$expected``.

.. literalinclude:: ../../examples/testing/objectPropertiesEqualToTest.php
   :linenos:
   :caption: Usage of objectPropertiesEqualTo()
   :name: testing.assertions.objectPropertiesEqualTo.example

.. literalinclude:: ../../examples/testing/objectPropertiesEqualToTest.stdout
  :linenos:
  :language: none

The constraint may be used recursively, i.e. it may be used to require given
property to be an object with prescribed properties.


.. _testing.constraints.objectPropertiesIdenticalTo:

objectPropertiesIdenticalTo
---------------------------

Synopsis:

.. code:: php

  function objectPropertiesIdenticalTo(array $expected)

Creates :class:`Korowai\\Testing\\Constraint\\ObjectPropertiesIdenticalTo` constraint.

The constraint accepts objects having selected properties identical to
``$expected``.

.. literalinclude:: ../../examples/testing/objectPropertiesIdenticalToTest.php
   :linenos:
   :caption: Usage of objectPropertiesIdenticalTo()
   :name: testing.assertions.objectPropertiesIdenticalTo.example

.. literalinclude:: ../../examples/testing/objectPropertiesIdenticalToTest.stdout
  :linenos:
  :language: none

The constraint may be used recursively, i.e. it may be used to require given
property to be an object with prescribed properties.

.. _testing.constraints.usesTrait:

usesTrait
---------

Synopsis:

.. code:: php

  function usesTrait(array $trait)

Creates :class:`Korowai\\Testing\\Constraint\\UsesTrait` constraint.

The constraint accepts objects (and classes) that use given ``$trait``.

.. literalinclude:: ../../examples/testing/usesTraitTest.php
   :linenos:
   :caption: Usage of usesTrait()
   :name: testing.assertions.usesTrait.example

.. literalinclude:: ../../examples/testing/usesTraitTest.stdout
  :linenos:
  :language: none


.. <!--- vim: set syntax=rst spell: -->
