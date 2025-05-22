-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2025 at 02:33 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

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
-- Table structure for table `christina_test`
--

CREATE TABLE `christina_test` (
  `EOInumber` int(11) NOT NULL,
  `job_reference_number` varchar(10) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `street_address` varchar(100) NOT NULL,
  `suburb` varchar(50) NOT NULL,
  `state` varchar(3) NOT NULL,
  `postcode` varchar(4) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `skill1` varchar(50) NOT NULL,
  `skill2` varchar(50) NOT NULL,
  `skill3` varchar(50) NOT NULL,
  `other_skills` text NOT NULL,
  `status` enum('New','Current','Final') NOT NULL DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `christina_test`
--

INSERT INTO `christina_test` (`EOInumber`, `job_reference_number`, `first_name`, `last_name`, `street_address`, `suburb`, `state`, `postcode`, `email`, `phone`, `skill1`, `skill2`, `skill3`, `other_skills`, `status`) VALUES
(5, 'REF005', 'Eva', 'Brown', '56 Collins St', 'Hobart', 'TAS', '7000', 'eva.brown@example.com', '0455555555', 'HTML', 'PHP', 'CSS', 'Great communication', 'Final'),
(6, 'REF006', 'Frank', 'White', '67 Burke Rd', 'Canberra', 'ACT', '2600', 'frank.white@example.com', '0466666666', 'JavaScript', 'SQL', 'React', 'Detail-oriented', 'Current'),
(7, 'REF007', 'Grace', 'Kim', '78 Swanston St', 'Darwin', 'NT', '0800', 'grace.kim@example.com', '0477777777', 'Node.js', 'HTML', 'CSS', 'Quick learner', 'New'),
(8, 'REF008', 'Henry', 'Wong', '89 Bourke St', 'Perth', 'WA', '6000', 'henry.wong@example.com', '0488888888', 'C#', '.NET', 'SQL', 'Great at debugging', 'Current'),
(9, 'REF009', 'Ivy', 'Taylor', '90 Exhibition St', 'Melbourne', 'VIC', '3000', 'ivy.taylor@example.com', '0499999999', 'Python', 'HTML', 'Flask', 'Works well under pressure', 'New'),
(10, 'REF010', 'Jack', 'Brown', '101 Lonsdale St', 'Sydney', 'NSW', '2000', 'jack.brown@example.com', '0400000000', 'Java', 'SQL', 'Angular', 'Fast learner', 'New'),
(11, 'REF011', 'Kelly', 'Green', '11 York St', 'Brisbane', 'QLD', '4000', 'kelly.green@example.com', '0401010101', 'JavaScript', 'CSS', 'PHP', 'Experience with REST APIs', 'New'),
(12, 'REF012', 'Leo', 'Clark', '22 George St', 'Adelaide', 'SA', '5000', 'leo.clark@example.com', '0402020202', 'MySQL', 'Java', 'Git', 'Can work independently', 'New'),
(13, 'REF013', 'Maya', 'Hill', '33 Macquarie St', 'Hobart', 'TAS', '7000', 'maya.hill@example.com', '0403030303', 'HTML', 'CSS', 'Python', 'Problem-solving mindset', 'New'),
(14, 'REF014', 'Nathan', 'Young', '44 Adelaide St', 'Canberra', 'ACT', '2600', 'nathan.young@example.com', '0404040404', 'Python', 'Linux', 'AWS', 'Excellent communicator', 'New'),
(15, 'REF015', 'Olivia', 'Scott', '55 Victoria Rd', 'Darwin', 'NT', '0800', 'olivia.scott@example.com', '0405050505', 'SQL', 'HTML', 'CSS', 'Team management skills', 'Current'),
(16, 'REF016', 'Peter', 'Adams', '66 King William St', 'Perth', 'WA', '6000', 'peter.adams@example.com', '0406060606', 'PHP', 'JavaScript', 'CSS', 'Friendly and reliable', 'New'),
(17, 'REF017', 'Quinn', 'Carter', '77 Flinders St', 'Melbourne', 'VIC', '3000', 'quinn.carter@example.com', '0407070707', 'Python', 'Pandas', 'SQL', 'Data-driven thinker', 'New'),
(18, 'REF018', 'Rachel', 'Brooks', '88 William St', 'Sydney', 'NSW', '2000', 'rachel.brooks@example.com', '0408080808', 'React', 'Node.js', 'CSS', 'Has worked on group projects', 'Current'),
(19, 'REF019', 'Sam', 'Reid', '99 Market St', 'Brisbane', 'QLD', '4000', 'sam.reid@example.com', '0409090909', 'Java', 'HTML', 'CSS', 'Can meet tight deadlines', 'New');

-- --------------------------------------------------------

--
-- Table structure for table `eoi`
--

CREATE TABLE `eoi` (
  `EOInumber` int(11) NOT NULL,
  `job_reference` varchar(5) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('female','male','other') NOT NULL,
  `street_address` varchar(40) NOT NULL,
  `suburb` varchar(40) NOT NULL,
  `state` enum('ACT','NSW','NT','QLD','SA','TAS','VIC','WA') NOT NULL,
  `postcode` char(4) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `technical_support` tinyint(1) NOT NULL,
  `system_administration` tinyint(1) NOT NULL,
  `problem_solving_and_communication` tinyint(1) NOT NULL,
  `other_skills` text DEFAULT NULL,
  `status` enum('New','Current','Final') DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `title` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `responsibilities` text NOT NULL,
  `essential_qualifications` text NOT NULL,
  `ideal_qualifications` text NOT NULL,
  `salary` varchar(20) NOT NULL,
  `ref_id` varchar(10) NOT NULL,
  `reports_to` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`title`, `description`, `responsibilities`, `essential_qualifications`, `ideal_qualifications`, `salary`, `ref_id`, `reports_to`) VALUES
('IT Support Technician', 'Provides frontline technical support for hardware, software, and network issues. Ensures effective communication between users and IT, and supports day-to-day IT operations.', 'Offer first- and second-line support (in-person/remotely).\r\n\r\nMaintain and troubleshoot hardware, Windows/macOS systems, Microsoft Office, and Microsoft 365.\r\n\r\nManage user accounts, resolve connectivity issues, document support tickets.\r\n\r\nAssist with onboarding/offboarding and contribute to IT security and backup efforts.', 'IT support experience, strong Windows/macOS knowledge, effective communication skills.', 'Certifications (CompTIA A+/Network+, Microsoft Certified), experience with ticketing systems, and basic networking knowledge (TCP/IP, DNS, DHCP, VPN).', '70K - 85K', 'ITA27', 'IT Manager'),
('Systems Administrator', 'Responsible for maintaining and optimizing IT infrastructure, including servers, networks, and enterprise systems. Ensures system reliability, security, and performance, and plays a key role in technical projects and upgrades.', 'Manage physical and virtual servers (Windows/Linux).\r\n\r\nSupport Active Directory, file shares, Microsoft 365, Azure.\r\n\r\nHandle updates, patches, backups, disaster recovery, and security enforcement.\r\n\r\nTroubleshoot infrastructure issues and lead IT projects (e.g., server migrations, cloud integration).', 'Experience with Windows Server, Active Directory, DNS, DHCP, virtualization (Hyper-V/VMware), and strong troubleshooting skills.', ' Certifications (Azure, CompTIA Server+, VMware), Linux/scripting experience, and automation tools like PowerShell or Ansible.', '95K - 115K', 'SYS42', 'IT Manager');

-- --------------------------------------------------------

--
-- Table structure for table `manager_creds`
--

CREATE TABLE `manager_creds` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `failed_attempts` int(11) DEFAULT 0,
  `last_failed_login` datetime DEFAULT NULL,
  `locked_until` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manager_creds`
--

INSERT INTO `manager_creds` (`id`, `username`, `password_hash`, `failed_attempts`, `last_failed_login`, `locked_until`) VALUES
(1, 's105326824', '$2y$10$aDFt/FPdJjisRWMnVNssNudgH4/0Q2BOPtGTKWtLSyqxwweRijkW6', 0, NULL, NULL),
(2, 'christina', '$2y$10$nvjM7wIVmhVLt3toQ/5qlOMST.tOFx.s9MWPlBQcwqn4pl7MDtD5u', 3, '2025-05-22 11:50:53', '2025-05-22 04:00:53'),
(3, 'manager', '$2y$10$eSYfMOKp6/Sv8IiIvIyDpuhSJGeNINbmpKRyLhnPqcOzs8v03YMlq', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `test_table`
--

CREATE TABLE `test_table` (
  `user_id` int(11) NOT NULL,
  `a` int(11) NOT NULL,
  `b` int(11) NOT NULL,
  `c` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `christina_test`
--
ALTER TABLE `christina_test`
  ADD PRIMARY KEY (`EOInumber`);

--
-- Indexes for table `manager_creds`
--
ALTER TABLE `manager_creds`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `test_table`
--
ALTER TABLE `test_table`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `christina_test`
--
ALTER TABLE `christina_test`
  MODIFY `EOInumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `manager_creds`
--
ALTER TABLE `manager_creds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `test_table`
--
ALTER TABLE `test_table`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
