.. index::
   single: Context; Caller Error Handlers
   single: Lib; Context; Caller Error Handlers
.. _CallerErrorHandlers:

Caller Error Handlers
=====================

:ref:`CallerErrorHandlers` are useful when a function implementer wants to
"blame" function's callers for errors occurring within the function. Normally,
when error is triggered with ``trigger_error()``, the error handler receives
``$file`` and ``$line`` pointing to the line of code where the
``trigger_error()`` was invoked. This can be easily changed with
:ref:`CallerErrorHandlers`, where the developer can easily point to current
function's caller or its caller's caller, and so on.

Simple example with Caller Error Handler
----------------------------------------

The example uses the following symbols

.. literalinclude:: ../../examples/lib/error/caller_error_handler.php
   :linenos:
   :start-after: [use]
   :end-before: [/use]

Our error handler will just output ``$file`` and ``$line`` it received from
:class:`Korowai\\Lib\\Error\\CallerErrorHandler`.

.. literalinclude:: ../../examples/lib/error/caller_error_handler.php
   :linenos:
   :start-after: [handler]
   :end-before: [/handler]

We're now going to implement a function which triggers an error, but blames its
caller for this error. This may be easily done with the
:class:`Korowai\\Lib\\Error\\CallerErrorHandler` class.

.. literalinclude:: ../../examples/lib/error/caller_error_handler.php
   :linenos:
   :start-after: [trigger]
   :end-before: [/trigger]

Finally, we test our function with the following code

.. literalinclude:: ../../examples/lib/error/caller_error_handler.php
   :linenos:
   :start-after: [test]
   :end-before: [/test]

The outputs from the above example are

- stdout:

.. literalinclude:: ../../examples/lib/error/caller_error_handler.stdout
   :linenos:
   :language: none

Exception-throwing Caller Error Handler
---------------------------------------

The example uses the following symbols

.. literalinclude:: ../../examples/lib/error/caller_error_thrower.php
   :linenos:
   :start-after: [use]
   :end-before: [/use]

We're now going to implement a function which triggers an error, but blames its
caller for this error. This may be easily done with the
:class:`Korowai\\Lib\\Error\\CallerExceptionErrorHandler` class.

.. literalinclude:: ../../examples/lib/error/caller_error_thrower.php
   :linenos:
   :start-after: [trigger]
   :end-before: [/trigger]

Finally, we test our function with the following code

.. literalinclude:: ../../examples/lib/error/caller_error_thrower.php
   :linenos:
   :start-after: [try-catch]
   :end-before: [/try-catch]

The outputs from the above example are

- stdout:

.. literalinclude:: ../../examples/lib/error/caller_error_thrower.stdout
   :linenos:
   :language: none

.. <!--- vim: set syntax=rst spell: -->
