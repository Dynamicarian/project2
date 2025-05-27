-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2025 at 02:26 AM
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
-- Database: `cai_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `applicants`
--

CREATE TABLE `applicants` (
  `applicant_id` int(11) NOT NULL,
  `first_name` varchar(20) DEFAULT NULL,
  `last_name` varchar(20) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('Female','Male','Other') DEFAULT NULL,
  `street_address` varchar(40) DEFAULT NULL,
  `suburb` varchar(40) DEFAULT NULL,
  `state` enum('ACT','NSW','NT','QLD','SA','TAS','VIC','WA') DEFAULT NULL,
  `postcode` char(4) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(12) DEFAULT NULL,
  `other_skills` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applicants`
--

INSERT INTO `applicants` (`applicant_id`, `first_name`, `last_name`, `date_of_birth`, `gender`, `street_address`, `suburb`, `state`, `postcode`, `email`, `phone`, `other_skills`) VALUES
(31, 'Tina', 'Fey', '2025-04-29', 'Female', '24 Wakefield Street', 'Hawthorn', 'VIC', '3122', '12345678@gmail.com', '0422676682', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `applicant_skills`
--

CREATE TABLE `applicant_skills` (
  `applicant_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applicant_skills`
--

INSERT INTO `applicant_skills` (`applicant_id`, `skill_id`) VALUES
(31, 1),
(31, 2),
(31, 3);

-- --------------------------------------------------------

--
-- Table structure for table `eoi`
--

CREATE TABLE `eoi` (
  `eoi_id` int(11) NOT NULL,
  `applicant_id` int(11) NOT NULL,
  `ref_id` varchar(10) NOT NULL,
  `status` enum('New','Current','Final') DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eoi`
--

INSERT INTO `eoi` (`eoi_id`, `applicant_id`, `ref_id`, `status`) VALUES
(35, 31, 'DEV78', 'New');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `ref_id` varchar(10) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `responsibilities` text DEFAULT NULL,
  `essential_qualifications` text DEFAULT NULL,
  `ideal_qualifications` text DEFAULT NULL,
  `salary` varchar(20) DEFAULT NULL,
  `reports_to` varchar(50) DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`ref_id`, `title`, `description`, `responsibilities`, `essential_qualifications`, `ideal_qualifications`, `salary`, `reports_to`, `image`) VALUES
('DEV78', 'Senior Developer', 'Responsible for designing, developing, and maintaining complex software solutions across multiple platforms. Provides technical leadership on strategic initiatives and architectural decisions.', 'Design, develop, test, and deploy scalable software applications and services.\r\nCollaborate with cross-functional teams to gather requirements and define technical specifications.\r\nLead code reviews, mentor junior developers, and enforce coding standards.\r\nOptimize application performance, troubleshoot bugs, and implement enhancements.\r\nContribute to architecture planning and participate in agile development practices.', 'Proficiency in modern programming languages (e.g., Java, C#, Python, or JavaScript/TypeScript).', 'Experience with cloud platforms (AWS/Azure/GCP), containerization (Docker/Kubernetes), CI/CD pipelines, and frameworks such as React, Angular, or .NET Core.', '120K - 145K', 'Software Development Manager', 'images/seniorsoftwaredeveloper.png'),
('ITA27', 'IT Support Technician', 'Provides frontline technical support for hardware, software, network issues, and supports day-to-day IT operations.', 'Offer first- and second-line support (in-person/remotely).\r\nMaintain and troubleshoot hardware, Windows/macOS systems, Microsoft Office, and Microsoft 365.\r\nManage user accounts, resolve connectivity issues, document support tickets.\r\nAssist with onboarding/offboarding and contribute to IT security and backup efforts.', 'IT support experience, strong Windows/macOS knowledge, effective communication skills.', 'Certifications (CompTIA A+/Network+, Microsoft Certified), experience with ticketing systems, and basic networking knowledge (TCP/IP, DNS, DHCP, VPN).', '70K - 85K', 'IT Manager', 'images/ITSupportTechnician.png'),
('SYS42', 'Systems Administrator', 'Responsible for maintaining and optimizing IT infrastructure, including servers, networks, and enterprise systems. Ensures system reliability, security, and performance.', 'Manage physical and virtual servers (Windows/Linux).\r\nSupport Active Directory, file shares, Microsoft 365, Azure.\r\nHandle updates, patches, backups, disaster recovery, and security enforcement.\r\nTroubleshoot infrastructure issues and lead IT projects (e.g., server migrations, cloud integration).', 'Experience with Windows Server, Active Directory, DNS, DHCP, virtualization (Hyper-V/VMware), and strong troubleshooting skills.', 'Certifications (Azure, CompTIA Server+, VMware), Linux/scripting experience, and automation tools like PowerShell or Ansible.', '95K - 115K', 'IT Manager', 'images/systemsadmin.png');

-- --------------------------------------------------------

--
-- Table structure for table `manager_creds`
--

CREATE TABLE `manager_creds` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `failed_attempts` int(11) DEFAULT 0,
  `last_failed_login` datetime DEFAULT NULL,
  `locked_until` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manager_creds`
--

INSERT INTO `manager_creds` (`id`, `username`, `password_hash`, `failed_attempts`, `last_failed_login`, `locked_until`) VALUES
(1, 'christina', '$2y$10$ZgS/vzHSD79LNQTcYA6JqusEeP7pQplhyDO35uLkHKEDkzEv/cIwi', 0, '2025-05-25 13:31:59', NULL),
(2, 'manager', '$2y$10$xMNHSM0WpQvRJGGD6hshpea2ekNPju1a8WHmvG4aQVDG6hlNgkIfy', 4, '2025-05-25 18:53:21', '2025-05-25 11:03:21'),
(3, 'Me', '$2y$10$Z9PbXskkRcDjetM3/eVYEOMLNu2yzuvIwIQbhgGDPSBFJF2.it5sm', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `skill_id` int(11) NOT NULL,
  `skill_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`skill_id`, `skill_name`, `description`, `created_at`) VALUES
(1, 'Technical Support', 'Troubleshoots hardware/software issues (Windows & macOS)', '2025-05-24 06:56:21'),
(2, 'System Administration', 'Proficient with Windows Server, Group Policy, DNS, DHCP', '2025-05-24 06:56:21'),
(3, 'Problem Solving and Communication', 'Strong troubleshooting, documentation, and user support skills', '2025-05-24 06:56:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applicants`
--
ALTER TABLE `applicants`
  ADD PRIMARY KEY (`applicant_id`);

--
-- Indexes for table `applicant_skills`
--
ALTER TABLE `applicant_skills`
  ADD PRIMARY KEY (`applicant_id`,`skill_id`),
  ADD KEY `skill_id` (`skill_id`);

--
-- Indexes for table `eoi`
--
ALTER TABLE `eoi`
  ADD PRIMARY KEY (`eoi_id`),
  ADD KEY `applicant_id` (`applicant_id`),
  ADD KEY `ref_id` (`ref_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`ref_id`);

--
-- Indexes for table `manager_creds`
--
ALTER TABLE `manager_creds`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`skill_id`),
  ADD UNIQUE KEY `skill_name` (`skill_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applicants`
--
ALTER TABLE `applicants`
  MODIFY `applicant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `eoi`
--
ALTER TABLE `eoi`
  MODIFY `eoi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `manager_creds`
--
ALTER TABLE `manager_creds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `skill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applicant_skills`
--
ALTER TABLE `applicant_skills`
  ADD CONSTRAINT `applicant_skills_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicant_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`skill_id`) ON DELETE CASCADE;

--
-- Constraints for table `eoi`
--
ALTER TABLE `eoi`
  ADD CONSTRAINT `eoi_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `eoi_ibfk_2` FOREIGN KEY (`ref_id`) REFERENCES `jobs` (`ref_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
