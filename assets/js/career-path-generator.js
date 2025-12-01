// Function to get node color based on type
function getNodeColor(type) {
    switch(type) {
        case 'education': return '#3498db';
        case 'skill': return '#27ae60';
        case 'career': return '#e74c3c';
        default: return '#95a5a6';
    }
}

// Function to get enhanced node color with gradient effect
function getEnhancedNodeColor(type) {
    switch(type) {
        case 'education': return {
            background: 'radial-gradient(circle, #3498db, #1a5276)',
            border: '#1a5276',
            highlight: {
                background: 'radial-gradient(circle, #5dade2, #21618c)',
                border: '#21618c'
            }
        };
        case 'skill': return {
            background: 'radial-gradient(circle, #27ae60, #145214)',
            border: '#145214',
            highlight: {
                background: 'radial-gradient(circle, #58d68d, #186a3b)',
                border: '#186a3b'
            }
        };
        case 'career': return {
            background: 'radial-gradient(circle, #e74c3c, #922b21)',
            border: '#922b21',
            highlight: {
                background: 'radial-gradient(circle, #ec7063, #a93226)',
                border: '#a93226'
            }
        };
        default: return {
            background: 'radial-gradient(circle, #95a5a6, #566573)',
            border: '#566573',
            highlight: {
                background: 'radial-gradient(circle, #bac3c5, #626567)',
                border: '#626567'
            }
        };
    }
}

// Function to get node shape based on type
function getNodeShape(type) {
    switch(type) {
        case 'education': return 'database';
        case 'skill': return 'ellipse';
        case 'career': return 'box';
        default: return 'circle';
    }
}

// Function to get node title based on type
function getNodeTitle(label, type) {
    var title = '<div style="font-size: 14px; font-weight: bold; color: #000;">' + label + '</div>';
    title += '<div style="color: #000;">Type: ' + type.charAt(0).toUpperCase() + type.slice(1) + '</div>';
    return title;
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
            
            // Add all nodes
            for (var nodeId in graph.nodes) {
                if (graph.nodes.hasOwnProperty(nodeId)) {
                    var node = graph.nodes[nodeId];
                    allNodes.add({
                        id: nodeId,
                        label: node.name,
                        title: getNodeTitle(node.name, node.type),
                        color: getEnhancedNodeColor(node.type),
                        shape: getNodeShape(node.type),
                        font: { 
                            color: '#000', 
                            size: 14, 
                            face: 'Segoe UI, Arial, sans-serif',
                            strokeWidth: 2,
                            strokeColor: '#fff'
                        },
                        borderWidth: 2,
                        size: 30,
                        shadow: {
                            enabled: true,
                            color: 'rgba(0,0,0,0.2)',
                            size: 6,
                            x: 2,
                            y: 2
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
                        color: 'rgba(52, 152, 219, 0.4)',
                        highlight: 'rgba(41, 128, 185, 0.8)'
                    },
                    width: 2,
                    title: '<div style="font-size: 14px; font-weight: bold; color: #2c3e50; padding: 3px;">Transition</div><div style="color: #34495e; padding: 2px;">From: ' + fromNode.name + '</div><div style="color: #34495e; padding: 2px;">To: ' + toNode.name + '</div>',
                    font: { 
                        color: '#2c3e50', 
                        size: 12, 
                        face: 'Segoe UI, Arial, sans-serif',
                        strokeWidth: 1,
                        strokeColor: '#fff'
                    },
                    smooth: { type: 'dynamic' },
                    shadow: {
                        enabled: true,
                        color: 'rgba(52, 152, 219, 0.2)',
                        size: 3,
                        x: 1,
                        y: 1
                    }
                });
            }
            
            // Network options for full network
            var fullOptions = {
                nodes: {
                    shape: 'dot',
                    size: 30,
                    font: {
                        size: 14,
                        face: 'Segoe UI, Arial, sans-serif',
                        color: '#000',
                        bold: true,
                        strokeWidth: 2,
                        strokeColor: '#fff'
                    },
                    borderWidth: 2,
                    shadow: {
                        enabled: true,
                        color: 'rgba(0,0,0,0.3)',
                        size: 8,
                        x: 3,
                        y: 3
                    },
                    scaling: {
                        min: 15,
                        max: 40
                    }
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
                    smooth: {
                        type: 'dynamic'
                    },
                    shadow: {
                        enabled: true,
                        color: 'rgba(0,0,0,0.2)',
                        size: 4,
                        x: 1,
                        y: 1
                    },
                    color: {
                        color: 'rgba(52, 152, 219, 0.4)',
                        highlight: 'rgba(41, 128, 185, 0.8)'
                    }
                },
                physics: {
                    enabled: true,
                    stabilization: {
                        iterations: 1500,
                        fit: true
                    },
                    repulsion: {
                        nodeDistance: 180,
                        centralGravity: 0.2,
                        springLength: 180,
                        springConstant: 0.05
                    },
                    solver: 'repulsion',
                    timestep: 0.5,
                    adaptiveTimestep: true
                },
                interaction: {
                    hover: true,
                    tooltipDelay: 200,
                    navigationButtons: true,
                    keyboard: true,
                    multiselect: true,
                    hoverConnectedEdges: true,
                    selectConnectedEdges: true
                },
                layout: {
                    improvedLayout: true,
                    hierarchical: {
                        enabled: true,
                        direction: 'LR',
                        sortMethod: 'directed',
                        nodeSpacing: 200,
                        treeSpacing: 300,
                        blockShifting: true,
                        edgeMinimization: true,
                        parentCentralization: true
                    }
                },
                animation: {
                    duration: 500,
                    easingFunction: 'easeInOutQuad'
                }
            };
            
            // Create network for full career graph
            var fullContainer = document.getElementById('fullNetwork');
            if (fullContainer) {
                console.log('Creating full network with data:', { nodes: allNodes, edges: allEdges });
                console.log('Full network options:', fullOptions);
                var fullData = {
                    nodes: allNodes,
                    edges: allEdges
                };
                var fullNetwork = new vis.Network(fullContainer, fullData, fullOptions);
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
            
            // Populate path nodes
            for (var i = 0; i < pathResult.path.length; i++) {
                var nodeId = pathResult.path[i];
                var node = graph.nodes[nodeId];
                pathNodes.add({
                    id: nodeId,
                    label: node.name,
                    title: getNodeTitle(node.name, node.type),
                    color: getEnhancedNodeColor(node.type),
                    shape: getNodeShape(node.type),
                    font: { 
                        color: '#000', 
                        size: 18, 
                        face: 'Segoe UI, Arial, sans-serif', 
                        bold: true,
                        strokeWidth: 3,
                        strokeColor: '#fff'
                    },
                    borderWidth: 4,
                    size: 45,
                    shadow: {
                        enabled: true,
                        color: 'rgba(0,0,0,0.5)',
                        size: 15,
                        x: 5,
                        y: 5
                    },
                    scaling: {
                        min: 20,
                        max: 60
                    }
                });
            }
            
            // Populate path edges
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
                            scaleFactor: 1.5
                        }
                    },
                    color: { 
                        color: 'rgba(231, 76, 60, 0.8)', 
                        highlight: 'rgba(192, 57, 43, 1)',
                        opacity: 0.8
                    },
                    width: 6,
                    title: '<div style="font-size: 16px; font-weight: bold; color: #2c3e50; padding: 5px;">Transition</div><div style="color: #34495e; padding: 3px;">From: ' + fromNode.name + '</div><div style="color: #34495e; padding: 3px;">To: ' + toNode.name + '</div><div style="color: #e74c3c; font-weight: bold; padding: 3px;">Effort: ' + transitionEffort + '</div>',
                    font: { 
                        color: '#2c3e50', 
                        size: 16, 
                        face: 'Segoe UI, Arial, sans-serif',
                        strokeWidth: 2,
                        strokeColor: '#fff'
                    },
                    smooth: { 
                        type: 'dynamic',
                        roundness: 0.5
                    },
                    shadow: {
                        enabled: true,
                        color: 'rgba(231, 76, 60, 0.3)',
                        size: 8,
                        x: 2,
                        y: 2
                    }
                });
            }
            
            // Network options for path visualization
            var pathOptions = {
                nodes: {
                    shape: 'dot',
                    size: 45,
                    font: {
                        size: 18,
                        face: 'Segoe UI, Arial, sans-serif',
                        color: '#000',
                        bold: true,
                        strokeWidth: 3,
                        strokeColor: '#fff'
                    },
                    borderWidth: 4,
                    shadow: {
                        enabled: true,
                        color: 'rgba(0,0,0,0.5)',
                        size: 15,
                        x: 5,
                        y: 5
                    },
                    scaling: {
                        min: 20,
                        max: 60
                    },
                    animation: {
                        scale: 1.2,
                        duration: 300
                    }
                },
                edges: {
                    width: 6,
                    arrows: {
                        to: { 
                            enabled: true, 
                            scaleFactor: 1.5,
                            type: 'arrow'
                        }
                    },
                    smooth: {
                        type: 'dynamic',
                        roundness: 0.5
                    },
                    shadow: {
                        enabled: true,
                        color: 'rgba(0,0,0,0.3)',
                        size: 8,
                        x: 2,
                        y: 2
                    },
                    color: {
                        color: 'rgba(231, 76, 60, 0.8)',
                        highlight: 'rgba(192, 57, 43, 1)',
                        opacity: 0.8
                    }
                },
                physics: {
                    enabled: true,
                    stabilization: {
                        iterations: 1500,
                        fit: true,
                        updateInterval: 50
                    },
                    repulsion: {
                        nodeDistance: 300,
                        centralGravity: 0.1,
                        springLength: 300,
                        springConstant: 0.05
                    },
                    solver: 'repulsion',
                    timestep: 0.3,
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
                    zoomView: true,
                    dragNodes: true
                },
                layout: {
                    improvedLayout: true,
                    hierarchical: {
                        enabled: true,
                        direction: 'LR',
                        sortMethod: 'directed',
                        nodeSpacing: 250,
                        treeSpacing: 350,
                        blockShifting: true,
                        edgeMinimization: true,
                        parentCentralization: true
                    }
                },
                animation: {
                    duration: 1000,
                    easingFunction: 'easeInOutQuad'
                }
            };
            
            // Create network for optimal path
            var pathContainer = document.getElementById('network');
            if (pathContainer) {
                console.log('Creating path network with data:', { nodes: pathNodes, edges: pathEdges });
                console.log('Path network options:', pathOptions);
                var pathData = {
                    nodes: pathNodes,
                    edges: pathEdges
                };
                var pathNetwork = new vis.Network(pathContainer, pathData, pathOptions);
                console.log('Path network created successfully');
            }
            
            // Highlight path in full network
            // Create network for full career graph
            var allNodes = new vis.DataSet([]);
            var allEdges = new vis.DataSet([]);
            
            // Add all nodes
            for (var nodeId in graph.nodes) {
                if (graph.nodes.hasOwnProperty(nodeId)) {
                    var node = graph.nodes[nodeId];
                    allNodes.add({
                        id: nodeId,
                        label: node.name,
                        title: getNodeTitle(node.name, node.type),
                        color: getEnhancedNodeColor(node.type),
                        shape: getNodeShape(node.type),
                        font: { 
                            color: '#000', 
                            size: 14, 
                            face: 'Segoe UI, Arial, sans-serif',
                            strokeWidth: 2,
                            strokeColor: '#fff'
                        },
                        borderWidth: 2,
                        size: 30,
                        shadow: {
                            enabled: true,
                            color: 'rgba(0,0,0,0.2)',
                            size: 6,
                            x: 2,
                            y: 2
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
                        color: 'rgba(52, 152, 219, 0.4)',
                        highlight: 'rgba(41, 128, 185, 0.8)'
                    },
                    width: 2,
                    title: '<div style="font-size: 14px; font-weight: bold; color: #2c3e50; padding: 3px;">Transition</div><div style="color: #34495e; padding: 2px;">From: ' + fromNode.name + '</div><div style="color: #34495e; padding: 2px;">To: ' + toNode.name + '</div>',
                    font: { 
                        color: '#2c3e50', 
                        size: 12, 
                        face: 'Segoe UI, Arial, sans-serif',
                        strokeWidth: 1,
                        strokeColor: '#fff'
                    },
                    smooth: { type: 'dynamic' },
                    shadow: {
                        enabled: true,
                        color: 'rgba(52, 152, 219, 0.2)',
                        size: 3,
                        x: 1,
                        y: 1
                    }
                });
            }
            
            // Highlight path nodes in full network
            for (var i = 0; i < pathResult.path.length; i++) {
                var nodeId = pathResult.path[i];
                var node = graph.nodes[nodeId];
                allNodes.update({
                    id: nodeId,
                    color: getEnhancedNodeColor(node.type),
                    size: 60,
                    font: { 
                        color: '#000', 
                        size: 20, 
                        bold: true,
                        strokeWidth: 4,
                        strokeColor: '#fff'
                    },
                    borderWidth: 6,
                    shadow: {
                        enabled: true,
                        color: 'rgba(231, 76, 60, 0.9)',
                        size: 25,
                        x: 6,
                        y: 6
                    }
                });
            }
            
            // Highlight path edges in full network
            for (var i = 0; i < pathResult.path.length - 1; i++) {
                var from = pathResult.path[i];
                var to = pathResult.path[i + 1];
                
                // Find the edge in allEdges
                for (var j = 0; j < graph.edges.length; j++) {
                    var edge = graph.edges[j];
                    if (edge.from === from && edge.to === to) {
                        allEdges.update({
                            from: edge.from,
                            to: edge.to,
                            color: {
                                color: 'rgba(231, 76, 60, 0.9)',
                                highlight: 'rgba(192, 57, 43, 1)'
                            },
                            width: 8,
                            arrows: {
                                to: {
                                    enabled: true,
                                    type: 'arrow',
                                    scaleFactor: 1.2
                                }
                            },
                            smooth: { type: 'dynamic', roundness: 0.5 },
                            shadow: {
                                enabled: true,
                                color: 'rgba(231, 76, 60, 0.5)',
                                size: 10,
                                x: 3,
                                y: 3
                            }
                        });
                        break;
                    }
                }
            }
            
            // Network options for full network
            var fullOptions = {
                nodes: {
                    shape: 'dot',
                    size: 30,
                    font: {
                        size: 14,
                        face: 'Segoe UI, Arial, sans-serif',
                        color: '#000',
                        bold: true,
                        strokeWidth: 2,
                        strokeColor: '#fff'
                    },
                    borderWidth: 2,
                    shadow: {
                        enabled: true,
                        color: 'rgba(0,0,0,0.3)',
                        size: 8,
                        x: 3,
                        y: 3
                    },
                    scaling: {
                        min: 15,
                        max: 40
                    }
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
                    smooth: {
                        type: 'dynamic'
                    },
                    shadow: {
                        enabled: true,
                        color: 'rgba(0,0,0,0.2)',
                        size: 4,
                        x: 1,
                        y: 1
                    },
                    color: {
                        color: 'rgba(52, 152, 219, 0.4)',
                        highlight: 'rgba(41, 128, 185, 0.8)'
                    }
                },
                physics: {
                    enabled: true,
                    stabilization: {
                        iterations: 2000,
                        fit: true,
                        updateInterval: 50
                    },
                    repulsion: {
                        nodeDistance: 250,
                        centralGravity: 0.1,
                        springLength: 250,
                        springConstant: 0.05
                    },
                    solver: 'repulsion',
                    timestep: 0.3,
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
                    zoomView: true,
                    dragNodes: true
                },
                layout: {
                    improvedLayout: true,
                    hierarchical: {
                        enabled: true,
                        direction: 'LR',
                        sortMethod: 'directed',
                        nodeSpacing: 200,
                        treeSpacing: 300,
                        blockShifting: true,
                        edgeMinimization: true,
                        parentCentralization: true
                    }
                },
                animation: {
                    duration: 1000,
                    easingFunction: 'easeInOutQuad'
                }
            };
            
            // Create network for full career graph
            var fullContainer = document.getElementById('fullNetwork');
            if (fullContainer) {
                console.log('Creating full network with data:', { nodes: allNodes, edges: allEdges });
                console.log('Full network options:', fullOptions);
                var fullData = {
                    nodes: allNodes,
                    edges: allEdges
                };
                var fullNetwork = new vis.Network(fullContainer, fullData, fullOptions);
                console.log('Full network created successfully');
            }
        }
    } catch (error) {
        console.error('Error initializing career path generator:', error);
    }
});