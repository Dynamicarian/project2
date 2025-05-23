-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2025 at 10:14 AM
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
-- Table structure for table `eoi`
--

CREATE TABLE `eoi` (
  `EOInumber` int(10) NOT NULL,
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

--
-- Dumping data for table `eoi`
--

INSERT INTO `eoi` (`EOInumber`, `job_reference`, `first_name`, `last_name`, `date_of_birth`, `gender`, `street_address`, `suburb`, `state`, `postcode`, `email`, `phone`, `technical_support`, `system_administration`, `problem_solving_and_communication`, `other_skills`, `status`) VALUES
(3, 'J5678', 'Charlie', 'Lee', '1995-08-22', 'other', '789 King St', 'Adelaide', 'SA', '5000', 'charlie@example.com', '0423456789', 0, 1, 0, 'Linux sysadmin', 'Final'),
(4, 'J4321', 'Diana', 'White', '2000-01-10', 'female', '321 High St', 'Hobart', 'TAS', '7000', 'diana@example.com', '0434567890', 1, 1, 1, NULL, 'New'),
(5, 'J4321', 'Ethan', 'Brown', '1992-12-12', 'male', '654 Elm St', 'Darwin', 'NT', '0800', 'ethan@example.com', '0445678901', 0, 0, 1, 'Interpersonal skills', 'Current'),
(6, 'J1001', 'Fiona', 'Nguyen', '1994-03-18', 'female', '8 Oak St', 'Canberra', 'ACT', '2600', 'fiona@example.com', '0456789012', 1, 0, 1, NULL, 'Final'),
(7, 'J1002', 'George', 'Patel', '1988-07-07', 'male', '22 Birch Rd', 'Perth', 'WA', '6000', 'george@example.com', '0467890123', 1, 1, 1, 'SQL, Java', 'New'),
(8, 'J1003', 'Hannah', 'Kim', '1993-09-09', 'female', '14 Pine Rd', 'Sydney', 'NSW', '2000', 'hannah@example.com', '0478901234', 0, 1, 0, 'MacOS admin', 'Current'),
(9, 'J1004', 'Ian', 'Wong', '1987-06-30', 'male', '5 Maple Ave', 'Melbourne', 'VIC', '3001', 'ian@example.com', '0489012345', 1, 1, 0, 'Database tuning', 'Final'),
(10, 'J1005', 'Jane', 'Davis', '1996-11-25', 'female', '9 Cedar St', 'Geelong', 'VIC', '3220', 'jane@example.com', '0490123456', 0, 0, 1, NULL, 'New'),
(11, 'J1011', 'Kyle', 'Allen', '1991-05-15', 'male', '17 Bluegum St', 'Darwin', 'NT', '0801', 'kyle@example.com', '0401000001', 1, 0, 0, 'JavaScript expert', 'Current'),
(12, 'J1012', 'Lily', 'Brown', '1992-02-21', 'female', '29 Willow Rd', 'Brisbane', 'QLD', '4001', 'lily@example.com', '0401000002', 1, 1, 0, NULL, 'Final'),
(13, 'J1013', 'Mason', 'Clark', '1993-03-03', 'male', '34 Banksia Blvd', 'Adelaide', 'SA', '5001', 'mason@example.com', '0401000003', 0, 1, 1, 'Customer service', 'New'),
(14, 'J1014', 'Nora', 'Dean', '1994-04-04', 'female', '23 Eucalypt Ln', 'Hobart', 'TAS', '7001', 'nora@example.com', '0401000004', 1, 1, 1, NULL, 'Current'),
(15, 'J1015', 'Oscar', 'Evans', '1995-05-05', 'other', '66 Acacia Ave', 'Canberra', 'ACT', '2601', 'oscar@example.com', '0401000005', 0, 0, 0, 'Graphic design', 'Final'),
(16, 'J1016', 'Paula', 'Foster', '1996-06-06', 'female', '19 Wattle Dr', 'Perth', 'WA', '6001', 'paula@example.com', '0401000006', 1, 0, 1, 'Vue.js experience', 'New'),
(17, 'J1017', 'Quinn', 'Green', '1997-07-07', 'male', '90 Palm Ct', 'Sydney', 'NSW', '2001', 'quinn@example.com', '0401000007', 1, 1, 0, NULL, 'Current'),
(18, 'J1018', 'Riley', 'Hill', '1998-08-08', 'female', '5 Poplar St', 'Melbourne', 'VIC', '3002', 'riley@example.com', '0401000008', 0, 1, 1, NULL, 'Final'),
(19, 'J1019', 'Sam', 'Ingram', '1999-09-09', 'male', '88 Spruce Rd', 'Geelong', 'VIC', '3221', 'sam@example.com', '0401000009', 1, 0, 0, 'Excel, SAP', 'New'),
(20, 'J1020', 'Tina', 'Jones', '2000-10-10', 'female', '6 Magnolia Way', 'Darwin', 'NT', '0802', 'tina@example.com', '0401000010', 0, 1, 1, 'Team leadership', 'Current'),
(21, 'J1021', 'Uma', 'Klein', '1992-12-12', 'female', '11 Redwood St', 'Brisbane', 'QLD', '4002', 'uma@example.com', '0401000011', 1, 1, 1, NULL, 'Final'),
(22, 'J1022', 'Victor', 'Lewis', '1983-11-11', 'male', '22 Hazel St', 'Adelaide', 'SA', '5002', 'victor@example.com', '0401000012', 0, 0, 1, NULL, 'New'),
(23, 'J1023', 'Wendy', 'Martin', '1989-10-10', 'female', '33 Myrtle St', 'Hobart', 'TAS', '7002', 'wendy@example.com', '0401000013', 1, 0, 0, 'Admin support', 'Current'),
(24, 'J1024', 'Xavier', 'Nash', '1990-09-09', 'other', '44 Sassafras St', 'Canberra', 'ACT', '2602', 'xavier@example.com', '0401000014', 1, 1, 0, 'Node.js, React', 'Final'),
(25, 'J1025', 'Yara', 'Olsen', '1991-08-08', 'female', '55 Waratah Rd', 'Perth', 'WA', '6002', 'yara@example.com', '0401000015', 0, 1, 1, NULL, 'New'),
(26, 'J1026', 'Zane', 'Peterson', '1984-07-07', 'male', '10 Ironbark St', 'Sydney', 'NSW', '2002', 'zane@example.com', '0401000016', 1, 1, 1, 'REST APIs, Docker', 'Current'),
(27, 'J1027', 'Abby', 'Quinn', '1993-06-06', 'female', '12 Bottlebrush Ave', 'Melbourne', 'VIC', '3003', 'abby@example.com', '0401000017', 0, 0, 1, NULL, 'Final'),
(28, 'J1028', 'Ben', 'Reed', '1986-05-05', 'male', '13 Casuarina Way', 'Geelong', 'VIC', '3222', 'ben@example.com', '0401000018', 1, 0, 0, 'C++ development', 'New'),
(29, 'J1029', 'Cara', 'Shaw', '1988-04-04', 'female', '14 Mahogany Blvd', 'Darwin', 'NT', '0803', 'cara@example.com', '0401000019', 0, 1, 1, 'Excellent communicator', 'Current'),
(30, 'J1030', 'Dev', 'Tran', '1991-03-03', 'other', '15 Cedar Ct', 'Brisbane', 'QLD', '4003', 'dev@example.com', '0401000020', 1, 1, 0, NULL, 'Final'),
(31, 'J1031', 'Ella', 'Upton', '1992-02-02', 'female', '16 Cypress Rd', 'Adelaide', 'SA', '5003', 'ella@example.com', '0401000021', 1, 0, 1, 'Content creator', 'New'),
(32, 'J1032', 'Finn', 'Vega', '1987-01-01', 'male', '17 Teatree Ln', 'Hobart', 'TAS', '7003', 'finn@example.com', '0401000022', 0, 1, 1, 'Fast learner', 'Current'),
(33, 'J1033', 'Gina', 'Wells', '1990-12-12', 'female', '18 Grevillea St', 'Canberra', 'ACT', '2603', 'gina@example.com', '0401000023', 1, 1, 1, NULL, 'Final'),
(34, 'J1034', 'Hugo', 'Xu', '1995-11-11', 'male', '19 Wisteria Rd', 'Perth', 'WA', '6003', 'hugo@example.com', '0401000024', 0, 0, 1, NULL, 'New'),
(35, 'J1035', 'Isla', 'Young', '1989-10-10', 'female', '20 Camellia St', 'Sydney', 'NSW', '2003', 'isla@example.com', '0401000025', 1, 0, 0, 'UX design', 'Current'),
(36, 'J1036', 'Jack', 'Zimmerman', '1986-09-09', 'male', '21 Jacaranda Dr', 'Melbourne', 'VIC', '3004', 'jack@example.com', '0401000026', 0, 1, 0, NULL, 'Final'),
(37, 'J1037', 'Kylie', 'Adams', '1993-08-08', 'female', '22 Tallowwood St', 'Geelong', 'VIC', '3223', 'kylie@example.com', '0401000027', 1, 1, 1, 'Full stack dev', 'New'),
(38, 'J1038', 'Leo', 'Barnes', '1985-07-07', 'male', '23 Sheoak Blvd', 'Darwin', 'NT', '0804', 'leo@example.com', '0401000028', 1, 0, 1, NULL, 'Current'),
(39, 'J1039', 'Mia', 'Chen', '1990-06-06', 'female', '24 Coral Tree Rd', 'Brisbane', 'QLD', '4004', 'mia@example.com', '0401000029', 0, 1, 1, NULL, 'Final'),
(40, 'J1040', 'Noah', 'Doyle', '1988-05-05', 'male', '25 Lemon Myrtle Way', 'Adelaide', 'SA', '5004', 'noah@example.com', '0401000030', 1, 1, 0, 'DevOps experience', 'New'),
(41, 'J1041', 'Olive', 'Eaton', '1992-04-04', 'female', '26 Fig Tree St', 'Hobart', 'TAS', '7004', 'olive@example.com', '0401000031', 0, 0, 1, NULL, 'Current'),
(42, 'J1042', 'Parker', 'Ford', '1994-03-03', 'other', '27 Flame Tree Ave', 'Canberra', 'ACT', '2604', 'parker@example.com', '0401000032', 1, 0, 0, NULL, 'Final'),
(43, 'J1043', 'Quincy', 'Grant', '1996-02-02', 'male', '28 Lilly Pilly Ln', 'Perth', 'WA', '6004', 'quincy@example.com', '0401000033', 1, 1, 1, 'Cloud computing', 'New'),
(44, 'J1044', 'Rosa', 'Hunt', '1987-01-01', 'female', '29 Kurrajong Blvd', 'Sydney', 'NSW', '2004', 'rosa@example.com', '0401000034', 0, 1, 0, NULL, 'Current'),
(45, 'J1045', 'Shane', 'Irwin', '1983-12-12', 'male', '30 Blackwood St', 'Melbourne', 'VIC', '3005', 'shane@example.com', '0401000035', 1, 1, 1, NULL, 'Final'),
(46, 'J1046', 'Tess', 'Jordan', '1991-11-11', 'female', '31 Silver Wattle Ct', 'Geelong', 'VIC', '3224', 'tess@example.com', '0401000036', 1, 0, 1, 'Event planning', 'New'),
(47, 'J1047', 'Uri', 'Kaur', '1984-10-10', 'male', '32 Redgum Rd', 'Darwin', 'NT', '0805', 'uri@example.com', '0401000037', 0, 1, 1, NULL, 'Current'),
(48, 'J1048', 'Vera', 'Lloyd', '1995-09-09', 'female', '33 Snowgum St', 'Brisbane', 'QLD', '4005', 'vera@example.com', '0401000038', 1, 1, 0, NULL, 'Final'),
(49, 'J1049', 'Will', 'Moore', '1989-08-08', 'male', '34 Spotted Gum Ave', 'Adelaide', 'SA', '5005', 'will@example.com', '0401000039', 0, 0, 1, 'Public speaking', 'New'),
(50, 'J1050', 'Xena', 'Nolan', '1997-07-07', 'female', '35 River Redgum Blvd', 'Hobart', 'TAS', '7005', 'xena@example.com', '0401000040', 1, 0, 1, NULL, 'Current');

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
  `reports_to` varchar(50) NOT NULL,
  `image` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`title`, `description`, `responsibilities`, `essential_qualifications`, `ideal_qualifications`, `salary`, `ref_id`, `reports_to`, `image`) VALUES
('Senior Developer', 'Responsible for designing, developing, and maintaining complex software solutions across multiple platforms. Provides technical leadership on strategic initiatives and architectural decisions.', 'Design, develop, test, and deploy scalable software applications and services.\r\nCollaborate with cross-functional teams to gather requirements and define technical specifications.\r\nLead code reviews, mentor junior developers, and enforce coding standards.\r\nOptimize application performance, troubleshoot bugs, and implement enhancements.\r\nContribute to architecture planning and participate in agile development practices.', 'Proficiency in modern programming languages (e.g., Java, C#, Python, or JavaScript/TypeScript).', 'Experience with cloud platforms (AWS/Azure/GCP), containerization (Docker/Kubernetes), CI/CD pipelines, and frameworks such as React, Angular, or .NET Core.', '120K - 145K', 'DEV78', 'Software Development Manager', '\"/images/seniorsoftwaredeveloper.png\"'),
('IT Support Technician', 'Provides frontline technical support for hardware, software, network issues, and supports day-to-day IT operations.', 'Offer first- and second-line support (in-person/remotely).\r\nMaintain and troubleshoot hardware, Windows/macOS systems, Microsoft Office, and Microsoft 365.\r\nManage user accounts, resolve connectivity issues, document support tickets.\r\nAssist with onboarding/offboarding and contribute to IT security and backup efforts.', 'IT support experience, strong Windows/macOS knowledge, effective communication skills.', 'Certifications (CompTIA A+/Network+, Microsoft Certified), experience with ticketing systems, and basic networking knowledge (TCP/IP, DNS, DHCP, VPN).', '70K - 85K', 'ITA27', 'IT Manager', '\"/images/ITSupportTechnician.png\"'),
('Systems Administrator', 'Responsible for maintaining and optimizing IT infrastructure, including servers, networks, and enterprise systems. Ensures system reliability, security, and performance.', 'Manage physical and virtual servers (Windows/Linux).\r\nSupport Active Directory, file shares, Microsoft 365, Azure.\r\nHandle updates, patches, backups, disaster recovery, and security enforcement.\r\nTroubleshoot infrastructure issues and lead IT projects (e.g., server migrations, cloud integration).', 'Experience with Windows Server, Active Directory, DNS, DHCP, virtualization (Hyper-V/VMware), and strong troubleshooting skills.', ' Certifications (Azure, CompTIA Server+, VMware), Linux/scripting experience, and automation tools like PowerShell or Ansible.', '95K - 115K', 'SYS42', 'IT Manager', '\"/images/systemsadmin.png\"');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`ref_id`);
COMMIT;

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
(3, 'manager', '$2y$10$eSYfMOKp6/Sv8IiIvIyDpuhSJGeNINbmpKRyLhnPqcOzs8v03YMlq', 0, NULL, NULL),
(4, 'Tristan', '$2y$10$MFhLHyUuz38hFQSHo8VhZucJzZYQn.O5QMy1SRdHyCW5R74UGMngi', 0, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eoi`
--
ALTER TABLE `eoi`
  ADD PRIMARY KEY (`EOInumber`);

--
-- Indexes for table `manager_creds`
--
ALTER TABLE `manager_creds`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `manager_creds`
--
ALTER TABLE `manager_creds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
