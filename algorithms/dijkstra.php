<?php
/**
 * Dijkstra's Algorithm Implementation for Career Path Finding
 * 
 * This algorithm finds the shortest path between two nodes in a weighted graph,
 * which is used to determine the most efficient career path for students.
 */

/**
 * Dijkstra's algorithm implementation with path details and improved edge case handling
 * 
 * @param array $graph The graph structure containing nodes and edges
 * @param string $start The starting node ID
 * @param string $end The ending node ID
 * @return array|null The path details or null if no path exists
 */
function dijkstra($graph, $start, $end) {
    // Validate inputs
    if (empty($graph) || empty($graph['nodes']) || empty($graph['edges'])) {
        return null;
    }
    
    $nodes = $graph['nodes'];
    $edges = $graph['edges'];
    
    // Validate start and end nodes
    if (!isset($nodes[$start]) || !isset($nodes[$end])) {
        return null;
    }
    
    // If start and end are the same node, return a simple path
    if ($start === $end) {
        return [
            'path' => [$start],
            'effort' => 0,
            'nodes' => [[
                'id' => $start,
                'name' => $nodes[$start]['name'],
                'type' => $nodes[$start]['type'],
                'effort' => $nodes[$start]['effort'] ?? 0
            ]],
            'edges' => [],
            'distance' => 0
        ];
    }
    
    // Build adjacency list
    $adjList = [];
    foreach ($edges as $edge) {
        // Validate edge structure
        if (!isset($edge['from']) || !isset($edge['to']) || !isset($edge['weight'])) {
            continue;
        }
        
        // Validate nodes exist
        if (!isset($nodes[$edge['from']]) || !isset($nodes[$edge['to']])) {
            continue;
        }
        
        if (!isset($adjList[$edge['from']])) {
            $adjList[$edge['from']] = [];
        }
        $adjList[$edge['from']][] = ['to' => $edge['to'], 'weight' => $edge['weight']];
    }
    
    // Initialize distances and previous nodes
    $distances = [];
    $previous = [];
    $unvisited = [];
    
    foreach ($nodes as $nodeId => $node) {
        $distances[$nodeId] = INF;
        $previous[$nodeId] = null;
        $unvisited[$nodeId] = true;
    }
    
    $distances[$start] = 0;
    
    while (!empty($unvisited)) {
        // Find node with minimum distance
        $current = null;
        $minDistance = INF;
        foreach ($unvisited as $nodeId => $unused) {
            if ($distances[$nodeId] < $minDistance) {
                $minDistance = $distances[$nodeId];
                $current = $nodeId;
            }
        }
        
        // If no more nodes or we've reached infinity, break
        if ($current === null || $minDistance === INF) {
            break;
        }
        
        // If we reached the destination, break early
        if ($current === $end) {
            break;
        }
        
        // Remove current from unvisited
        unset($unvisited[$current]);
        
        // Update distances to neighbors
        if (isset($adjList[$current])) {
            foreach ($adjList[$current] as $neighbor) {
                if (isset($unvisited[$neighbor['to']])) {
                    $alt = $distances[$current] + $neighbor['weight'];
                    if ($alt < $distances[$neighbor['to']]) {
                        $distances[$neighbor['to']] = $alt;
                        $previous[$neighbor['to']] = $current;
                    }
                }
            }
        }
    }
    
    // Check if path exists
    if ($distances[$end] === INF) {
        return null; // No path exists
    }
    
    // Reconstruct path
    $path = [];
    $current = $end;
    while ($current !== null) {
        array_unshift($path, $current);
        $current = $previous[$current];
    }
    
    // If path doesn't start with start node, no path exists
    if (empty($path) || $path[0] !== $start) {
        return null;
    }
    
    // Calculate total effort and collect edge details
    $totalEffort = 0;
    $pathEdges = [];
    for ($i = 0; $i < count($path) - 1; $i++) {
        $from = $path[$i];
        $to = $path[$i + 1];
        
        // Find the edge details
        foreach ($edges as $edge) {
            if (isset($edge['from']) && isset($edge['to']) && 
                $edge['from'] === $from && $edge['to'] === $to) {
                $totalEffort += $edge['weight'];
                $pathEdges[] = $edge;
                break;
            }
        }
    }
    
    // Collect detailed node information
    $pathNodes = [];
    foreach ($path as $nodeId) {
        $pathNodes[] = [
            'id' => $nodeId,
            'name' => $nodes[$nodeId]['name'] ?? 'Unknown',
            'type' => $nodes[$nodeId]['type'] ?? 'unknown',
            'effort' => $nodes[$nodeId]['effort'] ?? 0
        ];
    }
    
    return [
        'path' => $path,
        'effort' => $totalEffort,
        'nodes' => $pathNodes,
        'edges' => $pathEdges,
        'distance' => $distances[$end]
    ];
}
?>