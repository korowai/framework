<?php
/**
 * @file Testing/Graph.php
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
 * A directed graph.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class Graph
{
    /**
     * The graph stored as an adjacency list.
     *
     * @var array
     */
    protected $adjacency;

    /**
     * Returns *$value* if *$value* is an array or *array($value)* otherwise.
     *
     * @param  mixed $value
     * @return array
     */
    public static function arraize($value) : array
    {
        return is_array($value) ? $value : [$value];
    }

    /**
     * Appends missing entries to the *$adjacency* array.
     *
     * @param  array $adjacency
     * @return array
     */
    public static function fixAdjacencyArray(array $adjacency) : array
    {
        $missing = [];
        foreach ($adjacency as $key => $value) {
            $adjacent = static::arraize($value);
            $adjEmpty = array_combine($adjacent, array_fill(0, count($adjacent), []));
            $adjMissing = array_diff_key($adjEmpty, $adjacency);
            $missing = array_merge($missing, $adjMissing);
        }
        return array_merge($adjacency, $missing);
    }

    /**
     * Creates new instance of Graph from *$adjacency* array. The function
     * fixes the adjacency array internally with *fixAdjacencyArray()*.
     *
     * @param  array $adjacency
     * @return Graph
     */
    public static function createWithAdjacency(array $adjacency)
    {
        return new static(static::fixAdjacencyArray($adjacency));
    }

    /**
     * Initializes the graph. All graph nodes must be found in *$adjacency*'s keys.
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
    public function adjacency() : ?array
    {
        return $this->adjacency;
    }

    /**
     * Returns the array of source nodes.
     *
     * @return array
     */
    public function nodes() : array
    {
        return array_keys($this->adjacency);
    }

    /**
     * Returns *true* if *$node* belongs to graph, *false* otherwise.
     *
     * @param  mixed $node
     * @return bool
     */
    public function hasNode($node) : bool
    {
        return array_key_exists($node, $this->adjacency);
    }

    /**
     * Returns an array of unique elements from *$items* that belong to graph.
     *
     * @param  array $items
     * @return array
     */
    public function uniqueNodes(array $items)
    {
        $entries = array_intersect_key($this->adjacency, array_flip($items));
        return array_keys($entries);
    }

    /**
     * Returns nodes adjacent to *$node* or null if *$node* is not in graph.
     *
     * @param  mixed $node
     * @return array|null
     */
    public function adjacentNodes($node) : ?array
    {
        if (!$this->hasNode($node)) {
            return null;
        }
        return static::arraize($this->adjacency[$node]);
    }

    /**
     * Traverses the graph in breadth-first-search order and returns the array
     * of visited nodes.
     *
     * @param  mixed $start Start node (or an array of nodes).
     * @return array
     */
    public function bfs($start) : array
    {
        [$i, $queue, $seen] = $this->bfsInitial($start);
        while ($i < count($queue)) {
            $v = $queue[$i++]; // $v = $queue.dequeue()
            $this->bfsSweep($v, $queue, $seen);
        }
        return $queue;
    }

    /**
     * Prepares initial variables for the BFS algorithm.
     */
    protected function bfsInitial($start) : array
    {
        $nodes = static::arraize($start);
        $queue = $this->uniqueNodes($nodes);
        $seen = array_combine($queue, array_fill(0, count($queue), true));
        return [0, $queue, $seen];
    }

    /**
     * Internal BFS loop over nodes adjacent to *$v*.
     */
    protected function bfsSweep(string $v, array &$queue, array &$seen)
    {
        $adjacent = $this->adjacentNodes($v);
        foreach ($adjacent as $w) {
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
     * @param  mixed $start Start node (or an array of nodes).
     * @return array
     */
    public function dfs($start) : array
    {
        [$stack, $seen] = $this->dfsInitial($start);
        while (!empty($stack)) {
            $v = array_pop($stack);
            $this->dfsSweep($v, $stack, $seen);
        }
        return array_keys($seen);
    }

    /**
     * Prepares initial variables for the DFS algorithm.
     */
    protected function dfsInitial($start)
    {
        $nodes = static::arraize($start);
        $stack = array_reverse($this->uniqueNodes($nodes));
        return [$stack, []];
    }

    /**
     * Internal DFS loop over nodes adjacent to *$v*.
     */
    protected function dfsSweep(string $v, array &$stack, array &$seen)
    {
        if (!($seen[$v] ?? false)) {
            $seen[$v] = true;
            $adjacent = array_reverse($this->adjacentNodes($v));
            $stack = array_merge($stack, $adjacent);
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
