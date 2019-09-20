.. index::
   single: Error
   single: Lib; Error


.. _ErrorLibrary:

The Error Library
-----------------

The Error library provides handy utilities for error flow alteration.

Installation
^^^^^^^^^^^^

.. code-block:: shell

   php composer.phar require "korowai/errorlib:dev-master"

Purpose
^^^^^^^

The main purpose of Error library is to provide handy utilities to
(temporarily) change flow of errors/exceptions within a PHP application.
It is designed to play nicely with our :ref:`Context Library <ContextLibrary>`,
so one can temporarily redirect PHP errors to custom handlers in one place
without altering other parts of code.

The Error library is used to implement our own variants of several functions
which normally used to trigger PHP errors. Our implementations redirect these
errors to our own exception-throwing error handlers, so we're no longer forced
to check return values from functions for errors.

Basic Example
^^^^^^^^^^^^^

In the following example we'll redirect errors from one invocation of a
problematic function to a no-op error handler. The example will use the
following symbols

.. literalinclude:: ../examples/lib/error/basic_example.php
   :start-after: [use]
   :lines: 1-2

and the problematic function is

.. literalinclude:: ../examples/lib/error/basic_example.php
   :start-after: [makeTroubleFunction]
   :lines: 1-4

The function could normally cause some noise. For example, it could call
default or an application-wide error handler. Calling it with ``@`` only mutes
error messages, but the handler is still invoked. We can prevent this by
temporarily enabling our own empty handler. This is easily achieved with
:ref:`Contexts <ContextLibrary>` and
:class:`Korowai\\Lib\\Error\\EmptyErrorHandler`.

.. literalinclude:: ../examples/lib/error/basic_example.php
   :start-after: [testCode]
   :lines: 1-3


.. toctree::
   :maxdepth: 1
   :hidden:
   :glob:

   error/*


.. <!--- vim: set syntax=rst spell: -->
