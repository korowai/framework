.. index::
   single: Basic; Singleton
   single: Lib; Basic; Singleton

.. _lib.basic.singleton:

Singleton
=========

The :class:`Korowai\\Lib\\Basic\\Singleton` trait facilitates implementation of
classes that adhere to singleton design pattern. The trait implements method
:method:`Korowai\\Lib\\Basic\\SingletonInterface::getInstance` as defined by
the :class:`Korowai\\Lib\\Basic\\SingletonInterface`.

To begin with, one has to import these two symbols

.. literalinclude:: ../../examples/lib/basic/trivial_singleton.php
  :linenos:
  :start-after: [use]
  :end-before: [/use]

A trivial singleton may be implemented with just few lines

.. literalinclude:: ../../examples/lib/basic/trivial_singleton.php
  :linenos:
  :start-after: [TrivialSingleton]
  :end-before: [/TrivialSingleton]

The singleton instance shall be retrieved with

.. literalinclude:: ../../examples/lib/basic/trivial_singleton.php
  :linenos:
  :start-after: [TestCode]
  :end-before: [/TestCode]

The :class:`Korowai\\Lib\\Basic\\Singleton` trait introduces features that
shall appear in a typical singleton:

- :method:`Korowai\\Lib\\Basic\\Singleton::__construct` is private,
- :method:`Korowai\\Lib\\Basic\\Singleton::__clone` is private,
- :method:`Korowai\\Lib\\Basic\\Singleton::__wakeup` is private.

In addition:

- the trait implements :method:`Korowai\\Lib\\Basic\\Singleton::getInstance`
  static method which returns the single instance of the class using the
  :class:`Korowai\\Lib\\Basic\\Singleton` trait,
- a protected method
  :method:`Korowai\\Lib\\Basic\\Singleton::initializeSingleton` is invoked from
  the private constructor when the single instance gets created; a subclass may
  override the method to initialize the single instance in its own way.


The complete functionality of the :class:`Korowai\\Lib\\Basic\\Singleton` may
be illustrated with the following example

.. literalinclude:: ../../examples/lib/basic/count_singleton.php
   :linenos:
   :caption: Usage of Singleton
   :name: lib.singleton.count_singleton.example

.. literalinclude:: ../../examples/lib/basic/count_singleton.stdout
  :linenos:
  :language: none

.. <!--- vim: set syntax=rst spell: -->
