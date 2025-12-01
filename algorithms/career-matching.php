<?php
// Career Recommendation Algorithm
// Enhanced algorithm for more accurate career recommendations

// Include the skill gap analysis algorithm
require_once 'skill-gap-analysis.php';

/**
 * Recommend career based on personality traits and skills
 * 
 * @param int $personality_score User's personality assessment score
 * @param int $skills_score User's skills assessment score
 * @param array $career Career data from database
 * @param array $user_traits User's personality traits
 * @param array $user_skills User's selected skills
 * @return int Recommendation score (0-100)
 */
function careerRecommend($personality_score, $skills_score, $career, $user_traits, $user_skills) {
    // Use passed selected skills parameter
    $selected_skills = $user_skills;
    
    // Parse career required skills
    $required_skills = array_map('trim', explode(',', $career['required_skills']));
    
    // Calculate skill match percentage with more nuance
    $skill_matches = 0;
    $partial_matches = 0;
    
    foreach($selected_skills as $skill) {
        foreach($required_skills as $req_skill) {
            // Exact match
            if (strtolower(trim($req_skill)) === strtolower(trim($skill))) {
                $skill_matches += 1.0;
                break;
            }
            // Partial match (substring)
            elseif (stripos($req_skill, $skill) !== false || stripos($skill, $req_skill) !== false) {
                $partial_matches += 0.5;
            }
            // Similarity match (using levenshtein distance for typos)
            elseif (levenshtein(strtolower($req_skill), strtolower($skill)) <= 2) {
                $partial_matches += 0.3;
            }
        }
    }
    
    // Calculate weighted skill match percentage
    $total_possible_matches = count($required_skills);
    $weighted_matches = $skill_matches + $partial_matches;
    $skill_match_percentage = !empty($required_skills) ? min(100, ($weighted_matches / $total_possible_matches) * 100) : 0;
    
    // Calculate personality trait alignment with more variation
    $trait_alignment = 0;
    $trait_count = 0;
    
    // Define career-specific trait preferences
    $career_traits = [
        'Software Developer' => ['Analytical' => 9, 'Practical' => 7],
        'Data Scientist' => ['Analytical' => 10, 'Practical' => 6],
        'Teacher' => ['Social' => 9, 'Leadership' => 7],
        'Business Analyst' => ['Analytical' => 8, 'Leadership' => 7, 'Social' => 6],
        'Graphic Designer' => ['Practical' => 9, 'Analytical' => 5],
        'Project Manager' => ['Leadership' => 10, 'Social' => 8, 'Analytical' => 6],
        'Civil Engineer' => ['Analytical' => 9, 'Practical' => 8],
        'Doctor' => ['Social' => 9, 'Analytical' => 8],
        'Nurse' => ['Social' => 10, 'Practical' => 8],
        'Journalist' => ['Social' => 8, 'Practical' => 7],
        'Marketing Manager' => ['Leadership' => 8, 'Social' => 7, 'Practical' => 6],
        'Accountant' => ['Analytical' => 9, 'Practical' => 7],
        'Lawyer' => ['Analytical' => 8, 'Social' => 7],
        'Chef' => ['Practical' => 10, 'Social' => 6],
        'Photographer' => ['Practical' => 9, 'Analytical' => 5],
        'Tourism Guide' => ['Social' => 10, 'Practical' => 7],
        'Agricultural Officer' => ['Analytical' => 7, 'Practical' => 9],
        'Bank Manager' => ['Leadership' => 9, 'Analytical' => 7],
        'Social Worker' => ['Social' => 10, 'Analytical' => 6],
        'Entrepreneur' => ['Leadership' => 10, 'Practical' => 8, 'Analytical' => 7],
        'Cybersecurity Specialist' => ['Analytical' => 10, 'Practical' => 8],
        'Environmental Scientist' => ['Analytical' => 8, 'Practical' => 7],
        'Human Resources Manager' => ['Social' => 9, 'Leadership' => 8],
        'Architect' => ['Practical' => 9, 'Analytical' => 7],
        'Content Writer' => ['Practical' => 8, 'Social' => 6],
        'UX Designer' => ['Practical' => 8, 'Analytical' => 7],
        'Financial Advisor' => ['Analytical' => 8, 'Social' => 7],
        'Event Planner' => ['Leadership' => 8, 'Practical' => 7],
        'Fitness Trainer' => ['Social' => 8, 'Practical' => 9],
        'Translator' => ['Social' => 7, 'Analytical' => 6]
    ];
    
    $career_name = $career['career_name'];
    if (isset($career_traits[$career_name])) {
        foreach($career_traits[$career_name] as $trait => $required_level) {
            if (isset($user_traits[$trait])) {
                $user_level = $user_traits[$trait];
                // Calculate alignment with exponential scaling for more variation
                $difference = abs($user_level - $required_level);
                $alignment = max(0, 100 - ($difference * 8)); // More sensitive to differences
                $trait_alignment += $alignment;
                $trait_count++;
            }
        }
    }
    
    $trait_match_percentage = $trait_count > 0 ? $trait_alignment / $trait_count : 30; // Lower default
    
    // Career specific factors (deterministic, not randomized)
    $demand_factor = 85;
    $growth_factor = 90;
    $local_opportunity = 75;
    $educational_access = 80;
    
    // High demand careers in Nepal
    $high_demand_careers = ['Software Developer', 'Doctor', 'Nurse', 'Civil Engineer', 'Cybersecurity Specialist'];
    
    // Growing careers in Nepal
    $growing_careers = ['Data Scientist', 'Environmental Scientist', 'Tourism Guide', 'Entrepreneur', 'Marketing Manager'];
    
    // Stable careers in Nepal
    $stable_careers = ['Teacher', 'Accountant', 'Bank Manager', 'Project Manager', 'Human Resources Manager'];
    
    if (in_array($career_name, $high_demand_careers)) {
        $demand_factor = 95;
        $growth_factor = 90;
        $local_opportunity = 90;
    } elseif (in_array($career_name, $growing_careers)) {
        $demand_factor = 80;
        $growth_factor = 95;
        $local_opportunity = 85;
    } elseif (in_array($career_name, $stable_careers)) {
        $demand_factor = 75;
        $growth_factor = 80;
        $local_opportunity = 80;
    }
    
    // Adjust educational access based on career
    $educational_requirements = [
        'Software Developer' => 90,
        'Data Scientist' => 95,
        'Teacher' => 85,
        'Business Analyst' => 85,
        'Graphic Designer' => 70,
        'Project Manager' => 90,
        'Civil Engineer' => 95,
        'Doctor' => 95,
        'Nurse' => 85,
        'Journalist' => 75,
        'Marketing Manager' => 85,
        'Accountant' => 80,
        'Lawyer' => 95,
        'Chef' => 70,
        'Photographer' => 65,
        'Tourism Guide' => 70,
        'Agricultural Officer' => 85,
        'Bank Manager' => 90,
        'Social Worker' => 80,
        'Entrepreneur' => 75,
        'Cybersecurity Specialist' => 95,
        'Environmental Scientist' => 90,
        'Human Resources Manager' => 85,
        'Architect' => 90,
        'Content Writer' => 70,
        'UX Designer' => 80,
        'Financial Advisor' => 85,
        'Event Planner' => 75,
        'Fitness Trainer' => 70,
        'Translator' => 80
    ];
    
    if (isset($educational_requirements[$career_name])) {
        $educational_access = $educational_requirements[$career_name];
    }
    
    // Weighted scoring algorithm
    $personality_weight = 0.35;
    $skills_weight = 0.25;
    $demand_weight = 0.15;
    $growth_weight = 0.15;
    $local_weight = 0.05;
    $education_weight = 0.05;
    
    // Calculate final score
    $final_score = (
        ($trait_match_percentage * $personality_weight) +
        ($skill_match_percentage * $skills_weight) +
        ($demand_factor * $demand_weight) +
        ($growth_factor * $growth_weight) +
        ($local_opportunity * $local_weight) +
        ($educational_access * $education_weight)
    );
    
    // Add small random variation to prevent identical scores
    $random_variation = mt_rand(-3, 3);
    $final_score += $random_variation;
    
    // Ensure score is between 0 and 100
    $final_score = max(0, min(100, $final_score));
    
    return round($final_score);
}

/**
 * Get educational pathway for a career
 * 
 * @param string $career_name Name of the career
 * @return array Educational pathway information
 */
function getEducationalPathway($career_name) {
    $pathways = [
        'Software Developer' => [
            'stream' => 'Science Stream (+2)',
            'bachelor' => 'BSc in Computer Science or BE in Computer Engineering',
            'master' => 'MSc in Computer Science or MBA in IT',
            'duration' => '4 years',
            'description' => 'Focus on programming, software engineering, and system design. Consider additional certifications in emerging technologies.'
        ],
        'Data Scientist' => [
            'stream' => 'Science Stream (+2)',
            'bachelor' => 'BSc in Statistics, Mathematics, or Computer Science',
            'master' => 'MSc in Data Science or Statistics',
            'duration' => '4 years',
            'description' => 'Emphasis on statistical analysis, machine learning, and big data technologies. Strong mathematical foundation is essential.'
        ],
        'Teacher' => [
            'stream' => 'Any Stream (+2)',
            'bachelor' => 'BEd (Bachelor of Education) or Bachelor degree in subject area + BEd',
            'master' => 'MEd (Master of Education) or MA/MSc in subject area',
            'duration' => '4 years',
            'description' => 'Complete BEd for teaching certification. Specialize in specific subjects. Continuous professional development is important.'
        ],
        'Business Analyst' => [
            'stream' => 'Management or Science Stream (+2)',
            'bachelor' => 'BBA, BBS, or BSc in Business Analytics',
            'master' => 'MBA or MSc in Business Analytics',
            'duration' => '4 years',
            'description' => 'Combine business knowledge with analytical skills. Focus on data-driven decision making and process optimization.'
        ],
        'Graphic Designer' => [
            'stream' => 'Any Stream (+2)',
            'bachelor' => 'BFA in Graphic Design or BDes in Design',
            'master' => 'MFA in Graphic Design or MDes in Design',
            'duration' => '4 years',
            'description' => 'Develop creative and technical skills in design software. Build a strong portfolio. Stay updated with design trends.'
        ],
        'Project Manager' => [
            'stream' => 'Management or Science Stream (+2)',
            'bachelor' => 'BBA, BBS, or Bachelor in Engineering',
            'master' => 'MBA or PMP Certification',
            'duration' => '4 years',
            'description' => 'Gain experience in your domain first, then move to project management. PMP certification enhances career prospects.'
        ],
        'Civil Engineer' => [
            'stream' => 'Science Stream (+2)',
            'bachelor' => 'BE in Civil Engineering',
            'master' => 'ME in Civil Engineering or MBA',
            'duration' => '4 years',
            'description' => 'Strong foundation in mathematics and physics required. Specialize in structural, geotechnical, or transportation engineering.'
        ],
        'Doctor' => [
            'stream' => 'Science Stream (+2)',
            'bachelor' => 'MBBS',
            'master' => 'MD in specialization area',
            'duration' => '5.5 years',
            'description' => 'Complete MBBS followed by internship. Specialize in preferred field. Continuous medical education is mandatory.'
        ],
        'Nurse' => [
            'stream' => 'Science Stream (+2)',
            'bachelor' => 'BSc in Nursing',
            'master' => 'MSc in Nursing',
            'duration' => '4 years',
            'description' => 'Clinical training is essential. Specialize in areas like critical care, pediatrics, or mental health. Licensure required.'
        ],
        'Journalist' => [
            'stream' => 'Any Stream (+2)',
            'bachelor' => 'BA in Journalism or Mass Communication',
            'master' => 'MA in Journalism or Mass Communication',
            'duration' => '4 years',
            'description' => 'Develop writing and communication skills. Gain experience through internships. Digital media skills are increasingly important.'
        ],
        'Marketing Manager' => [
            'stream' => 'Management or Humanities Stream (+2)',
            'bachelor' => 'BBA, BBS, or BA in Marketing',
            'master' => 'MBA in Marketing',
            'duration' => '4 years',
            'description' => 'Combine business knowledge with creative skills. Digital marketing expertise is highly valued. Consider certifications in marketing tools.'
        ],
        'Accountant' => [
            'stream' => 'Management or Science Stream (+2)',
            'bachelor' => 'BBS, BBA, or BCom',
            'master' => 'MBS, MBA, or CA certification',
            'duration' => '4 years',
            'description' => 'Strong numerical skills required. Professional certifications like CA or ACCA enhance career prospects significantly.'
        ],
        'Lawyer' => [
            'stream' => 'Any Stream (+2)',
            'bachelor' => 'LLB',
            'master' => 'LLM',
            'duration' => '5 years',
            'description' => 'Complete LLB after bachelor degree or integrated 5-year program. Pass bar examination. Specialize in corporate, criminal, or civil law.'
        ],
        'Chef' => [
            'stream' => 'Any Stream (+2)',
            'bachelor' => 'Diploma in Culinary Arts or Hotel Management',
            'master' => 'Advanced Diplomas or Specialized Certifications',
            'duration' => '1-3 years',
            'description' => 'Hands-on training is essential. Gain experience in various cuisines. Consider specialization in specific cooking styles or dietary requirements.'
        ],
        'Photographer' => [
            'stream' => 'Any Stream (+2)',
            'bachelor' => 'Diploma in Photography or Mass Communication',
            'master' => 'Advanced Photography Courses',
            'duration' => '1-2 years',
            'description' => 'Build technical and artistic skills. Create a strong portfolio. Specialize in areas like portrait, wedding, or commercial photography.'
        ],
        'Tourism Guide' => [
            'stream' => 'Any Stream (+2)',
            'bachelor' => 'Bachelor degree in any field',
            'master' => 'Masters in Tourism Management',
            'duration' => '4 years',
            'description' => 'Knowledge of local culture and languages is essential. Get certified as a tourist guide. Continuous learning about new destinations.'
        ],
        'Agricultural Officer' => [
            'stream' => 'Science Stream (+2)',
            'bachelor' => 'BSc in Agriculture',
            'master' => 'MSc in Agriculture',
            'duration' => '4 years',
            'description' => 'Specialize in areas like agronomy, horticulture, or animal husbandry. Field experience is crucial. Stay updated with agricultural technologies.'
        ],
        'Bank Manager' => [
            'stream' => 'Management Stream (+2)',
            'bachelor' => 'BBS, BBA, or Commerce degree',
            'master' => 'MBS, MBA, or Professional Banking Certification',
            'duration' => '4 years',
            'description' => 'Start in entry-level banking positions. Gain experience in different departments. Professional certifications enhance career growth.'
        ],
        'Social Worker' => [
            'stream' => 'Humanities or Science Stream (+2)',
            'bachelor' => 'BSW (Bachelor of Social Work)',
            'master' => 'MSW (Master of Social Work)',
            'duration' => '4 years',
            'description' => 'Focus on community development and counseling skills. Fieldwork is essential. Specialize in areas like child welfare or mental health.'
        ],
        'Entrepreneur' => [
            'stream' => 'Any Stream (+2)',
            'bachelor' => 'Any Bachelor degree',
            'master' => 'MBA or Entrepreneurship Certification',
            'duration' => 'Varies',
            'description' => 'Develop business acumen and leadership skills. Gain industry experience before starting your venture. Network building is crucial.'
        ],
        'Cybersecurity Specialist' => [
            'stream' => 'Science Stream (+2)',
            'bachelor' => 'BSc in Cybersecurity or Computer Science',
            'master' => 'MSc in Cybersecurity or CISSP Certification',
            'duration' => '4 years',
            'description' => 'Strong technical foundation is essential. Stay updated with latest security threats and solutions. Professional certifications are highly valued.'
        ],
        'Environmental Scientist' => [
            'stream' => 'Science Stream (+2)',
            'bachelor' => 'BSc in Environmental Science',
            'master' => 'MSc in Environmental Science',
            'duration' => '4 years',
            'description' => 'Focus on environmental issues and sustainable solutions. Fieldwork and research experience are important. Specialize in areas like conservation or pollution control.'
        ],
        'Human Resources Manager' => [
            'stream' => 'Management or Humanities Stream (+2)',
            'bachelor' => 'BBA, BBS, or BA in Psychology',
            'master' => 'MBA in HR or MA in Organizational Psychology',
            'duration' => '4 years',
            'description' => 'Understand labor laws and organizational behavior. Gain experience in recruitment and employee relations. Continuous learning in HR practices.'
        ],
        'Architect' => [
            'stream' => 'Science Stream (+2)',
            'bachelor' => 'Bachelor of Architecture (BArch)',
            'master' => 'Master of Architecture (MArch)',
            'duration' => '5 years',
            'description' => 'Creative and technical skills are both essential. Complete internship for licensure. Stay updated with building codes and design software.'
        ],
        'Content Writer' => [
            'stream' => 'Any Stream (+2)',
            'bachelor' => 'Any Bachelor degree',
            'master' => 'MA in English or Journalism',
            'duration' => '4 years',
            'description' => 'Excellent writing and research skills required. Build a portfolio of diverse content. Stay updated with SEO and digital marketing trends.'
        ],
        'UX Designer' => [
            'stream' => 'Science or Management Stream (+2)',
            'bachelor' => 'BDes, BSc in Psychology, or Computer Science',
            'master' => 'MDes or Specialized UX Certifications',
            'duration' => '4 years',
            'description' => 'Combine design skills with user psychology understanding. Build a strong portfolio. Stay updated with design tools and methodologies.'
        ],
        'Financial Advisor' => [
            'stream' => 'Management or Science Stream (+2)',
            'bachelor' => 'BBS, BBA, or BCom',
            'master' => 'MBS, MBA, or Financial Planning Certification',
            'duration' => '4 years',
            'description' => 'Strong analytical and communication skills required. Professional certifications like CFA or CFP enhance credibility. Build client relationships.'
        ],
        'Event Planner' => [
            'stream' => 'Management or Humanities Stream (+2)',
            'bachelor' => 'BBA, BA in Event Management, or Hospitality Management',
            'master' => 'MBA in Event Management or Hospitality',
            'duration' => '4 years',
            'description' => 'Organizational and creative skills are essential. Gain experience in different types of events. Build vendor networks and client relationships.'
        ],
        'Fitness Trainer' => [
            'stream' => 'Any Stream (+2)',
            'bachelor' => 'Bachelor degree in any field',
            'master' => 'Certifications in Personal Training or Sports Science',
            'duration' => 'Varies',
            'description' => 'Get certified in personal training and first aid. Specialize in areas like weight loss or sports performance. Continuous education on fitness trends.'
        ],
        'Translator' => [
            'stream' => 'Any Stream (+2)',
            'bachelor' => 'BA in Languages or Linguistics',
            'master' => 'MA in Translation or Languages',
            'duration' => '4 years',
            'description' => 'Fluency in multiple languages is essential. Get certified in translation. Specialize in specific fields like legal, medical, or technical translation.'
        ]
    ];
    
    return isset($pathways[$career_name]) ? $pathways[$career_name] : [
        'stream' => 'Not specified',
        'bachelor' => 'Not specified',
        'master' => 'Not specified',
        'duration' => 'Not specified',
        'description' => 'Pathway information not available for this career.'
    ];
}


?>