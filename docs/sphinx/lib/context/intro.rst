Installation
============

.. code-block:: shell

   php composer.phar require "korowai/contextlib:dev-master"



Basic Usage
===========

The library provides :func:`Korowai\\Lib\\Context\\with` function, whose
typical use is like

.. code-block:: php

   use function Korowai\Lib\Context\with;
   // ...
   with($cm1, ...)(function ($arg1, ...) {
      // user's instructions to be executed within context ...
   });

The arguments ``$cm1, ...`` are subject of context management.
:func:`Korowai\\Lib\\Context\\with` accepts any value as an argument but only
certain types are context-managed out-of-the box. It supports most of the
standard `PHP resources`_ and objects that implement
:class:`Korowai\\Lib\\Context\\ContextManagerInterface`. A support for other
already-existing types and classes can be added with :ref:`ContextFactories`.

For any instance of :class:`Korowai\\Lib\\Context\\ContextManagerInterface`,
its method :method:`Korowai\\Lib\\Context\\ContextManagerInterface::enterContext`
gets invoked just before the user-provided callback is called, and
:method:`Korowai\\Lib\\Context\\ContextManagerInterface::exitContext`
gets invoked just after the user-provided callback returns (or throws an
exception). Whatever ``$cm#->enterContext()`` returns, is passed to the
user-provided callback as ``$arg#`` argument.


Simple Example
==============

A relatively popular use of Python's `with-statement`_ is for automatic
management of opened file handles. Similar goals can be achieved with the
``korowai/contextlib`` and `PHP resources`_. In the following example we'll
open a file to only print its contents, and a resource context manager will
close it automatically when leaving the context.

The example requires the function :func:`Korowai\\Lib\\Context\\with` to be
imported to current scope

.. literalinclude:: ../../examples/lib/context/basic_with_usage.php
   :linenos:
   :start-after: [use]
   :end-before: [/use]

Once it's done, the following three-liner can be used to open file and read its
contents. The file gets closed automatically as soon, as the user callback
returns (or throws an exception).

.. literalinclude:: ../../examples/lib/context/basic_with_usage.php
   :linenos:
   :start-after: [test]
   :end-before: [/test]
