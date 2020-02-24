.. index::
   :single: Context; Multiple Arguments
   :single: Lib; Context; Multiple Arguments
.. _MultipleContextArguments:

Multiple context arguments
==========================

Multiple arguments may be passed to :func:`Korowai\\Lib\\Context\\with`:

.. code-block:: php

   with($cm1, $cm2, ...)(function ($arg1, $arg2, ...) {
      # body of the user-provided callback ...
   });

For every value ``$cm1``, ``$cm2``, ..., passed to
:func:`Korowai\\Lib\\Context\\with` a corresponding value is passed as an
argument to the user-provided callback. Assuming, ``$cm1``, ``$cm2``, ..., are
*Context Managers*, the corresponding arguments of the user-provided callback
will receive

   - ``$arg1 = $cm1->enterContext()``,
   - ``$arg2 = $cm2->enterContext()``,
   - ...

The context managers ``cm1``, ``cm2``, ..., are invoked in the same order as
they appear on the argument list to :func:`Korowai\\Lib\\Context\\with` when
entering the context
(:method:`Korowai\\Lib\\Context\\ContextManagerInterface::enterContext`) and in
the reverse order when exiting the context
(:method:`Korowai\\Lib\\Context\\ContextManagerInterface::exitContext`).

Let's use the following simple context manager to illustrate this

.. literalinclude:: ../../examples/lib/context/multiple_args.php
   :linenos:
   :start-after: [MyInt]
   :end-before: [/MyInt]

The order or argument processing may be then illustrated by the following test

.. literalinclude:: ../../examples/lib/context/multiple_args.php
   :linenos:
   :start-after: [test]
   :end-before: [/test]

The output from above snippet will be

.. literalinclude:: ../../examples/lib/context/multiple_args.stdout
   :linenos:
   :language: stdout

.. <!--- vim: set syntax=rst spell: -->
