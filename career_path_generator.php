<?php
session_start();
include 'header.php';
include 'db.php';

// Check if user is logged in
if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit;
}

// Function to build the career graph
function buildCareerGraph() {
    // Define nodes (career path elements)
    $nodes = [
        // Education levels
        'edu_secondary' => ['name' => '+2 Level', 'type' => 'education'],
        'edu_bachelor' => ['name' => 'Bachelor Degree', 'type' => 'education'],
        'edu_master' => ['name' => 'Master Degree', 'type' => 'education'],
        'edu_phd' => ['name' => 'PhD', 'type' => 'education'],
        
        // Basic skills
        'basic_computer' => ['name' => 'Basic Computer Skills', 'type' => 'skill'],
        'english_comm' => ['name' => 'English Communication', 'type' => 'skill'],
        'math_fundamentals' => ['name' => 'Math Fundamentals', 'type' => 'skill'],
        
        // IT Skills
        'html_css' => ['name' => 'HTML/CSS', 'type' => 'skill'],
        'javascript' => ['name' => 'JavaScript', 'type' => 'skill'],
        'php' => ['name' => 'PHP', 'type' => 'skill'],
        'java' => ['name' => 'Java', 'type' => 'skill'],
        'database' => ['name' => 'Database Management', 'type' => 'skill'],
        'laravel' => ['name' => 'Laravel Framework', 'type' => 'skill'],
        'react' => ['name' => 'React.js', 'type' => 'skill'],
        'nodejs' => ['name' => 'Node.js', 'type' => 'skill'],
        'python' => ['name' => 'Python', 'type' => 'skill'],
        'data_science' => ['name' => 'Data Science', 'type' => 'skill'],
        'mobile_dev' => ['name' => 'Mobile Development', 'type' => 'skill'],
        'networking' => ['name' => 'Networking', 'type' => 'skill'],
        'cybersecurity' => ['name' => 'Cybersecurity', 'type' => 'skill'],
        'cloud_computing' => ['name' => 'Cloud Computing', 'type' => 'skill'],
        'devops' => ['name' => 'DevOps', 'type' => 'skill'],
        'ml_engineering' => ['name' => 'Machine Learning Engineering', 'type' => 'skill'],
        'blockchain' => ['name' => 'Blockchain', 'type' => 'skill'],
        'iot' => ['name' => 'Internet of Things', 'type' => 'skill'],
        'game_dev' => ['name' => 'Game Development', 'type' => 'skill'],
        
        // Design Skills
        'graphic_design' => ['name' => 'Graphic Design', 'type' => 'skill'],
        'ui_ux' => ['name' => 'UI/UX Design', 'type' => 'skill'],
        'video_editing' => ['name' => 'Video Editing', 'type' => 'skill'],
        'animation' => ['name' => 'Animation', 'type' => 'skill'],
        
        // Business Skills
        'business_fundamentals' => ['name' => 'Business Fundamentals', 'type' => 'skill'],
        'marketing' => ['name' => 'Digital Marketing', 'type' => 'skill'],
        'project_management' => ['name' => 'Project Management', 'type' => 'skill'],
        'financial_analysis' => ['name' => 'Financial Analysis', 'type' => 'skill'],
        'entrepreneurship' => ['name' => 'Entrepreneurship', 'type' => 'skill'],
        
        // Healthcare Skills
        'basic_healthcare' => ['name' => 'Basic Healthcare', 'type' => 'skill'],
        'patient_care' => ['name' => 'Patient Care', 'type' => 'skill'],
        'medical_research' => ['name' => 'Medical Research', 'type' => 'skill'],
        
        // Media Skills
        'content_writing' => ['name' => 'Content Writing', 'type' => 'skill'],
        'social_media' => ['name' => 'Social Media Management', 'type' => 'skill'],
        'photography' => ['name' => 'Photography', 'type' => 'skill'],
        
        // Additional Skills for intermediate paths
        'computer_science' => ['name' => 'Computer Science', 'type' => 'skill'],
        'advanced_cs' => ['name' => 'Advanced Computer Science', 'type' => 'skill'],
        'research_methods' => ['name' => 'Research Methods', 'type' => 'skill'],
        'product_management' => ['name' => 'Product Management', 'type' => 'skill'],
        'scientific_research' => ['name' => 'Scientific Research', 'type' => 'skill'],
        'academic_research' => ['name' => 'Academic Research', 'type' => 'skill'],
        'data_engineering' => ['name' => 'Data Engineering', 'type' => 'skill'],
        'digital_marketing' => ['name' => 'Digital Marketing', 'type' => 'skill'],
        

        // Career roles
        'junior_dev' => ['name' => 'Junior Developer', 'type' => 'career'],
        'web_dev' => ['name' => 'Web Developer', 'type' => 'career'],
        'backend_dev' => ['name' => 'Backend Developer', 'type' => 'career'],
        'fullstack_dev' => ['name' => 'Full Stack Developer', 'type' => 'career'],
        'software_eng' => ['name' => 'Software Engineer', 'type' => 'career'],
        'data_scientist' => ['name' => 'Data Scientist', 'type' => 'career'],
        'ui_ux_designer' => ['name' => 'UI/UX Designer', 'type' => 'career'],
        'project_manager' => ['name' => 'Project Manager', 'type' => 'career'],
        'app_developer' => ['name' => 'App Developer', 'type' => 'career'],
        'security_analyst' => ['name' => 'Security Analyst', 'type' => 'career'],
        'cloud_architect' => ['name' => 'Cloud Architect', 'type' => 'career'],
        'devops_engineer' => ['name' => 'DevOps Engineer', 'type' => 'career'],
        'ml_engineer' => ['name' => 'Machine Learning Engineer', 'type' => 'career'],
        'digital_marketer' => ['name' => 'Digital Marketer', 'type' => 'career'],
        'financial_analyst' => ['name' => 'Financial Analyst', 'type' => 'career'],
        'nurse' => ['name' => 'Registered Nurse', 'type' => 'career'],
        'content_creator' => ['name' => 'Content Creator', 'type' => 'career'],
        'social_media_manager' => ['name' => 'Social Media Manager', 'type' => 'career'],
        'cto' => ['name' => 'Chief Technology Officer', 'type' => 'career'],
        'product_manager' => ['name' => 'Product Manager', 'type' => 'career'],
        'data_engineer' => ['name' => 'Data Engineer', 'type' => 'career'],
        'game_developer' => ['name' => 'Game Developer', 'type' => 'career'],
        'blockchain_dev' => ['name' => 'Blockchain Developer', 'type' => 'career'],
        'iot_engineer' => ['name' => 'IoT Engineer', 'type' => 'career'],
        'cybersecurity_expert' => ['name' => 'Cybersecurity Expert', 'type' => 'career'],
        'research_scientist' => ['name' => 'Research Scientist', 'type' => 'career'],
        'professor' => ['name' => 'Professor', 'type' => 'career'],
        'chief_scientist' => ['name' => 'Chief Scientist', 'type' => 'career'],
    ];
    
    // Define edges (connections between nodes with weights)
    $edges = [
        // Education paths
        ['from' => 'edu_secondary', 'to' => 'edu_bachelor', 'weight' => 10],
        ['from' => 'edu_bachelor', 'to' => 'edu_master', 'weight' => 15],
        ['from' => 'edu_master', 'to' => 'edu_phd', 'weight' => 20],
        
        // Direct paths from +2 to skills (realistic for students who can start learning skills early)
        ['from' => 'edu_secondary', 'to' => 'basic_computer', 'weight' => 2],
        ['from' => 'edu_secondary', 'to' => 'english_comm', 'weight' => 3],
        ['from' => 'edu_secondary', 'to' => 'math_fundamentals', 'weight' => 4],
        ['from' => 'edu_secondary', 'to' => 'basic_healthcare', 'weight' => 5],
        ['from' => 'edu_secondary', 'to' => 'content_writing', 'weight' => 4],
        ['from' => 'edu_secondary', 'to' => 'graphic_design', 'weight' => 5],
        ['from' => 'edu_secondary', 'to' => 'social_media', 'weight' => 4],
        
        // Direct paths from +2 to certifications (some certifications can be started early)
        // ['from' => 'edu_secondary', 'to' => 'cert_basic_it', 'weight' => 3],
        
        // Direct paths from +2 to entry-level careers (realistic for some careers)
        // Modified to ensure at least 3 nodes in each path by adding intermediate steps
        ['from' => 'edu_secondary', 'to' => 'content_writing', 'weight' => 4], // First step
        ['from' => 'content_writing', 'to' => 'content_creator', 'weight' => 5], // Second step to final career
        
        ['from' => 'edu_secondary', 'to' => 'social_media', 'weight' => 4], // First step
        ['from' => 'social_media', 'to' => 'social_media_manager', 'weight' => 5], // Second step to final career
        
        ['from' => 'edu_secondary', 'to' => 'basic_computer', 'weight' => 3], // First step
        ['from' => 'basic_computer', 'to' => 'junior_dev', 'weight' => 6], // Second step to final career
        
        ['from' => 'edu_secondary', 'to' => 'basic_healthcare', 'weight' => 5], // First step
        ['from' => 'basic_healthcare', 'to' => 'nurse', 'weight' => 7], // Second step to final career
        
        // Path from +2 to blockchain development through computer skills
        ['from' => 'edu_secondary', 'to' => 'basic_computer', 'weight' => 3],
        
        // IT skill progression
        ['from' => 'basic_computer', 'to' => 'html_css', 'weight' => 5],
        ['from' => 'html_css', 'to' => 'javascript', 'weight' => 7],
        ['from' => 'html_css', 'to' => 'php', 'weight' => 8],
        ['from' => 'html_css', 'to' => 'java', 'weight' => 8],
        
        // Direct paths from basic computer skills to programming languages
        ['from' => 'basic_computer', 'to' => 'php', 'weight' => 10],
        ['from' => 'basic_computer', 'to' => 'java', 'weight' => 10],
        ['from' => 'basic_computer', 'to' => 'python', 'weight' => 9],
        ['from' => 'javascript', 'to' => 'react', 'weight' => 9],
        ['from' => 'php', 'to' => 'laravel', 'weight' => 10],
        ['from' => 'php', 'to' => 'database', 'weight' => 6],
        ['from' => 'java', 'to' => 'database', 'weight' => 6],
        ['from' => 'python', 'to' => 'ml_engineering', 'weight' => 12],
        ['from' => 'ml_engineering', 'to' => 'ml_engineer', 'weight' => 8],
        ['from' => 'cloud_computing', 'to' => 'devops', 'weight' => 7],
        ['from' => 'devops', 'to' => 'devops_engineer', 'weight' => 8],
        ['from' => 'cybersecurity', 'to' => 'blockchain', 'weight' => 10],
        ['from' => 'blockchain', 'to' => 'blockchain_dev', 'weight' => 10],
        ['from' => 'database', 'to' => 'backend_dev', 'weight' => 5],
        ['from' => 'laravel', 'to' => 'backend_dev', 'weight' => 3],
        ['from' => 'java', 'to' => 'backend_dev', 'weight' => 4],
        ['from' => 'react', 'to' => 'web_dev', 'weight' => 4],
        ['from' => 'web_dev', 'to' => 'fullstack_dev', 'weight' => 6],
        ['from' => 'backend_dev', 'to' => 'fullstack_dev', 'weight' => 5],
        ['from' => 'fullstack_dev', 'to' => 'software_eng', 'weight' => 8],
        
        // Advanced IT paths
        ['from' => 'python', 'to' => 'ml_engineering', 'weight' => 12],
        ['from' => 'ml_engineering', 'to' => 'ml_engineer', 'weight' => 8],
        ['from' => 'networking', 'to' => 'cybersecurity', 'weight' => 8],
        ['from' => 'cybersecurity', 'to' => 'security_analyst', 'weight' => 7],
        ['from' => 'security_analyst', 'to' => 'cybersecurity_expert', 'weight' => 10],
        ['from' => 'cloud_computing', 'to' => 'cloud_architect', 'weight' => 9],
        ['from' => 'devops', 'to' => 'devops_engineer', 'weight' => 8],
        ['from' => 'blockchain', 'to' => 'blockchain_dev', 'weight' => 10],
        ['from' => 'iot', 'to' => 'iot_engineer', 'weight' => 9],
        ['from' => 'game_dev', 'to' => 'game_developer', 'weight' => 8],
        
        // Mobile development path
        ['from' => 'javascript', 'to' => 'nodejs', 'weight' => 8],
        ['from' => 'nodejs', 'to' => 'mobile_dev', 'weight' => 7],
        ['from' => 'mobile_dev', 'to' => 'app_developer', 'weight' => 5],
        
        // Data science path
        ['from' => 'math_fundamentals', 'to' => 'python', 'weight' => 7],
        ['from' => 'python', 'to' => 'data_science', 'weight' => 12],
        ['from' => 'data_science', 'to' => 'data_scientist', 'weight' => 5],
        ['from' => 'data_scientist', 'to' => 'data_engineer', 'weight' => 6],
        
        // Design path
        ['from' => 'basic_computer', 'to' => 'graphic_design', 'weight' => 6],
        ['from' => 'graphic_design', 'to' => 'ui_ux', 'weight' => 8],
        ['from' => 'ui_ux', 'to' => 'ui_ux_designer', 'weight' => 4],
        ['from' => 'graphic_design', 'to' => 'animation', 'weight' => 7],
        ['from' => 'animation', 'to' => 'game_dev', 'weight' => 8],
        
        // Business path
        ['from' => 'english_comm', 'to' => 'business_fundamentals', 'weight' => 5],
        ['from' => 'business_fundamentals', 'to' => 'marketing', 'weight' => 6],
        ['from' => 'business_fundamentals', 'to' => 'project_management', 'weight' => 7],
        ['from' => 'project_management', 'to' => 'project_manager', 'weight' => 5],
        ['from' => 'project_manager', 'to' => 'product_manager', 'weight' => 8],
        ['from' => 'business_fundamentals', 'to' => 'entrepreneurship', 'weight' => 9],
        
        // Healthcare path
        ['from' => 'basic_healthcare', 'to' => 'patient_care', 'weight' => 7],
        ['from' => 'patient_care', 'to' => 'nurse', 'weight' => 5],
        ['from' => 'math_fundamentals', 'to' => 'medical_research', 'weight' => 10],
        ['from' => 'medical_research', 'to' => 'research_scientist', 'weight' => 8],
        
        // Media path
        ['from' => 'english_comm', 'to' => 'content_writing', 'weight' => 4],
        ['from' => 'content_writing', 'to' => 'content_creator', 'weight' => 5],
        ['from' => 'content_creator', 'to' => 'social_media_manager', 'weight' => 6],
        ['from' => 'social_media', 'to' => 'social_media_manager', 'weight' => 5],
        
        // Photography and video editing paths
        ['from' => 'basic_computer', 'to' => 'photography', 'weight' => 6],
        ['from' => 'photography', 'to' => 'content_creator', 'weight' => 7],
        ['from' => 'basic_computer', 'to' => 'video_editing', 'weight' => 6],
        ['from' => 'video_editing', 'to' => 'content_creator', 'weight' => 7],
        
        
        // Career advancement paths
        ['from' => 'html_css', 'to' => 'junior_dev', 'weight' => 2],
        ['from' => 'junior_dev', 'to' => 'web_dev', 'weight' => 6],
        ['from' => 'software_eng', 'to' => 'cto', 'weight' => 15],
        ['from' => 'project_manager', 'to' => 'product_manager', 'weight' => 8],

        
        // Alternative education paths
        ['from' => 'edu_secondary', 'to' => 'edu_bachelor', 'weight' => 10],
        ['from' => 'edu_bachelor', 'to' => 'cybersecurity', 'weight' => 8],
        ['from' => 'edu_bachelor', 'to' => 'data_science', 'weight' => 10],
        ['from' => 'edu_bachelor', 'to' => 'cloud_computing', 'weight' => 9],
        
        // Direct paths from Bachelor's to careers (modified to ensure at least 3 nodes)
        // Software engineering path
        ['from' => 'edu_bachelor', 'to' => 'computer_science', 'weight' => 3],
        ['from' => 'computer_science', 'to' => 'software_eng', 'weight' => 5],
        
        // Data scientist path
        ['from' => 'edu_bachelor', 'to' => 'math_fundamentals', 'weight' => 2],
        ['from' => 'math_fundamentals', 'to' => 'data_scientist', 'weight' => 6],
        
        // Web developer path
        ['from' => 'edu_bachelor', 'to' => 'html_css', 'weight' => 2],
        ['from' => 'html_css', 'to' => 'web_dev', 'weight' => 4],
        
        // Security analyst path
        ['from' => 'edu_bachelor', 'to' => 'cybersecurity', 'weight' => 3],
        ['from' => 'cybersecurity', 'to' => 'security_analyst', 'weight' => 5],
        
        // Project manager path
        ['from' => 'edu_bachelor', 'to' => 'business_fundamentals', 'weight' => 2],
        ['from' => 'business_fundamentals', 'to' => 'project_manager', 'weight' => 6],
        
        // UI/UX designer path
        ['from' => 'edu_bachelor', 'to' => 'ui_ux', 'weight' => 3],
        ['from' => 'ui_ux', 'to' => 'ui_ux_designer', 'weight' => 5],
        
        // Digital marketer path
        ['from' => 'edu_bachelor', 'to' => 'marketing', 'weight' => 2],
        ['from' => 'marketing', 'to' => 'digital_marketer', 'weight' => 4],
        
        // Financial analyst path
        ['from' => 'edu_bachelor', 'to' => 'financial_analysis', 'weight' => 3],
        ['from' => 'financial_analysis', 'to' => 'financial_analyst', 'weight' => 6],
        
        // Direct paths from Bachelor's to advanced skills
        ['from' => 'edu_bachelor', 'to' => 'ml_engineering', 'weight' => 8],
        ['from' => 'edu_bachelor', 'to' => 'cloud_computing', 'weight' => 7],
        ['from' => 'edu_bachelor', 'to' => 'cybersecurity', 'weight' => 7],
        ['from' => 'edu_bachelor', 'to' => 'devops', 'weight' => 8],
        ['from' => 'edu_bachelor', 'to' => 'data_science', 'weight' => 9],
        ['from' => 'edu_bachelor', 'to' => 'ui_ux', 'weight' => 6],
        ['from' => 'edu_bachelor', 'to' => 'marketing', 'weight' => 5],
        ['from' => 'edu_bachelor', 'to' => 'project_management', 'weight' => 6],
        ['from' => 'edu_bachelor', 'to' => 'financial_analysis', 'weight' => 7],
        
        // Direct paths from Master's to advanced careers (modified to ensure at least 3 nodes)
        // CTO path
        ['from' => 'edu_master', 'to' => 'advanced_cs', 'weight' => 4],
        ['from' => 'advanced_cs', 'to' => 'cto', 'weight' => 8],
        
        // Research scientist path
        ['from' => 'edu_master', 'to' => 'research_methods', 'weight' => 3],
        ['from' => 'research_methods', 'to' => 'research_scientist', 'weight' => 7],
        
        // Product manager path
        ['from' => 'edu_master', 'to' => 'product_management', 'weight' => 3],
        ['from' => 'product_management', 'to' => 'product_manager', 'weight' => 6],
        
        // Cloud architect path
        ['from' => 'edu_master', 'to' => 'cloud_computing', 'weight' => 4],
        ['from' => 'cloud_computing', 'to' => 'cloud_architect', 'weight' => 7],
        
        // Cybersecurity expert path
        ['from' => 'edu_master', 'to' => 'cybersecurity', 'weight' => 4],
        ['from' => 'cybersecurity', 'to' => 'cybersecurity_expert', 'weight' => 8],
        
        // Chief scientist path
        ['from' => 'edu_master', 'to' => 'scientific_research', 'weight' => 5],
        ['from' => 'scientific_research', 'to' => 'chief_scientist', 'weight' => 9],
        
        // Professor path
        ['from' => 'edu_master', 'to' => 'academic_research', 'weight' => 4],
        ['from' => 'academic_research', 'to' => 'professor', 'weight' => 8],
        
        // Data engineer path
        ['from' => 'edu_master', 'to' => 'data_engineering', 'weight' => 4],
        ['from' => 'data_engineering', 'to' => 'data_engineer', 'weight' => 7],
        
        // ML engineer path
        ['from' => 'edu_master', 'to' => 'ml_engineering', 'weight' => 5],
        ['from' => 'ml_engineering', 'to' => 'ml_engineer', 'weight' => 8],
        
        // Digital marketer path
        ['from' => 'edu_master', 'to' => 'digital_marketing', 'weight' => 3],
        ['from' => 'digital_marketing', 'to' => 'digital_marketer', 'weight' => 6],
        
        // Financial analyst path
        ['from' => 'edu_master', 'to' => 'financial_analysis', 'weight' => 4],
        ['from' => 'financial_analysis', 'to' => 'financial_analyst', 'weight' => 7],
        
        // UI/UX designer path
        ['from' => 'edu_master', 'to' => 'ui_ux', 'weight' => 3],
        ['from' => 'ui_ux', 'to' => 'ui_ux_designer', 'weight' => 6],
        
        // Direct paths from Master's to advanced skills
        ['from' => 'edu_master', 'to' => 'devops', 'weight' => 6],
        ['from' => 'edu_master', 'to' => 'blockchain', 'weight' => 8],
        ['from' => 'edu_master', 'to' => 'iot', 'weight' => 7],
        ['from' => 'edu_master', 'to' => 'game_dev', 'weight' => 7],
        ['from' => 'edu_master', 'to' => 'data_science', 'weight' => 8],
        
        // Direct paths from programming skills to blockchain
        ['from' => 'php', 'to' => 'blockchain', 'weight' => 9],
        ['from' => 'java', 'to' => 'blockchain', 'weight' => 9],
        ['from' => 'python', 'to' => 'blockchain', 'weight' => 8],
        ['from' => 'javascript', 'to' => 'blockchain', 'weight' => 10],
        ['from' => 'database', 'to' => 'blockchain', 'weight' => 7],
        
        // Direct paths from skills to Blockchain Developer career
        ['from' => 'blockchain', 'to' => 'blockchain_dev', 'weight' => 10],
        ['from' => 'cybersecurity', 'to' => 'blockchain_dev', 'weight' => 12],
        ['from' => 'networking', 'to' => 'blockchain_dev', 'weight' => 11],
        
        // Direct paths from programming skills to Blockchain Developer career
        ['from' => 'php', 'to' => 'blockchain_dev', 'weight' => 15],
        ['from' => 'java', 'to' => 'blockchain_dev', 'weight' => 15],
        ['from' => 'python', 'to' => 'blockchain_dev', 'weight' => 14],
        ['from' => 'javascript', 'to' => 'blockchain_dev', 'weight' => 16],
        
        // Direct paths from PhD to research/academic careers
        ['from' => 'edu_phd', 'to' => 'research_scientist', 'weight' => 5],
        ['from' => 'edu_phd', 'to' => 'professor', 'weight' => 6],
        ['from' => 'edu_phd', 'to' => 'chief_scientist', 'weight' => 7],
        
        // Direct paths from PhD to advanced research skills
        ['from' => 'edu_phd', 'to' => 'ml_engineering', 'weight' => 5],
        ['from' => 'edu_phd', 'to' => 'medical_research', 'weight' => 6],
    ];
    
    return ['nodes' => $nodes, 'edges' => $edges];
}

// Include Dijkstra's algorithm
include 'algorithms/dijkstra.php';

// Handle form submission
$selectedStart = isset($_POST['start_node']) ? $_POST['start_node'] : 'edu_secondary';
$selectedEnd = isset($_POST['end_career']) ? $_POST['end_career'] : 'software_eng';
$algorithm = 'dijkstra'; // Always use Dijkstra

// Build graph
$graph = buildCareerGraph();

// Find path using Dijkstra's algorithm
$pathResult = dijkstra($graph, $selectedStart, $selectedEnd);

// Get all career options for dropdown
$careerOptions = [];
foreach ($graph['nodes'] as $id => $node) {
    if ($node['type'] === 'career') {
        $careerOptions[$id] = $node['name'];
    }
}

// Sort career options alphabetically
asort($careerOptions);

// Get all starting points for dropdown
$startOptions = [
    'edu_secondary' => '+2 Level',
    'edu_bachelor' => 'Bachelor Degree',
    'edu_master' => 'Master Degree',
    'edu_phd' => 'PhD',
    'basic_computer' => 'Basic Computer Skills',
    'html_css' => 'HTML/CSS',
    'javascript' => 'JavaScript',
    'php' => 'PHP',
    'python' => 'Python',
    'java' => 'Java',
    'math_fundamentals' => 'Math Fundamentals',
    'english_comm' => 'English Communication',
    'graphic_design' => 'Graphic Design',
    'ui_ux' => 'UI/UX Design',
    'data_science' => 'Data Science',
    'cybersecurity' => 'Cybersecurity',
    'cloud_computing' => 'Cloud Computing',
    'project_management' => 'Project Management',
    'business_fundamentals' => 'Business Fundamentals',
    'ml_engineering' => 'Machine Learning Engineering',
    'devops' => 'DevOps',
    'blockchain' => 'Blockchain'
];

?>

<div class="container py-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h1 class="display-5 fw-bold"><i class="fas fa-project-diagram me-2"></i>Dynamic Career Path Generator</h1>
            <p class="lead">Discover your optimal career path using advanced algorithms</p>
        </div>
    </div>
    
    <!-- Path Generator Form -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-4"><i class="fas fa-sliders-h me-2"></i>Generate Your Career Path</h3>
                    <form method="POST" class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label for="startNode" class="form-label fw-bold">Starting Point</label>
                            <select class="form-select form-select-lg" id="startNode" name="start_node" required>
                                <?php foreach($startOptions as $id => $name): ?>
                                    <option value="<?php echo $id; ?>" <?php echo $selectedStart === $id ? 'selected' : ''; ?>>
                                        <?php echo $name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-5">
                            <label for="endCareer" class="form-label fw-bold">Target Career</label>
                            <select class="form-select form-select-lg" id="endCareer" name="end_career" required>
                                <?php foreach($careerOptions as $id => $name): ?>
                                    <option value="<?php echo $id; ?>" <?php echo $selectedEnd === $id ? 'selected' : ''; ?>>
                                        <?php echo $name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-route me-2"></i>Generate Path
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <?php if ($pathResult): ?>
    <!-- Results Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-route me-2"></i>Your Optimal Career Path
                </div>
                <div class="card-body">
                    <div class="row">
                        
                    
                        <div class="col-12">
                            <h5 class="mb-4">Path Visualization</h5>
                            <div id="network" style="width: 100%; height: 400px; border: 1px solid #ddd; border-radius: 5px;">
                                <div class="d-flex justify-content-center align-items-center h-100">
                                    <div class="text-center">
                                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-3 fw-bold">Generating Optimal Path Visualization...</p>
                                    </div>
                                </div>
                            </div>
                            <div class="network-info mt-3">
                                <h6 class="mb-2"><i class="fas fa-key me-1"></i> Legend</h6>
                                <div class="network-legend">
                                    <div class="legend-item">
                                        <div class="legend-color" style="background-color: #3498db;"></div>
                                        <span>Education</span>
                                    </div>
                                    <div class="legend-item">
                                        <div class="legend-color" style="background-color: #27ae60;"></div>
                                        <span>Skills</span>
                                    </div>
                                    <div class="legend-item">
                                        <div class="legend-color" style="background-color: #e74c3c;"></div>
                                        <span>Careers</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Career Graph Visualization -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-sitemap me-2"></i>Complete Career Network
                </div>
                <div class="card-body">
                    <div id="fullNetwork" style="width: 100%; height: 600px; border: 1px solid #ddd; border-radius: 5px;">
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-3 fw-bold">Generating Career Network Visualization...</p>
                            </div>
                        </div>
                    </div>
                    <div class="network-info mt-3">
                        <h6 class="mb-2"><i class="fas fa-key me-1"></i> Legend</h6>
                        <div class="network-legend">
                            <div class="legend-item">
                                <div class="legend-color" style="background-color: #3498db;"></div>
                                <span>Education</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background-color: #27ae60;"></div>
                                <span>Skills</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background-color: #e74c3c;"></div>
                                <span>Careers</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Always pass graph data to external JavaScript
    var graph = <?php echo json_encode($graph); ?>;
    
    <?php if ($pathResult): ?>
    // Pass path result data to external JavaScript
    var pathResult = <?php echo json_encode($pathResult); ?>;
    <?php endif; ?>
</script>

<!-- Vis Network Library -->
<script src="https://cdn.jsdelivr.net/npm/vis-network@9.1.2/dist/vis-network.min.js"></script>

<!-- Custom JavaScript -->
<script src="assets/js/career-path-generator.js"></script>

<?php include 'footer.php'; ?>