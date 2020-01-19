Installation
============

.. code-block:: shell

   php composer.phar require "korowai/testing:dev-master"


Purpose
=======

The purpose of this package is to provide additional test methods and fixtures
that are recurrently used in other parts of :ref:`TheKorowaiFramework`. The
package extends the PHPUnit_ by Sebastian Bergmann by defining new constraints,
assertions and utility methods.

Beginning
=========

All testing facilities are provided by the new
:class:`Korowai\\Testing\\TestCase <Korowai\\Testing\\TestCase>` class. Just
use this class as a base for your test case instead of the PHPUnit_'s
``TestCase`` class.

.. _PHPUnit: https://phpunit.de/

.. <!--- vim: set syntax=rst spell: -->
