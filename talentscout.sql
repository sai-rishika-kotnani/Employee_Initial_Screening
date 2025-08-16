-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2025 at 03:38 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `talentscout`
--

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

CREATE TABLE `candidates` (
  `id` int(11) NOT NULL,
  `auth_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `experience` int(11) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `tech_stack` text DEFAULT NULL,
  `submission_status` varchar(50) DEFAULT 'Not Started',
  `evaluation_status` varchar(50) DEFAULT 'Pending',
  `feedback_status` varchar(50) DEFAULT 'Unavailable',
  `answers` text DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `candidates`
--

INSERT INTO `candidates` (`id`, `auth_id`, `name`, `phone`, `experience`, `position`, `location`, `tech_stack`, `submission_status`, `evaluation_status`, `feedback_status`, `answers`, `feedback`, `score`, `email`) VALUES
(1, NULL, 'Sindhu', '9234567682', 2, 'SDE', 'Hyd', 'C,java', 'Submitted', 'Done', 'Available', 'Q1: cppp\nQ2: Darvinii\nQ3: Sindhuu\n', '1. Score: 1/10\r\nFeedback: The answer provided does not address the question at all. It is not relevant to the differences between C and C++ programming languages.\r\n\r\n2. Score: 2/10\r\nFeedback: The answer provided is not relevant to implementing polymorphism in C++ programming. It lacks explanation and does not address the importance of polymorphism in object-oriented design.\r\n\r\n3. Score: 3/10\r\nFeedback: The answer provided is not relevant to debugging memory leaks in a C program. It lacks specific tools or techniques and does not provide a clear approach to identifying and resolving memory leaks.', 1, NULL),
(2, NULL, 'Hansika', NULL, NULL, NULL, NULL, NULL, 'Submitted', 'Done', 'Available', 'Q1: Python has simpler and more readable syntax compared to Java—it\'s great for beginners and quick development. Java is more verbose but strongly typed, which helps with large-scale applications. In terms of performance, Java is faster than Python because it\'s compiled to bytecode and runs on the JVM, while Python is interpreted. Python excels in data science, scripting, and automation, while Java is strong in building enterprise-level applications, Android development, and backend systems.\nQ2: To achieve interoperability between Python and Java, I’d use tools like Jython (Python on the JVM) or Py4J, which allows Python programs to access Java objects. For example, if I had a Python-based data analysis tool and wanted to use an existing Java library for machine learning, I’d use Py4J to connect them. The Python script can start a Java gateway and call Java methods directly. This setup lets both languages work together without rewriting code, making it efficient for combining strengths like Python’s data handling and Java’s performance.\nQ3: Python, with frameworks like Django and Flask, allows for fast and easy web development due to its simple syntax and rapid prototyping. It’s great for startups and data-driven applications. Java, using frameworks like Spring Boot, offers more structure, performance, and scalability, making it ideal for large enterprise applications.\r\n\r\nIn one of my learning projects, I used Python (Flask) for building a lightweight web interface and Java for handling backend processing through APIs. The challenge was managing communication between them, which I handled using RESTful APIs to keep the components loosely coupled but well-integrated.\n', 'Q1: The answer provides a thorough comparison between Python and Java in terms of syntax, performance, and use cases. It also highlights the strengths of each language. Overall, the answer is well-structured and informative. Score: 9/10\r\nQ2: The answer demonstrates knowledge of tools like Jython and Py4J that enable interoperability between Python and Java. The example provided is clear and relevant. However, it could benefit from more detailed steps on how to optimize performance. Score: 8/10\r\nQ3: The answer effectively explains how Python and Java were used in a project, with specific frameworks mentioned for each language and the challenges faced during integration. Details on the integration process could be expanded further for a deeper understanding. Score: 8/10\r\n\r\nOverall, the answers are well-informed and provide valuable insights into the differences between Python and Java, optimizing performance, and integrating both languages in a project.', 9, 'hansikabose0011@gmail.com'),
(7, NULL, 'Hansika Bose', '9951886476', 2, 'Software Engineer', 'Banglore', 'Python,Java', 'Submitted', 'Pending', 'Unavailable', NULL, NULL, NULL, NULL),
(8, NULL, 'Hansika Bose', '9951886476', 2, 'Software Engineer', 'Banglore', 'Python,Java', 'Submitted', 'Pending', 'Unavailable', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `candidate_auth`
--

CREATE TABLE `candidate_auth` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `candidate_auth`
--

INSERT INTO `candidate_auth` (`id`, `email`, `password`) VALUES
(1, 'sindhu@123', 'sindhu123'),
(2, 'hansikabose0011@gmail.com', 'Han123');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `candidate_id` int(11) DEFAULT NULL,
  `recruiter_id` int(11) DEFAULT NULL,
  `problem_solving` varchar(50) DEFAULT NULL,
  `communication` varchar(50) DEFAULT NULL,
  `overall_feedback` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recruiters`
--

CREATE TABLE `recruiters` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recruiters`
--

INSERT INTO `recruiters` (`id`, `name`, `password`, `email`) VALUES
(1, 'Happy', 'hello123', 'happy@123'),
(2, 'Gojo Satoru', 'gojo', 'gojo@123');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `candidates`
--
ALTER TABLE `candidates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auth_id` (`auth_id`);

--
-- Indexes for table `candidate_auth`
--
ALTER TABLE `candidate_auth`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `candidate_id` (`candidate_id`),
  ADD KEY `recruiter_id` (`recruiter_id`);

--
-- Indexes for table `recruiters`
--
ALTER TABLE `recruiters`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `candidates`
--
ALTER TABLE `candidates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `candidate_auth`
--
ALTER TABLE `candidate_auth`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recruiters`
--
ALTER TABLE `recruiters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `candidates`
--
ALTER TABLE `candidates`
  ADD CONSTRAINT `candidates_ibfk_1` FOREIGN KEY (`auth_id`) REFERENCES `candidate_auth` (`id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`),
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`recruiter_id`) REFERENCES `recruiters` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
