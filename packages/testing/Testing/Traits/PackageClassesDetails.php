<?php
/**
 * @file Testing/Traits/PackageClassesDetails.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\testing
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Traits;

use Korowai\Testing\StringGraph;

/**
 * Implements extraction of particular characteristics of classes described by
 * an instance of [PackageDetailsInterface](\.\./PackageDetailsInterface.html).
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait PackageClassesDetails
{
    abstract public function objectPropertiesMap() : array;
    abstract public function classInheritanceGraph() : StringGraph;
    abstract public function interfaceInheritanceGraph() : StringGraph;
    abstract public function traitInheritanceGraph() : StringGraph;

    /**
     * Returns an array of parent classes for *$class*, as extacted from
     * packages' details. Walks recursively thought consecutive entries in the
     * [classInheritanceGraph()](#method_classInheritanceGraph).
     *
     * @param  string $class FQDN name of the child class being investigated.
     * @return array
     */
    public function getClassParents(string $class) : array
    {
        $graph = $this->classInheritanceGraph();
        $parents = array_slice($graph->dfsPath($class), 1);
        return array_combine($parents, $parents);
    }

    /**
     * Returns an array of all inherited interfaces for *$class*, extacted from
     * packages' details. Walks recursively thought consecutive entries in the
     * [interfaceInheritanceGraph()](#method_interfaceInheritanceGraph) and
     * [classInheritanceGraph()](#method_classInheritanceGraph).
     *
     * @param  string $class FQDN name of the child class being investigated.
     * @return array
     */
    public function getClassInterfaces(string $class) : array
    {
        throw \BadMethodCallException("Not implemented yet!");
    }

    /**
     * Returns an array of all inherited interfaces for *$class*, extacted from
     * packages' details. Walks recursively thought consecutive entries in the
     * [interfaceInheritanceGraph()](#method_interfaceInheritanceGraph) and
     * [classInheritanceGraph()](#method_classInheritanceGraph).
     *
     * @param  string $class FQDN name of the child class being investigated.
     * @return array
     */
    public function getClassTraits(string $class) : array
    {
        throw \BadMethodCallException("Not implemented yet!");
    }
}

// vim: syntax=php sw=4 ts=4 et:
