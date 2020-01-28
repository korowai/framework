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

/**
 * Implements extraction of particular characteristics of classes described by
 * an instance of [PackageDetailsInterface](\.\./PackageDetailsInterface.html).
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait PackageClassesDetails
{
    abstract public function objectPropertiesMap() : array;
    abstract public function classInheritanceGraph() : array;
    abstract public function interfaceInheritanceGraph() : array;
    abstract public function traitInheritanceGraph() : array;

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
        return $this->followNode($graph, $class);
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
        $graph = $this->interfaceInheritanceGraph();
        $parents = $this->getClassParents($class);
        return $this->followNodes($graph, array_merge($parents, [$class => $class]));
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
        $graph = $this->traitInheritanceGraph();
        $parents = $this->getClassParents($class);
        return $this->followNodes($graph, array_merge($parents, [$class => $class]));
    }

    /**
     * Traverses *$graph* starting from *$nodes* and returns an array of nodes traversed.
     *
     * @param  array $graph
     * @param  array $nodes
     * @param  array $visited
     *
     * @return array
     */
    public static function followNodes(array $graph, array $nodes, array &$visited = null) : array
    {
        $next = [];
        foreach ($nodes as $node) {
            $next = array_merge($next, static::followNode($graph, $node, $visited));
        }
        return $next;
    }

    /**
     * @todo Write documentation.
     *
     * @param  array $graph
     * @param  string $node
     * @param  array $visited
     *
     * @return array
     */
    public static function followNode(array $graph, string $node, array &$visited = null) : array
    {
        if (($next = $graph[$node] ?? null) !== null && !($visited[$node] ?? false)) {
            $visited[$node] = true; // break cycle in graph
            return self::followNodeRecursion($graph, $next, $visited);
        } else {
            return [];
        }
    }

    /**
     * @todo Write documentation.
     */
    public static function followNodeRecursion(array $graph, $nodes, array &$visited)
    {
        if (is_array($nodes)) {
            $next = static::followNodes($graph, $nodes, $visited);
            $nodes = array_combine($nodes, $nodes);
        } elseif (is_string($nodes)) {
            $next = static::followNode($graph, $nodes, $visited);
            $nodes = [$nodes => $nodes];
        } else {
            $msg = "Argument 2 passed to ".__class__."/followNodeRecursion() must be ".
                   "an array or a string, ". gettype($nodes)." given";
            throw new \InvalidArgumentException($msg);
        }
        return array_merge($next, $nodes);
    }
}

// vim: syntax=php sw=4 ts=4 et:
