.. index::
   single: Context; Custom Error Handlers
   single: Lib; Context; Custom Error Handlers
.. _CustomErrorHandlers:

Custom Error Handlers
=====================

With :ref:`TheErrorLibrary` and :ref:`Contexts <TheContextLibrary>` you can
easily use your own functions as error handlers for particular calls. A class
named :class:`Korowai\\Lib\\Error\\ErrorHandler` serves the purpose.

Simple example with custom error handler
----------------------------------------

The example uses the following symbols

.. literalinclude:: ../../examples/lib/error/custom_error_handler.php
   :linenos:
   :start-after: [use]
   :end-before: [/use]

Our error handler will respond differently to user warnings and notices.
Warnings will be printed to stderr, while notices will go normally to stdout.

.. literalinclude:: ../../examples/lib/error/custom_error_handler.php
   :linenos:
   :start-after: [watch]
   :end-before: [/watch]

The :class:`Korowai\\Lib\\Error\\ErrorHandler` object is used to enable
our error handler temporarily, so it is active only within the context

.. literalinclude:: ../../examples/lib/error/custom_error_handler.php
   :linenos:
   :start-after: [test]
   :end-before: [/test]

The outputs from the above example are

- stdout:

.. literalinclude:: ../../examples/lib/error/custom_error_handler.stdout
   :linenos:
   :language: stdout

- stderr:

.. literalinclude:: ../../examples/lib/error/custom_error_handler.stderr
   :linenos:
   :language: stderr

Note, that the last call to ``@trigger_error()`` (line 5) didn't output
anything.

.. <!--- vim: set syntax=rst spell: -->
