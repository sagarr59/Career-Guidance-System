-- Career Guidance System Database Setup
-- Execute this file in phpMyAdmin or MySQL command line

CREATE DATABASE IF NOT EXISTS career_guidance;
USE career_guidance;

-- Students Table
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Skills Table
CREATE TABLE IF NOT EXISTS skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    skill_name VARCHAR(100),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Careers Table
CREATE TABLE IF NOT EXISTS careers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    career_name VARCHAR(150) NOT NULL,
    description TEXT,
    required_skills TEXT,
    education_required VARCHAR(255) DEFAULT 'Bachelor Degree or equivalent',
    salary_range VARCHAR(100),
    job_locations VARCHAR(255) DEFAULT 'Kathmandu, Pokhara, Biratnagar',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Personality Questions Table
CREATE TABLE IF NOT EXISTS personality_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    option1 VARCHAR(255),
    option2 VARCHAR(255),
    option3 VARCHAR(255),
    option4 VARCHAR(255),
    score INT DEFAULT 1,
    personality_type VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Assessment Questions Table
CREATE TABLE IF NOT EXISTS assessment_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    options JSON,
    category VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Admin Table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- User Career Matches Table
CREATE TABLE IF NOT EXISTS user_career_matches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    career_id INT NOT NULL,
    match_score DECIMAL(5,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (career_id) REFERENCES careers(id) ON DELETE CASCADE
);

-- Insert Sample Data

-- Sample Skills
-- Further reduced to 15 key skills to minimize user fatigue while maintaining accuracy
INSERT INTO skills (name, skill_name) VALUES 
('Programming', 'Programming'),
('Communication', 'Communication'),
('Leadership', 'Leadership'),
('Problem Solving', 'Problem Solving'),
('Data Analysis', 'Data Analysis'),
('Project Management', 'Project Management'),
('Mathematics', 'Mathematics'),
('Medical Knowledge', 'Medical Knowledge'),
('Writing', 'Writing'),
('Financial Knowledge', 'Financial Knowledge'),
('Attention to Detail', 'Attention to Detail'),
('Creativity', 'Creativity'),
('Critical Thinking', 'Critical Thinking'),
('Empathy', 'Empathy'),
('Business Acumen', 'Business Acumen');

-- Sample Careers
-- Expanded to include more diverse career options for all interests
INSERT INTO careers (career_name, description, required_skills, education_required, salary_range, job_locations) VALUES
('Software Developer', 'Design and build software applications for businesses and consumers', 'Programming, Problem Solving, Logical Thinking, Attention to Detail', 'Bachelor in Computer Science or related field', 'NPR 40,000 - 150,000/month', 'Kathmandu, Lalitpur, Bhaktapur, Pokhara'),
('Data Scientist', 'Analyze complex data to help companies make informed decisions', 'Data Analysis, Statistics, Programming, Critical Thinking', 'Master in Data Science or related field', 'NPR 50,000 - 180,000/month', 'Kathmandu, Pokhara, Biratnagar'),
('Teacher', 'Educate and inspire students at various educational levels', 'Communication, Patience, Leadership, Subject Knowledge', 'Bachelor in Education or related field', 'NPR 25,000 - 70,000/month', 'Kathmandu, Pokhara, Biratnagar, Dharan'),
('Business Analyst', 'Analyze business processes and recommend improvements for efficiency', 'Problem Solving, Communication, Data Analysis, Business Acumen', 'Bachelor in Business Administration or related field', 'NPR 35,000 - 100,000/month', 'Kathmandu, Lalitpur, Bhaktapur'),
('Graphic Designer', 'Create visual content for brands, products, and marketing materials', 'Creativity, Adobe Tools, Visual Design, Communication', 'Bachelor in Design or related field', 'NPR 20,000 - 80,000/month', 'Kathmandu, Pokhara, Biratnagar'),
('Project Manager', 'Lead and coordinate project teams to achieve organizational goals', 'Leadership, Project Management, Communication, Planning', 'Bachelor in Management or related field', 'NPR 45,000 - 120,000/month', 'Kathmandu, Lalitpur, Bhaktapur, Pokhara'),
('Civil Engineer', 'Design and supervise construction of infrastructure projects like roads and buildings', 'Mathematics, Problem Solving, Technical Drawing, Project Management', 'Bachelor in Civil Engineering', 'NPR 35,000 - 90,000/month', 'Kathmandu, Pokhara, Biratnagar, Nepalgunj'),
('Doctor', 'Diagnose and treat illnesses, provide medical care to patients', 'Medical Knowledge, Communication, Critical Thinking, Empathy', 'MBBS Degree', 'NPR 60,000 - 200,000/month', 'Kathmandu, Pokhara, Biratnagar, Dharan'),
('Nurse', 'Provide patient care and support in healthcare settings', 'Medical Knowledge, Communication, Empathy, Attention to Detail', 'Bachelor in Nursing', 'NPR 25,000 - 60,000/month', 'Kathmandu, Pokhara, Biratnagar, Dharan'),
('Journalist', 'Research, write, and report news stories for media outlets', 'Writing, Communication, Research, Critical Thinking', 'Bachelor in Journalism or related field', 'NPR 20,000 - 50,000/month', 'Kathmandu, Pokhara, Biratnagar'),
('Marketing Manager', 'Develop and execute marketing strategies to promote products/services', 'Creativity, Communication, Market Research, Digital Marketing', 'Bachelor in Marketing or related field', 'NPR 30,000 - 90,000/month', 'Kathmandu, Lalitpur, Bhaktapur, Pokhara'),
('Accountant', 'Manage financial records, prepare tax documents, and ensure compliance', 'Mathematics, Attention to Detail, Financial Knowledge, Organization', 'Bachelor in Commerce or related field', 'NPR 25,000 - 70,000/month', 'Kathmandu, Pokhara, Biratnagar'),
('Lawyer', 'Provide legal advice and represent clients in legal matters', 'Communication, Critical Thinking, Research, Public Speaking', 'LLB Degree', 'NPR 40,000 - 150,000/month', 'Kathmandu, Pokhara, Biratnagar'),
('Chef', 'Plan, prepare, and cook meals in restaurants or other food service establishments', 'Creativity, Time Management, Attention to Detail, Leadership', 'Diploma in Culinary Arts', 'NPR 20,000 - 60,000/month', 'Kathmandu, Pokhara, Biratnagar'),
('Tourism Guide', 'Lead tours and provide information to tourists about local attractions', 'Communication, Cultural Knowledge, Language Skills, Customer Service', 'Bachelor in Tourism or related field', 'NPR 20,000 - 40,000/month', 'Kathmandu, Pokhara, Lumbini, Chitwan'),
('Agricultural Officer', 'Provide guidance on farming techniques and crop management', 'Agricultural Knowledge, Communication, Problem Solving, Field Work', 'Bachelor in Agriculture or related field', 'NPR 30,000 - 60,000/month', 'Kathmandu, Biratnagar, Dharan, Nepalgunj'),
('Bank Manager', 'Oversee banking operations and manage financial services', 'Financial Knowledge, Leadership, Communication, Decision Making', 'Bachelor in Finance or related field', 'NPR 40,000 - 100,000/month', 'Kathmandu, Pokhara, Biratnagar'),
('Social Worker', 'Help individuals and communities solve social and personal problems', 'Communication, Empathy, Problem Solving, Advocacy', 'Bachelor in Social Work or related field', 'NPR 25,000 - 50,000/month', 'Kathmandu, Pokhara, Biratnagar, Dharan'),
('Entrepreneur', 'Start and manage your own business ventures', 'Leadership, Creativity, Risk Management, Business Skills', 'Bachelor in Business or related field', 'NPR 0 - Unlimited (Varies greatly)', 'Kathmandu, Pokhara, Biratnagar, Dharan'),
('Cybersecurity Specialist', 'Protect computer networks and systems from cyber threats', 'Technical Skills, Problem Solving, Attention to Detail, Security Knowledge', 'Bachelor in Computer Science or related field', 'NPR 50,000 - 150,000/month', 'Kathmandu, Lalitpur, Bhaktapur'),
('Environmental Scientist', 'Study environmental issues and develop solutions for sustainability', 'Scientific Knowledge, Research, Problem Solving, Environmental Awareness', 'Master in Environmental Science or related field', 'NPR 35,000 - 80,000/month', 'Kathmandu, Pokhara, Biratnagar'),
('Human Resources Manager', 'Manage employee relations, recruitment, and organizational development', 'Communication, Leadership, Conflict Resolution, Organizational Skills', 'Bachelor in HR or related field', 'NPR 40,000 - 100,000/month', 'Kathmandu, Lalitpur, Bhaktapur, Pokhara'),
('Architect', 'Design buildings and structures that are functional, safe, and aesthetically pleasing', 'Creativity, Technical Drawing, Mathematics, Spatial Awareness', 'Bachelor in Architecture', 'NPR 35,000 - 120,000/month', 'Kathmandu, Pokhara, Biratnagar'),
('Content Writer', 'Create written content for websites, blogs, and marketing materials', 'Writing, Creativity, Research, Communication', 'Bachelor in English or related field', 'NPR 20,000 - 50,000/month', 'Kathmandu, Pokhara, Biratnagar'),
('UX Designer', 'Design user experiences for digital products and services', 'Creativity, User Research, Prototyping, Communication', 'Bachelor in Design or related field', 'NPR 35,000 - 90,000/month', 'Kathmandu, Lalitpur, Bhaktapur'),
('Financial Advisor', 'Provide financial planning and investment advice to clients', 'Financial Knowledge, Communication, Analytical Skills, Ethics', 'Bachelor in Finance or related field', 'NPR 30,000 - 100,000/month', 'Kathmandu, Pokhara, Biratnagar'),
('Event Planner', 'Organize and coordinate events such as weddings, conferences, and festivals', 'Organization, Communication, Creativity, Attention to Detail', 'Bachelor in Event Management or related field', 'NPR 25,000 - 70,000/month', 'Kathmandu, Pokhara, Biratnagar'),
('Fitness Trainer', 'Guide clients in exercise routines and fitness programs', 'Communication, Motivation, Physical Fitness, Anatomy', 'Diploma in Fitness Training', 'NPR 20,000 - 60,000/month', 'Kathmandu, Pokhara, Biratnagar'),
('Translator', 'Convert written or spoken content from one language to another', 'Language Skills, Communication, Cultural Knowledge, Attention to Detail', 'Bachelor in Languages or related field', 'NPR 25,000 - 80,000/month', 'Kathmandu, Pokhara, Biratnagar');

-- Personality Questions (Nepali Student Focused - Short & Simple)
-- Reduced to 6 key questions to minimize user fatigue
INSERT INTO personality_questions (question, option1, option2, option3, option4, score, personality_type) VALUES
('What do you enjoy doing in your free time?', 'Reading books or studying', 'Playing sports or games', 'Drawing or creative activities', 'Helping family or friends', 3, 'Analytical'),
('Which subject do you like most in school?', 'Mathematics or Science', 'Literature or Arts', 'Social Studies', 'Computer or Technology', 3, 'Analytical'),
('When working on a project, you:', 'Plan everything first', 'Start immediately and adjust', 'Ask for help from others', 'Try different approaches', 3, 'Leadership'),
('Your biggest strength is:', 'Being good at calculations', 'Being creative and artistic', 'Being a good listener', 'Being confident and bold', 3, 'Social'),
('After SEE/+2, you want to:', 'Continue higher education', 'Start working', 'Learn a skill or trade', 'Start a small business', 3, 'Leadership'),
('Which activity excites you most?', 'Solving puzzles or brain teasers', 'Performing or acting', 'Volunteering for social causes', 'Building or fixing things', 3, 'Practical');

-- Sample Assessment Questions
-- Streamlined to 5 key questions for better user experience
-- Updated to be more relevant based on user interests
INSERT INTO assessment_questions (question, options, category) VALUES
('What type of work excites you most?', '[{"option":"Solving complex problems","weight":3},{"option":"Creating art or designs","weight":2},{"option":"Helping people directly","weight":1},{"option":"Managing projects or teams","weight":2}]', 'Interest'),
('How do you prefer to work?', '[{"option":"Independently with focus","weight":2},{"option":"Collaborating with others","weight":3},{"option":"Leading a team","weight":1},{"option":"Mix of both","weight":2}]', 'WorkStyle'),
('What matters most in your career?', '[{"option":"Job security and stability","weight":1},{"option":"Creativity and freedom","weight":2},{"option":"Making a positive impact","weight":3},{"option":"Financial growth","weight":2}]', 'Values'),
('Which environment suits you best?', '[{"option":"Quiet office setting","weight":2},{"option":"Dynamic team environment","weight":3},{"option":"Outdoor/field work","weight":1},{"option":"Creative workspace","weight":2}]', 'Environment'),
('What are your strengths?', '[{"option":"Analytical thinking","weight":3},{"option":"Creative expression","weight":2},{"option":"People skills","weight":1},{"option":"Organization skills","weight":2}]', 'Strengths');

-- Insert Admin Account (username: admin, password: admin123)
INSERT INTO admins (username, password) VALUES 
('admin', '$2y$10$/93JRy1yD7l/8ZnOHueih.4SWHpFMrUvLgnkX7skJPlZNe');

-- Show success message
SELECT 'Database setup completed successfully!' as Status;