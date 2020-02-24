Installation
============

.. code-block:: shell

   php composer.phar require "korowai/errorlib:dev-master"

Purpose
=======

The main purpose of Error library is to provide handy utilities to control the
flow of PHP errors within an application. It is designed to play nicely with
our :ref:`Context Library <TheContextLibrary>`, so one can temporarily redirect
PHP errors to custom handlers in one location without altering other parts of
code.

:ref:`TheKorowaiFramework` uses :ref:`TheErrorLibrary` to implement variants of
PHP functions to throw predefined exceptions when the original functions
trigger errors.


Basic Example
=============

In the following example we'll redirect errors from one invocation of a
problematic function to a no-op error handler. The example uses the
following functions

.. literalinclude:: ../../examples/lib/error/basic_example.php
   :linenos:
   :start-after: [use]
   :end-before: [/use]

and the problematic function is

.. literalinclude:: ../../examples/lib/error/basic_example.php
   :linenos:
   :start-after: [trouble]
   :end-before: [/trouble]

The function could normally cause some noise. For example, it could call
default or an application-wide error handler. Invoking it with ``@`` only
disables error messages, but the handler is still invoked. We can prevent this
by temporarily replacing original handler with our own empty handler. This is
easily achieved with :ref:`Contexts <TheContextLibrary>` and
:class:`Korowai\\Lib\\Error\\EmptyErrorHandler`.

.. literalinclude:: ../../examples/lib/error/basic_example.php
   :linenos:
   :start-after: [test]
   :end-before: [/test]

.. <!--- vim: set syntax=rst spell: -->
