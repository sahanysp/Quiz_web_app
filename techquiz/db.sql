-- Tech Quiz Database Setup
CREATE DATABASE IF NOT EXISTS techquiz;
USE techquiz;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(255)
);

-- Questions table
CREATE TABLE IF NOT EXISTS questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    question TEXT NOT NULL,
    option_a VARCHAR(255) NOT NULL,
    option_b VARCHAR(255) NOT NULL,
    option_c VARCHAR(255) NOT NULL,
    option_d VARCHAR(255) NOT NULL,
    correct_answer CHAR(1) NOT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Scores table
CREATE TABLE IF NOT EXISTS scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    score INT NOT NULL,
    total INT NOT NULL,
    played_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Contact messages
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seed categories
INSERT INTO categories (name, description) VALUES
('Web Development', 'HTML, CSS, JavaScript, PHP and web technologies'),
('Networking', 'TCP/IP, DNS, HTTP, protocols and networking basics'),
('Programming', 'General programming concepts, algorithms and data structures'),
('Cybersecurity', 'Security concepts, encryption, vulnerabilities');

-- Seed questions - Web Development
INSERT INTO questions (category_id, question, option_a, option_b, option_c, option_d, correct_answer) VALUES
(1, 'What does HTML stand for?', 'Hyper Text Markup Language', 'High Tech Modern Language', 'Home Tool Markup Language', 'Hyperlinks and Text Markup Language', 'A'),
(1, 'Which CSS property is used to change the text color?', 'font-color', 'text-color', 'color', 'foreground-color', 'C'),
(1, 'What does CSS stand for?', 'Creative Style Sheets', 'Cascading Style Sheets', 'Computer Style Sheets', 'Colorful Style Sheets', 'B'),
(1, 'Which HTML tag is used for the largest heading?', '<h6>', '<heading>', '<h1>', '<head>', 'C'),
(1, 'What does PHP stand for?', 'Personal Home Page', 'PHP: Hypertext Preprocessor', 'Private Home Page', 'Pre Hypertext Processor', 'B'),
(1, 'Which JavaScript method is used to select an element by ID?', 'getElementById()', 'getElement()', 'selectById()', 'queryId()', 'A'),
(1, 'What is Bootstrap primarily used for?', 'Back-end development', 'Database management', 'Responsive front-end design', 'Server configuration', 'C'),
(1, 'Which HTTP method is used to submit form data?', 'GET', 'POST', 'PUT', 'DELETE', 'B'),
(1, 'What does DOM stand for?', 'Document Object Model', 'Data Object Management', 'Display Object Model', 'Document Oriented Module', 'A'),
(1, 'Which SQL command retrieves data from a table?', 'INSERT', 'UPDATE', 'SELECT', 'DELETE', 'C'),

-- Networking questions
(2, 'What does IP stand for?', 'Internet Protocol', 'Internal Processor', 'Input Port', 'Interface Protocol', 'A'),
(2, 'Which port does HTTP use by default?', '21', '443', '80', '25', 'C'),
(2, 'What does DNS stand for?', 'Data Network System', 'Domain Name System', 'Dynamic Network Service', 'Domain Node Server', 'B'),
(2, 'What is the purpose of a firewall?', 'Speed up internet', 'Monitor network traffic and block threats', 'Store web pages', 'Assign IP addresses', 'B'),
(2, 'Which layer of OSI model handles routing?', 'Physical layer', 'Data Link layer', 'Network layer', 'Transport layer', 'C'),
(2, 'What does HTTPS stand for?', 'Hyper Transfer Protocol Secure', 'HyperText Transfer Protocol Secure', 'High Tech Protocol Secure', 'HyperText Transport Protocol Standard', 'B'),
(2, 'What is the purpose of DHCP?', 'Translate domain names', 'Encrypt data', 'Automatically assign IP addresses', 'Send emails', 'C'),
(2, 'What does FTP stand for?', 'Fast Transfer Protocol', 'File Transfer Protocol', 'Firewall Transfer Process', 'File Type Protocol', 'B'),
(2, 'How many bits are in an IPv4 address?', '16', '64', '128', '32', 'D'),
(2, 'Which protocol sends email?', 'POP3', 'SMTP', 'IMAP', 'FTP', 'B'),

-- Programming questions
(3, 'What is a variable in programming?', 'A fixed value', 'A storage location for data', 'A type of loop', 'A function call', 'B'),
(3, 'What does OOP stand for?', 'Object Oriented Programming', 'Open Output Protocol', 'Optimized Output Process', 'Object Oriented Process', 'A'),
(3, 'Which data structure uses LIFO?', 'Queue', 'Array', 'Stack', 'Tree', 'C'),
(3, 'What is recursion?', 'A loop structure', 'A function calling itself', 'An error type', 'A data type', 'B'),
(3, 'What is a boolean data type?', 'Stores numbers only', 'Stores text', 'Stores true or false values', 'Stores arrays', 'C'),
(3, 'What does API stand for?', 'Application Programming Interface', 'Applied Process Integration', 'Application Protocol Input', 'Advanced Programming Interface', 'A'),
(3, 'Which sorting algorithm has O(n log n) average complexity?', 'Bubble Sort', 'Selection Sort', 'Merge Sort', 'Insertion Sort', 'C'),
(3, 'What is a compiler?', 'A text editor', 'A program that translates source code to machine code', 'An operating system', 'A database', 'B'),
(3, 'What does SQL stand for?', 'Structured Query Language', 'Simple Query Language', 'Standard Question Logic', 'System Query Language', 'A'),
(3, 'What is Git used for?', 'Web design', 'Version control', 'Database management', 'Network monitoring', 'B'),

-- Cybersecurity questions
(4, 'What does SSL stand for?', 'Secure Server Layer', 'Secure Sockets Layer', 'Standard Security Layer', 'System Security Link', 'B'),
(4, 'What is phishing?', 'A type of virus', 'A fraudulent attempt to obtain sensitive information', 'A network protocol', 'A hacking tool', 'B'),
(4, 'What is two-factor authentication?', 'Using two passwords', 'Two-step verification using two different factors', 'Logging in twice', 'Having two user accounts', 'B'),
(4, 'What does encryption do?', 'Speeds up data transfer', 'Deletes sensitive data', 'Converts data to unreadable format', 'Compresses files', 'C'),
(4, 'What is a DDoS attack?', 'Data Deletion on Demand System', 'Distributed Denial of Service attack', 'Direct Download of Software', 'Dynamic DNS Override System', 'B'),
(4, 'What is a VPN?', 'Very Private Network', 'Virtual Private Network', 'Virtual Protocol Node', 'Verified Public Network', 'B'),
(4, 'What is malware?', 'A security software', 'A type of hardware', 'Malicious software', 'A network protocol', 'C'),
(4, 'What does XSS stand for in web security?', 'Extra Secure Script', 'Cross-Site Scripting', 'Extended Style Sheets', 'Cross-Server System', 'B'),
(4, 'What is a brute force attack?', 'Physically breaking a server', 'Trying all possible passwords', 'Deleting files', 'Intercepting network traffic', 'B'),
(4, 'What is SQL injection?', 'Adding data to a database', 'A type of database', 'Inserting malicious SQL code into a query', 'A backup method', 'C');
