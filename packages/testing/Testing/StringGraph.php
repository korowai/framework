<?php
/**
 * @file Testing/StringGraph.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\testing
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing;

/**
 * A directed graph whose nodes are strings.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class StringGraph
{
    /**
     * The graph stored as an adjacency list.
     *
     * @var array
     */
    protected $adjacency;

    /**
     * Initializes the graph.
     *
     * @param  array $adjacency The graph content as an adjacency list (array).
     */
    public function __construct(array $adjacency)
    {
        $this->adjacency = $adjacency;
    }

    /**
     * Returns the graph content as adjacency list.
     *
     * @return array
     */
    public function getAdjacency() : ?array
    {
        return $this->adjacency;
    }

    /**
     * Returns the array of source nodes.
     *
     * @return array
     */
    public function getSourceNodes() : array
    {
        return array_keys($this->adjacency);
    }

    /**
     * Returns the array of target nodes.
     *
     * @return array
     */
    public function getTargetNodes() : array
    {
        $nodes = [];
        foreach ($this->getAdjacency() as $source => $target) {
            $target = array_flip(is_array($target) ? $target : [$target]);
            $nodes = array_merge($nodes, $target);
        }
        return array_keys($nodes);
    }

    /**
     * Returns the array of all graph nodes.
     *
     * @return array
     */
    public function getAllNodes() : array
    {
        $nodes = array_merge($this->getSourceNodes(), $this->getTargetNodes());
        return array_keys(array_flip($nodes));
    }

    /**
     * Returns the list of nodes adjacent to *$node*.
     *
     * @param  string $node
     * @return array
     */
    public function adjacentNodes(string $node) : array
    {
        $adjacent = ($this->adjacency[$node] ?? []);
        return is_array($adjacent) ? $adjacent : [$adjacent];
    }

    /**
     * Traverses the graph in breadth-first-search order and returns the array
     * of visited nodes.
     *
     * @param  string $node Start node.
     * @return array
     */
    public function bfsPath(string $start) : array
    {
        if (!($this->adjacency[$start] ?? false)) {
            return [];
        }
        [$queue, $i] = [[$start], 0]; // $queue.enqueue($start)
        $seen = [$start => true];
        while ($i < count($queue)) {
            $v = $queue[$i++]; // $v = $queue.dequeue()
            $this->bfsVisitAdjacent($v, $queue, $seen);
        }
        return $queue;
    }

    /**
     * Internal BFS loop over nodes adjacent to *$v*.
     */
    protected function bfsVisitAdjacent(string $v, array &$queue, array &$seen)
    {
        foreach ($this->adjacentNodes($v) as $w) {
            if (!($seen[$w] ?? false)) {
                $seen[$w] = true;
                $queue[] = $w;
            }
        }
    }

    /**
     * Traverses the graph in depth-first-search order and returns the array of
     * visited nodes.
     *
     * @param  string $start Start node.
     * @return array
     */
    public function dfsPath(string $start)
    {
        if (!($this->adjacency[$start] ?? false)) {
            return [];
        }
        $seen = [];
        $stack = [$start];
        while (!empty($stack)) {
            $v = array_pop($stack);
            $this->dfsVisitAdjacent($v, $stack, $seen);
        }
        return array_keys($seen);
    }

    /**
     * Internal DFS loop over nodes adjacent to *$v*.
     */
    protected function dfsVisitAdjacent(string $v, array &$stack, array &$seen)
    {
        if (!($seen[$v] ?? false)) {
            $seen[$v] = true;
            foreach ($this->adjacentNodes($v) as $w) {
                $stack[] = $w;
            }
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
