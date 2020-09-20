
.. index::
   single: Context; Exception Error Handlers
   single: Lib; Context; Exception Error Handlers
.. _errorlib.exception-error-handlers:

Exception Error Handlers
========================

:ref:`errorlib.exception-error-handlers` are error handlers that throw predefined
exceptions in response to PHP errors. :ref:`errorlib` provides
:class:`Korowai\\Lib\\Error\\ExceptionErrorHandler` class to easily create
exception-throwing error handlers.

A simple example with ExceptionErrorHandler
-------------------------------------------

In this example we'll set up a context to throw :phpclass:`\\ErrorException` in
response to PHP errors of severity ``E_USER_ERROR``. We'll use following two
functions

.. literalinclude:: ../../examples/errorlib/simple_exception_thrower.php
   :linenos:
   :start-after: [use]
   :end-before: [/use]

The :func:`Korowai\\Lib\\Error\\exceptionErrorHandler` returns new error handler
which throws a predefined exception. This handler may be enabled for a context
by passing it as an argument to :func:`Korowai\\Lib\\Context\\with`. All the
necessary code is shown in the following snippet

.. literalinclude:: ../../examples/errorlib/simple_exception_thrower.php
   :linenos:
   :start-after: [try-catch]
   :end-before: [/try-catch]

The output from our example is like

- stderr

   .. literalinclude:: ../../examples/errorlib/simple_exception_thrower.stderr
      :linenos:
      :language: none

.. <!--- vim: set syntax=rst spell: -->
