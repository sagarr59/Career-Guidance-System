<?php
/**
 * Skill Gap Analysis Algorithm
 * 
 * This algorithm analyzes the gap between a user's current skills and the skills
 * required for a specific career path, providing personalized recommendations
 * for skill development.
 */

/**
 * Analyze skill gaps for a specific career
 * 
 * @param string $career_name Name of the career
 * @param int $user_skills_score User's skills score
 * @param array $selected_skills User's selected skills
 * @param mysqli $conn Database connection
 * @return array Skill gap analysis results
 */
function getSkillGapAnalysis($career_name, $user_skills_score, $selected_skills, $conn) {
    // Define required skills for each career with more realistic mappings to database skills
    $career_skills = [
        'Software Developer' => ['Programming', 'Problem Solving', 'Communication', 'Attention to Detail'],
        'Data Scientist' => ['Data Analysis', 'Programming', 'Mathematics', 'Critical Thinking'],
        'Teacher' => ['Communication', 'Leadership', 'Creativity', 'Patience'],
        'Business Analyst' => ['Data Analysis', 'Communication', 'Problem Solving', 'Business Acumen'],
        'Graphic Designer' => ['Creativity', 'Communication', 'Attention to Detail', 'Critical Thinking'],
        'Project Manager' => ['Leadership', 'Communication', 'Project Management', 'Problem Solving'],
        'Civil Engineer' => ['Mathematics', 'Problem Solving', 'Attention to Detail', 'Project Management'],
        'Doctor' => ['Medical Knowledge', 'Communication', 'Critical Thinking', 'Empathy'],
        'Nurse' => ['Medical Knowledge', 'Communication', 'Empathy', 'Attention to Detail'],
        'Journalist' => ['Writing', 'Communication', 'Critical Thinking', 'Research'],
        'Marketing Manager' => ['Creativity', 'Communication', 'Business Acumen', 'Data Analysis'],
        'Accountant' => ['Mathematics', 'Attention to Detail', 'Financial Knowledge', 'Critical Thinking'],
        'Lawyer' => ['Communication', 'Critical Thinking', 'Research', 'Writing'],
        'Chef' => ['Creativity', 'Attention to Detail', 'Leadership', 'Communication'],
        'Photographer' => ['Creativity', 'Attention to Detail', 'Communication', 'Critical Thinking'],
        'Tourism Guide' => ['Communication', 'Creativity', 'Cultural Knowledge', 'Leadership'],
        'Agricultural Officer' => ['Problem Solving', 'Communication', 'Scientific Knowledge', 'Leadership'],
        'Bank Manager' => ['Financial Knowledge', 'Leadership', 'Communication', 'Business Acumen'],
        'Social Worker' => ['Communication', 'Empathy', 'Problem Solving', 'Critical Thinking'],
        'Entrepreneur' => ['Leadership', 'Creativity', 'Business Acumen', 'Communication'],
        'Cybersecurity Specialist' => ['Programming', 'Problem Solving', 'Attention to Detail', 'Critical Thinking'],
        'Environmental Scientist' => ['Scientific Knowledge', 'Research', 'Problem Solving', 'Critical Thinking'],
        'Human Resources Manager' => ['Communication', 'Leadership', 'Conflict Resolution', 'Critical Thinking'],
        'Architect' => ['Creativity', 'Mathematics', 'Attention to Detail', 'Problem Solving'],
        'Content Writer' => ['Writing', 'Creativity', 'Research', 'Communication'],
        'UX Designer' => ['Creativity', 'Communication', 'Critical Thinking', 'Attention to Detail'],
        'Financial Advisor' => ['Financial Knowledge', 'Communication', 'Analytical Skills', 'Critical Thinking'],
        'Event Planner' => ['Organization', 'Communication', 'Creativity', 'Attention to Detail'],
        'Fitness Trainer' => ['Communication', 'Motivation', 'Physical Fitness', 'Leadership'],
        'Translator' => ['Language Skills', 'Communication', 'Cultural Knowledge', 'Attention to Detail']
    ];
    
    $required_skills = isset($career_skills[$career_name]) ? $career_skills[$career_name] : ['General Skills'];
    
    // Calculate current skill level (0-100) based on selected skills vs required skills
    $matched_skills = 0;
    $selected_skill_names = [];
    
    // Look up skill names from database
    if (!empty($selected_skills) && $conn) {
        $skill_ids = implode(',', array_map('intval', $selected_skills));
        $skill_query = "SELECT id, skill_name FROM skills WHERE id IN ($skill_ids)";
        $skill_result = $conn->query($skill_query);
        
        if ($skill_result && $skill_result->num_rows > 0) {
            while ($skill_row = $skill_result->fetch_assoc()) {
                $selected_skill_names[$skill_row['id']] = $skill_row['skill_name'];
            }
        }
    }
    
    // Match selected skills with required skills using better matching logic
    foreach($required_skills as $req_skill) {
        $found = false;
        foreach($selected_skills as $skill_id) {
            $skill_name = isset($selected_skill_names[$skill_id]) ? $selected_skill_names[$skill_id] : "Skill " . $skill_id;
            
            // Better matching logic - check for exact matches or close semantic matches
            if (strcasecmp($req_skill, $skill_name) === 0) {
                $found = true;
                break;
            }
            
            // Check for partial matches with common synonyms
            $synonyms = [
                'Problem Solving' => ['Problem Solving', 'Critical Thinking', 'Analytical Skills'],
                'Communication' => ['Communication', 'Writing', 'Public Speaking'],
                'Creativity' => ['Creativity', 'Creative Skills', 'Artistic Ability'],
                'Leadership' => ['Leadership', 'Management', 'Team Leading'],
                'Attention to Detail' => ['Attention to Detail', 'Detail Oriented', 'Precision'],
                'Data Analysis' => ['Data Analysis', 'Analytical Skills', 'Statistics'],
                'Programming' => ['Programming', 'Coding', 'Software Development'],
                'Mathematics' => ['Mathematics', 'Math', 'Quantitative Skills']
            ];
            
            if (isset($synonyms[$req_skill])) {
                foreach ($synonyms[$req_skill] as $synonym) {
                    if (stripos($skill_name, $synonym) !== false || stripos($synonym, $skill_name) !== false) {
                        $found = true;
                        break 2; // Break out of both loops
                    }
                }
            }
        }
        if ($found) {
            $matched_skills++;
        }
    }
    
    // Calculate current skill level as percentage of matched skills
    $current_level = !empty($required_skills) ? min(100, ($matched_skills / count($required_skills)) * 100) : 0;
    
    // Calculate gap
    $gap = max(0, 100 - $current_level);
    
    // Identify missing skills more accurately
    $missing_skills = [];
    if ($gap > 0) {
        // Find skills that weren't matched
        foreach($required_skills as $req_skill) {
            $found = false;
            foreach($selected_skills as $skill_id) {
                $skill_name = isset($selected_skill_names[$skill_id]) ? $selected_skill_names[$skill_id] : "Skill " . $skill_id;
                
                // Same matching logic as above
                if (strcasecmp($req_skill, $skill_name) === 0) {
                    $found = true;
                    break;
                }
                
                // Check for partial matches with common synonyms
                $synonyms = [
                    'Problem Solving' => ['Problem Solving', 'Critical Thinking', 'Analytical Skills'],
                    'Communication' => ['Communication', 'Writing', 'Public Speaking'],
                    'Creativity' => ['Creativity', 'Creative Skills', 'Artistic Ability'],
                    'Leadership' => ['Leadership', 'Management', 'Team Leading'],
                    'Attention to Detail' => ['Attention to Detail', 'Detail Oriented', 'Precision'],
                    'Data Analysis' => ['Data Analysis', 'Analytical Skills', 'Statistics'],
                    'Programming' => ['Programming', 'Coding', 'Software Development'],
                    'Mathematics' => ['Mathematics', 'Math', 'Quantitative Skills']
                ];
                
                if (isset($synonyms[$req_skill])) {
                    foreach ($synonyms[$req_skill] as $synonym) {
                        if (stripos($skill_name, $synonym) !== false || stripos($synonym, $skill_name) !== false) {
                            $found = true;
                            break 2; // Break out of both loops
                        }
                    }
                }
            }
            if (!$found) {
                $missing_skills[] = $req_skill;
            }
        }
        
        // If we didn't find specific missing skills, take a sample
        if (empty($missing_skills)) {
            $missing_skills = array_slice($required_skills, 0, max(1, ceil(count($required_skills) * ($gap/100))));
        }
    }
    
    return [
        'required_skills' => $required_skills,
        'current_level' => round($current_level),
        'gap' => round($gap),
        'missing_skills' => $missing_skills
    ];
}
?>