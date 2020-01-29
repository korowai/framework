<?php
/**
 * @file Tests/GraphTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\testing
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing;

use PHPUnit\Framework\TestCase;
use Korowai\Testing\Graph;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class GraphTest extends TestCase
{
    public function test__arraize()
    {
        $this->assertSame(['a'], Graph::arraize(['a']));
        $this->assertSame(['a'], Graph::arraize('a'));
        $this->assertSame([null], Graph::arraize(null));
    }

    public function fixAdjacencyArrayCases()
    {
        return [
            [[], []],
            [['a' => 'a'], ['a' => 'a']],
            [
                [
                    'a' => 'b',
                    'b' => 'a',
                ],
                [
                    'a' => 'b',
                    'b' => 'a',
                ],
            ],
            [
                [
                    'a' => 'b',
                    'b' => [],
                ],
                [
                    'a' => 'b',
                ],
            ],
            [
                [
                    'a' => ['e', 'c'],
                    'c' => 'b',
                    'e' => [],
                    'b' => []

                ],
                [
                    'a' => ['e', 'c'],
                    'c' => 'b'
                ],
            ],
        ];
    }

    /**
     * @dataProvider fixAdjacencyArrayCases
     */
    public function test__fixAdjacencyArray(array $expect, array $input)
    {
        $this->assertSame($expect, Graph::fixAdjacencyArray($input));
    }

    /**
     * @dataProvider fixAdjacencyArrayCases
     */
    public function test__createWithAdjacency(array $expect, array $input)
    {
        $graph = Graph::createWithAdjacency($input);
        $this->assertInstanceOf(Graph::class, $graph);
        $this->assertSame($expect, $graph->adjacency());
    }

    public function test__construct()
    {
        $graph = new Graph([]);
        $this->assertSame([], (new Graph([]))->adjacency());
        $this->assertSame(['a' => 'A'], (new Graph(['a' => 'A']))->adjacency());
        $this->assertSame(['a' => ['A']], (new Graph(['a' => ['A']]))->adjacency());
    }

    public function test__nodes()
    {
        $this->assertSame([], (new Graph([]))->nodes());
        $this->assertSame(['a', 'b'], (new Graph(['a' => 'b', 'b' => 'c']))->nodes());
    }

    public function test__hasNode()
    {
        $this->assertFalse((new Graph([]))->hasNode('a'));
        $this->assertFalse((new Graph([]))->hasNode(null));

        $this->assertTrue((new Graph(['a' => 'b']))->hasNode('a'));
        $this->assertFalse((new Graph(['a' => 'b']))->hasNode('b'));
        $this->assertTrue((new Graph(['a' => 'b', 'b' => 'c']))->hasNode('b'));
    }

    public function uniqueNodesCases()
    {
        return [
            [[], [], []],
            [[], [], ['a', 'b']],
            [[], ['a' => 'b', 'c' => 'd'], []],
            [[], ['a' => 'b', 'c' => 'd'], ['b', 'd']],
            [['a', 'c', 'e'], ['a' => 'b', 'c' => 'd', 'e' => 'a'], ['a', 'b', 'c', 'e']],
            [['a', 'c', 'e'], ['a' => 'b', 'c' => 'd', 'e' => 'a'], ['a', 'b', 'c', 'c', 'e']],
        ];
    }

    /**
     * @dataProvider uniqueNodesCases
     */
    public function test__uniqueNodes(array $expect, array $adjacency, array $nodes)
    {
        $graph = new Graph($adjacency);
        $this->assertSame($expect, (new Graph($adjacency))->uniqueNodes($nodes));
    }

    public function adjacentNodesCases()
    {
        return [
            [null, [], 'a'],
            [[], ['a' => []], 'a'],
            [null, ['a' => ['b']], 'x'],
            [null, ['a' => ['b']], 'b'],
            [['b'], ['a' => ['b']], 'a'],
            [['b'], ['a' => 'b'], 'a'],
        ];
    }

    /**
     * @dataProvider adjacentNodesCases
     */
    public function test__adjacentNodes($expect, array $adjacency, $node)
    {
        $graph = new Graph($adjacency);
        $this->assertSame($expect, $graph->adjacentNodes($node));
    }

    public function bfsCases()
    {
        return [
            [[], [], 'a'],
            [
                ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i'],
                [
                    'a' => ['b', 'c'],
                    'b' => ['d', 'e'],
                    'c' => ['f', 'g'],
                    'd' => [],
                    'e' => [],
                    'f' => ['h', 'i'],
                    'g' => [],
                    'h' => [],
                    'i' => [],
                ],
                'a'
            ],
            [
                ['a', 'c', 'b', 'f', 'g', 'd', 'e', 'h', 'i'],
                [
                    'a' => ['b', 'c'],
                    'b' => ['d', 'e'],
                    'c' => ['f', 'g'],
                    'd' => [],
                    'e' => [],
                    'f' => ['h', 'i'],
                    'g' => [],
                    'h' => [],
                    'i' => [],
                ],
                ['a', 'c']
            ],
        ];
    }

    /**
     * @dataProvider bfsCases
     */
    public function test__bfs(array $expect, array $adjacency, $start)
    {
        $graph = new Graph($adjacency);
        $this->assertSame($expect, $graph->bfs($start));
    }

    public function dfsCases()
    {
        return [
            [[], [], 'a'],
            [
                ['a', 'b', 'd', 'e', 'c', 'f', 'h', 'i', 'g'],
                [
                    'a' => ['b', 'c'],
                    'b' => ['d', 'e'],
                    'c' => ['f', 'g'],
                    'd' => [],
                    'e' => [],
                    'f' => ['h', 'i'],
                    'g' => [],
                    'h' => [],
                    'i' => [],
                ],
                'a'
            ],
            [
                ['a', 'b', 'd', 'e', 'c', 'f', 'h', 'i', 'g'],
                [
                    'a' => ['b', 'c'],
                    'b' => ['d', 'e'],
                    'c' => ['f', 'g'],
                    'd' => [],
                    'e' => [],
                    'f' => ['h', 'i'],
                    'g' => [],
                    'h' => [],
                    'i' => [],
                ],
                ['a', 'c']
            ],
        ];
    }

    /**
     * @dataProvider dfsCases
     */
    public function test__dfs(array $expect, array $adjacency, $start)
    {
        $graph = new Graph($adjacency);
        $this->assertSame($expect, $graph->dfs($start));
    }
}

// vim: syntax=php sw=4 ts=4 et:
