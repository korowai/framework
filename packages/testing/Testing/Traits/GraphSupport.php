<?php
/**
 * @file Testing/Traits/GraphSupport.php
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
 * @todo Write documentation.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait GraphSupport
{
    /**
     * @todo Write documentation.
     *
     * @param  array $graph
     * @param  string $node
     * @param  array $visited
     *
     * @return array
     */
    public static function breadFirstSearch(array $graph, string $node, array &$visited = null) : array
    {
        $nodes = [];
        foreach (($graph[$node] ?? []) as $adjacent) {
            if (!($visited[$adjacent] ?? false)) {
                $visited[$adjacent] = true;
                $nodes[$adjacent] = $adjacent;
            }
            $nodes = array_merge($nodes, static::breadFirstSearch($graph, $adjacent, $visited);
        }
        return $nodes;
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
