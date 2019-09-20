.. index::
   :single: Context; Custom Context Manager
   :single: Lib; Context; Custom Context Manager

Custom Context Managers
-----------------------

A custom context manager can be created by implementing the
:class:`Korowai\\Lib\\Context\\ContextManagerInterface`. The new context
manager class must implement two methods:

   - :method:`Korowai\\Lib\\Context\\ContextManagerInterface::enterContext`:
     the value returned by this function is passed as an argument to
     user-provided callback when using :func:`Korowai\\Lib\\Context\\with`,
   - :method:`Korowai\\Lib\\Context\\ContextManagerInterface::exitContext`:
     the function accepts single argument ``$exception`` of type
     :phpclass:`\Throwable`, which can be ``null`` (if no exception occurred
     within a context); the function must return boolean value indicating
     whether it handled (``true``) or not (``false``) the ``$exception``.



Simple Value Wrapper
^^^^^^^^^^^^^^^^^^^^

In the following example we implement simple context manager, which wraps a
string and provides it as an argument to user-provided callback when using
:func:`Korowai\\Lib\\Context\\with`. Note, that there is a class
:class:`Korowai\\Lib\\Context\\TrivialValueWrapper` for very similar purpose
(except, it's not restricted to strings and it doesn't print anything).

Import symbols required for this example

.. literalinclude:: ../../examples/lib/context/my_value_wrapper.php
   :start-after: [use]
   :lines: 1-2

The class implementation will be rather simple. Its ``enterContext()`` and
``exitContext()`` will just print messages to inform us that the context was
entered/exited.

.. literalinclude:: ../../examples/lib/context/my_value_wrapper.php
   :start-after: [myValueWrapperClass]
   :lines: 1-21

The new context manager is ready to use. It may be tested as follows

.. literalinclude:: ../../examples/lib/context/my_value_wrapper.php
   :start-after: [withMyValueWrapperEchoValue]
   :lines: 1-3

Obviously, the expected output will be

.. literalinclude:: ../../examples/lib/context/my_value_wrapper.stdout
   :language: console



.. <!--- vim: set syntax=rst spell: -->
