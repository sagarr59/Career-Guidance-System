// Function to get node color based on type
function getNodeColor(type) {
    switch(type) {
        case 'education': return '#3498db'; // Blue
        case 'skill': return '#27ae60';     // Green
        case 'career': return '#e74c3c';    // Red
        default: return '#95a5a6';
    }
}

// Function to get simple node color (without gradients)
function getSimpleNodeColor(type) {
    switch(type) {
        case 'education': return '#3498db';
        case 'skill': return '#27ae60';
        case 'career': return '#e74c3c';
        default: return '#95a5a6';
    }
}

// Function to get node shape based on type - with diamond shape for careers
function getNodeShape(type) {
    switch(type) {
        case 'education': return 'database'; // Cylinder shape for education
        case 'skill': return 'circle';       // Circle for skills
        case 'career': return 'diamond';     // Diamond for careers
        default: return 'ellipse';
    }
}

// Initialize network visualizations when the page loads
document.addEventListener('DOMContentLoaded', function() {
    try {
        // Always initialize the full network, even if no path result
        if (typeof graph !== 'undefined' && graph !== null) {
            console.log('Graph data:', graph);
            
            // Create network for full career graph
            var allNodes = new vis.DataSet([]);
            var allEdges = new vis.DataSet([]);
            
            // Add all nodes with proper shapes and uniform sizing
            for (var nodeId in graph.nodes) {
                if (graph.nodes.hasOwnProperty(nodeId)) {
                    var node = graph.nodes[nodeId];
                    
                    allNodes.add({
                        id: nodeId,
                        label: node.name, // Show label on node
                        title: node.name + '\n(' + node.type.charAt(0).toUpperCase() + node.type.slice(1) + ')',
                        color: {
                            background: getSimpleNodeColor(node.type),
                            border: '#000000',
                            highlight: {
                                background: getSimpleNodeColor(node.type),
                                border: '#000000'
                            }
                        },
                        shape: getNodeShape(node.type),
                        font: { 
                            color: '#000000', // Black text for all nodes
                            size: 12, // Show internal labels with smaller font for fitting
                            face: 'Segoe UI, Arial, sans-serif',
                            strokeWidth: 0,
                            multi: 'html' // Allow multi-line text
                        },
                        borderWidth: 2,
                        size: 30, // Uniform size for all nodes
                        labelHighlightBold: true,
                        chosen: {
                            node: function(values, id, selected, hovering) {
                                // Custom function to ensure labels stay inside nodes
                                values.label = values.label || '';
                            }
                        }
                    });
                }
            }
            
            // Add all edges
            for (var i = 0; i < graph.edges.length; i++) {
                var edge = graph.edges[i];
                var fromNode = graph.nodes[edge.from];
                var toNode = graph.nodes[edge.to];
                allEdges.add({
                    from: edge.from,
                    to: edge.to,
                    arrows: {
                        to: {
                            enabled: true,
                            type: 'arrow',
                            scaleFactor: 0.8
                        }
                    },
                    color: { 
                        color: 'rgba(52, 152, 219, 0.7)',
                        highlight: 'rgba(41, 128, 185, 1)'
                    },
                    width: 2,
                    title: fromNode.name + ' → ' + toNode.name,
                    font: { 
                        color: '#000000', // Black text for all edges
                        size: 12, 
                        face: 'Segoe UI, Arial, sans-serif'
                    },
                    smooth: false
                });
            }
            
            // Network options for full network - Enhanced layout to prevent overlapping
            var fullOptions = {
                nodes: {
                    shape: 'diamond',
                    size: 30,
                    font: {
                        color: '#000000', // Black text for all nodes
                        size: 12, // Show internal labels
                        face: 'Segoe UI, Arial, sans-serif',
                        strokeWidth: 0,
                        multi: 'html' // Allow multi-line text
                    },
                    borderWidth: 2,
                    scaling: {
                        min: 15,
                        max: 40
                    },
                    labelHighlightBold: true
                },
                edges: {
                    width: 2,
                    arrows: {
                        to: { 
                            enabled: true, 
                            scaleFactor: 1.0,
                            type: 'arrow'
                        }
                    },
                    smooth: false,
                    color: {
                        color: 'rgba(52, 152, 219, 0.7)',
                        highlight: 'rgba(41, 128, 185, 1)'
                    },
                    font: {
                        color: '#000000' // Black text for all edges
                    }
                },
                physics: {
                    enabled: true,
                    stabilization: {
                        iterations: 2000, // More iterations for better layout
                        fit: true
                    },
                    repulsion: {
                        nodeDistance: 150, // Increased distance to prevent overlapping
                        centralGravity: 0.1,
                        springLength: 200,
                        springConstant: 0.01
                    },
                    solver: 'repulsion',
                    timestep: 0.1, // Slower physics for smoother stabilization
                    adaptiveTimestep: true
                },
                interaction: {
                    hover: true,
                    tooltipDelay: 200,
                    navigationButtons: true,
                    keyboard: true,
                    multiselect: true,
                    hoverConnectedEdges: true,
                    selectConnectedEdges: true,
                    dragNodes: true // Enable dragging
                },
                layout: {
                    improvedLayout: true,
                    hierarchical: {
                        enabled: false
                    }
                },
                animation: {
                    duration: 1000, // Smooth animations
                    easingFunction: 'easeInOutQuad'
                }
            };
            
            // Create network for full career graph
            var fullContainer = document.getElementById('fullNetwork');
            var fullNetwork;
            if (fullContainer) {
                console.log('Creating full network with data:', { nodes: allNodes, edges: allEdges });
                console.log('Full network options:', fullOptions);
                var fullData = {
                    nodes: allNodes,
                    edges: allEdges
                };
                fullNetwork = new vis.Network(fullContainer, fullData, fullOptions);
                
                // Disable physics after stabilization to prevent movement but keep dragging
                fullNetwork.once("stabilizationIterationsDone", function() {
                    fullNetwork.setOptions({ 
                        physics: false,
                        interaction: {
                            dragNodes: true // Keep dragging enabled
                        }
                    });
                });
                
                // Add smooth hover animations
                fullNetwork.on("hoverNode", function (params) {
                    // Scale up node on hover with animation
                    fullNetwork.body.data.nodes.update({
                        id: params.node,
                        size: 40
                    });
                });
                
                fullNetwork.on("blurNode", function (params) {
                    // Return to normal size when not hovering
                    fullNetwork.body.data.nodes.update({
                        id: params.node,
                        size: 30
                    });
                });
                
                // Add coordinated movement when dragging nodes
                fullNetwork.on("dragStart", function (params) {
                    // When dragging starts, enable physics temporarily for coordinated movement
                    fullNetwork.setOptions({
                        physics: {
                            enabled: true,
                            stabilization: {
                                iterations: 100
                            },
                            repulsion: {
                                nodeDistance: 150,
                                centralGravity: 0.1,
                                springLength: 200,
                                springConstant: 0.01
                            },
                            solver: 'repulsion'
                        }
                    });
                });
                
                fullNetwork.on("dragEnd", function (params) {
                    // When dragging ends, disable physics again
                    setTimeout(function() {
                        fullNetwork.setOptions({
                            physics: {
                                enabled: false
                            }
                        });
                    }, 500);
                });
                
                console.log('Full network created successfully');
            }
        }
        
        // Check if we have path result data
        if (typeof pathResult !== 'undefined' && pathResult !== null && typeof graph !== 'undefined' && graph !== null) {
            console.log('Path result data:', pathResult);
            console.log('Graph data:', graph);
            
            // Create nodes for optimal path
            var pathNodes = new vis.DataSet([]);
            var pathEdges = new vis.DataSet([]);
            
            // Populate path nodes with proper shapes and uniform sizing
            for (var i = 0; i < pathResult.path.length; i++) {
                var nodeId = pathResult.path[i];
                var node = graph.nodes[nodeId];
                
                pathNodes.add({
                    id: nodeId,
                    label: node.name, // Show label on node
                    title: node.name + '\n(' + node.type.charAt(0).toUpperCase() + node.type.slice(1) + ')',
                    color: {
                        background: getSimpleNodeColor(node.type),
                        border: '#000000',
                        highlight: {
                            background: getSimpleNodeColor(node.type),
                            border: '#000000'
                        }
                    },
                    shape: getNodeShape(node.type),
                    font: { 
                        color: '#000000', // Black text for all nodes
                        size: 14, // Show internal labels with slightly larger font
                        face: 'Segoe UI, Arial, sans-serif',
                        bold: true,
                        strokeWidth: 0,
                        multi: 'html' // Allow multi-line text
                    },
                    borderWidth: 3,
                    size: 45, // Uniform size for all path nodes
                    labelHighlightBold: true,
                    chosen: {
                        node: function(values, id, selected, hovering) {
                            // Custom function to ensure labels stay inside nodes
                            values.label = values.label || '';
                        }
                    }
                });
            }
            
            // Populate path edges with highlighting
            for (var i = 0; i < pathResult.path.length - 1; i++) {
                var from = pathResult.path[i];
                var to = pathResult.path[i + 1];
                var fromNode = graph.nodes[from];
                var toNode = graph.nodes[to];
                
                // Calculate effort for this transition if available
                var transitionEffort = 0;
                if (pathResult.edges && pathResult.edges[i]) {
                    transitionEffort = pathResult.edges[i].weight || 0;
                }
                
                pathEdges.add({
                    from: from,
                    to: to,
                    arrows: {
                        to: {
                            enabled: true,
                            type: 'arrow',
                            scaleFactor: 1.2
                        }
                    },
                    color: { 
                        color: 'rgba(231, 76, 60, 1)', // Solid red for path edges
                        highlight: 'rgba(192, 57, 43, 1)'
                    },
                    width: 4,
                    title: fromNode.name + ' → ' + toNode.name + ' (Effort: ' + transitionEffort + ')',
                    font: { 
                        color: '#000000', // Black text for all edges
                        size: 14, 
                        face: 'Segoe UI, Arial, sans-serif'
                    },
                    smooth: false
                });
            }
            
            // Network options for path visualization - Enhanced layout to prevent overlapping
            var pathOptions = {
                nodes: {
                    shape: 'diamond',
                    size: 45,
                    font: {
                        color: '#000000', // Black text for all nodes
                        size: 14, // Show internal labels
                        face: 'Segoe UI, Arial, sans-serif',
                        bold: true,
                        strokeWidth: 0,
                        multi: 'html' // Allow multi-line text
                    },
                    borderWidth: 3,
                    scaling: {
                        min: 20,
                        max: 60
                    },
                    labelHighlightBold: true
                },
                edges: {
                    width: 4,
                    arrows: {
                        to: { 
                            enabled: true, 
                            scaleFactor: 1.2,
                            type: 'arrow'
                        }
                    },
                    smooth: false,
                    color: {
                        color: 'rgba(231, 76, 60, 1)', // Solid red
                        highlight: 'rgba(192, 57, 43, 1)'
                    },
                    font: {
                        color: '#000000' // Black text for all edges
                    }
                },
                physics: {
                    enabled: true,
                    stabilization: {
                        iterations: 2000, // More iterations for better layout
                        fit: true
                    },
                    repulsion: {
                        nodeDistance: 200, // Increased distance to prevent overlapping
                        centralGravity: 0.1,
                        springLength: 250,
                        springConstant: 0.01
                    },
                    solver: 'repulsion',
                    timestep: 0.1, // Slower physics for smoother stabilization
                    adaptiveTimestep: true
                },
                interaction: {
                    hover: true,
                    tooltipDelay: 200,
                    navigationButtons: true,
                    keyboard: true,
                    multiselect: true,
                    hoverConnectedEdges: true,
                    selectConnectedEdges: true,
                    dragNodes: true // Enable dragging
                },
                layout: {
                    improvedLayout: true,
                    hierarchical: {
                        enabled: false
                    }
                },
                animation: {
                    duration: 1000, // Smooth animations
                    easingFunction: 'easeInOutQuad'
                }
            };
            
            // Create network for optimal path
            var pathContainer = document.getElementById('network');
            var pathNetwork;
            if (pathContainer) {
                console.log('Creating path network with data:', { nodes: pathNodes, edges: pathEdges });
                console.log('Path network options:', pathOptions);
                var pathData = {
                    nodes: pathNodes,
                    edges: pathEdges
                };
                pathNetwork = new vis.Network(pathContainer, pathData, pathOptions);
                
                // Disable physics after stabilization to prevent movement but keep dragging
                pathNetwork.once("stabilizationIterationsDone", function() {
                    pathNetwork.setOptions({ 
                        physics: false,
                        interaction: {
                            dragNodes: true // Keep dragging enabled
                        }
                    });
                });
                
                // Add smooth hover animations
                pathNetwork.on("hoverNode", function (params) {
                    // Scale up node on hover with animation
                    pathNetwork.body.data.nodes.update({
                        id: params.node,
                        size: 55
                    });
                });
                
                pathNetwork.on("blurNode", function (params) {
                    // Return to normal size when not hovering
                    pathNetwork.body.data.nodes.update({
                        id: params.node,
                        size: 45
                    });
                });
                
                console.log('Path network created successfully');
            }
            
            // Also highlight the path in the full network with YELLOW highlighting and glow effects
            if (typeof fullNetwork !== 'undefined') {
                // Highlight path nodes in full network with yellow color and glow
                for (var i = 0; i < pathResult.path.length; i++) {
                    var nodeId = pathResult.path[i];
                    fullNetwork.body.data.nodes.update({
                        id: nodeId,
                        color: {
                            background: '#FFD700', // Yellow background
                            border: '#FFD700', // Yellow border
                            highlight: {
                                background: '#FFD700',
                                border: '#FFD700'
                            }
                        },
                        size: 50, // Same size as regular nodes to maintain consistency
                        font: { 
                            color: '#000000', // Black text for better contrast on yellow
                            size: 12, // Show internal labels
                            bold: true,
                            strokeWidth: 0,
                            multi: 'html' // Allow multi-line text
                        },
                        borderWidth: 4,
                        shadow: {
                            enabled: true,
                            color: 'rgba(255, 215, 0, 0.5)', // Yellow glow
                            size: 15,
                            x: 5,
                            y: 5
                        },
                        labelHighlightBold: true,
                        chosen: {
                            node: function(values, id, selected, hovering) {
                                // Custom function to ensure labels stay inside nodes
                                values.label = values.label || '';
                            }
                        }
                    });
                }
                
                // Highlight path edges in full network with glow effect
                for (var i = 0; i < graph.edges.length; i++) {
                    var edge = graph.edges[i];
                    // Check if this edge is part of the path
                    var isPathEdge = false;
                    for (var j = 0; j < pathResult.path.length - 1; j++) {
                        if (edge.from === pathResult.path[j] && edge.to === pathResult.path[j + 1]) {
                            isPathEdge = true;
                            break;
                        }
                    }
                    
                    if (isPathEdge) {
                        // Create a unique ID for the edge
                        var edgeId = edge.from + "_" + edge.to;
                        fullNetwork.body.data.edges.update({
                            id: edgeId,
                            from: edge.from,
                            to: edge.to,
                            color: {
                                color: '#FFD700', // Yellow for path edges
                                highlight: '#FFD700'
                            },
                            width: 6, // Consistent width
                            arrows: {
                                to: {
                                    enabled: true,
                                    type: 'arrow',
                                    scaleFactor: 1.5
                                }
                            },
                            shadow: {
                                enabled: true,
                                color: 'rgba(255, 215, 0, 0.7)', // Yellow glow
                                size: 10,
                                x: 3,
                                y: 3
                            },
                            font: {
                                color: '#000000' // Black text for all edges
                            }
                        });
                    }
                }
            }
        }
    } catch (error) {
        console.error('Error initializing career path generator:', error);
    }
});