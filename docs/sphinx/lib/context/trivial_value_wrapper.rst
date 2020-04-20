.. index::
   single: Context; Trivial Value Wrapper
   single: Lib; Context; Trivial Value Wrapper
.. _lib.context.trivial-value-wrapper:

Trivial Value Wrapper
=====================

The :class:`Korowai\\Lib\\Context\\TrivialValueWrapper` class is a dummy
context manager, which only passes value to user-provided callback. This is
also a default context manager used for types/values not recognized by the
context library. The following two examples are actually equivalent.

- explicitly used :class:`Korowai\\Lib\\Context\\TrivialValueWrapper`:

   .. literalinclude:: ../../examples/lib/context/trivial_value_wrapper.php
      :linenos:
      :start-after: [test]
      :end-before: [/test]

- :class:`Korowai\\Lib\\Context\\TrivialValueWrapper` used internally as a
  fallback

   .. literalinclude:: ../../examples/lib/context/default_context_manager.php
      :linenos:
      :start-after: [test]
      :end-before: [/test]

.. <!--- vim: set syntax=rst spell: -->
