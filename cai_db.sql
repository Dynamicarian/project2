-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2025 at 08:28 AM
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

--
-- Dumping data for table `eoi`
--

INSERT INTO `eoi` (`EOInumber`, `job_reference`, `first_name`, `last_name`, `date_of_birth`, `gender`, `street_address`, `suburb`, `state`, `postcode`, `email`, `phone`, `technical_support`, `system_administration`, `problem_solving_and_communication`, `other_skills`, `status`) VALUES
(2147483647, 'ITA27', 'Tristan', 'Dinning', '2025-05-07', 'male', '7 Whelans Place', 'Romsey', 'VIC', '3434', 'trisdinning@gmail.com', '0444582611', 1, 0, 0, 'ezdsgrbegrbestrhyndsthmnf', 'New'),
(2147483647, 'ITA27', 'Tristan', 'Dinning', '2025-05-10', 'female', '7 Whelans Place', 'Romsey', 'VIC', '3434', 'trisdinning@gmail.com', '0444582611', 1, 0, 0, 'trhwhww54hgwr', 'New'),
(2147483647, 'ITA27', 'Tristan', 'Dinning', '2025-05-10', 'female', '7 Whelans Place', 'Romsey', 'VIC', '3434', 'trisdinning@gmail.com', '0444582611', 1, 0, 0, 'trhwhww54hgwr', 'New');

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
(3, 'manager', '$2y$10$eSYfMOKp6/Sv8IiIvIyDpuhSJGeNINbmpKRyLhnPqcOzs8v03YMlq', 0, NULL, NULL),
(4, 'Tristan', '$2y$10$MFhLHyUuz38hFQSHo8VhZucJzZYQn.O5QMy1SRdHyCW5R74UGMngi', 0, NULL, NULL);

--
-- Indexes for dumped tables
--

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
