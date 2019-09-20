.. index::
   :single: Context; Context Factories
   :single: Lib; Context; Context Factories

.. _ContextFactories:

Context Factories
-----------------

The ``korowai/contextlib`` has a concept of *Context Factory* (a precise name
should actually be *Context Manager Factory*). A *Context Factory* is an object
which takes a *value* as an argument and returns new instance of
:class:`\\Korowai\\Lib\\Context\\ContextManagerInterface` appropriate for given
*value* (or ``null`` if it won't handle the *value*). For example, the
:class:`Korowai\\Lib\\Context\\DefaultContextFactory` creates
:class:`Korowai\\Lib\\Context\\ResourceContextManager` for any
`PHP resource`_ and :class:`Korowai\\Lib\\Context\\TrivialValueWrapper` for any
other *value* (except for *values* that are already instances of
:class:`Korowai\\Lib\\Context\\ContextManagerInterface`). A *Context Factory*
must implement :class:`Korowai\\Lib\\Context\\ContextFactoryInterface`.

The ``korowai/contextlib`` has a single stack of (custom) *Context Factories*
(:class:`Korowai\\Lib\\Context\\ContextFactoryStack`). It's empty by
default, so initially only the :class:`Korowai\\Lib\\Context\\DefaultContextFactory`
is used to generate *Context Managers*. A custom factory object can be pushed
to the top of the stack to get precedence over other factories.

Creating custom Context Factories
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

A simplest way to create new *Context Factory* is to extend the
:class:`Korowai\\Lib\\Context\\AbstractManagedContextFactory`. The new
context factory must implement the
:method:`Korowai\\Lib\\Context\\ContextFactoryInterface::getContextManager`
method. The :class:`Korowai\\Lib\\Context\\AbstractManagedContextFactory` is
either a *Context Factory* and *Context Manager*. When an instance of
:class:`Korowai\\Lib\\Context\\AbstractManagedContextFactory` is passed
to :func:`Korowai\\Lib\\Context\\with`, it gets pushed to the top of
:class:`Korowai\\Lib\\Context\\ContextFactoryStack` when entering context
and popped when exiting (so the new *Context Factory* works for all nested
contexts).

In the following example we'll wrap an integer value with an object named
``MyCounter``. Then, we'll create a dedicated *Context Manager*, named
``MyCounterManger``, to increment the counter when entering a context and
decrement when exiting. Finally, we'll provide *Context Factory* named
``MyContextFactory`` to capture instances of ``MyCounter`` and wrap them with
``MyCounterManager``.

For the purpose of example we need the following symbols to be imported

.. literalinclude:: ../../examples/lib/context/my_context_factory.php
   :start-after: [use]
   :lines: 1-3

Our counter class will be as simple as

.. literalinclude:: ../../examples/lib/context/my_context_factory.php
   :start-after: [myCounterClass]
   :lines: 1-9

The counter manager shall just increment/decrement counter's value and print
short messages when entering/exiting a context.

.. literalinclude:: ../../examples/lib/context/my_context_factory.php
   :start-after: [myCounterManagerClass]
   :lines: 1-23

Finally, comes the *Context Factory*.

.. literalinclude:: ../../examples/lib/context/my_context_factory.php
   :start-after: [myContextFactoryClass]
   :lines: 1-10

We can now push an instance of ``MyContextFactory`` to the factory stack. To
push it temporarily, we'll create two nested contexts (outer and inner),
pass an instance of ``MyContextFactory`` to the outer context and do actual job
in the inner context.

.. literalinclude:: ../../examples/lib/context/my_context_factory.php
   :start-after: [testCode]
   :lines: 1-7
   :linenos:

It must be clear, that ``MyContextFactory`` is inactive in the outer
``with()`` (line 1). It only works when entering/exiting inner contexts (line 3
in the above snippet).

Following is the output from the above example

.. literalinclude:: ../../examples/lib/context/my_context_factory.stdout
   :language: console

.. _PHP resource: https://www.php.net/manual/en/language.types.resource.php

.. <!--- vim: set syntax=rst spell: -->
