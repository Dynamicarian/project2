-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2025 at 02:11 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
-- Table structure for table `test_table`
--

CREATE TABLE `test_table` (
  `user_id` int(11) NOT NULL,
  `a` int(11) NOT NULL,
  `b` int(11) NOT NULL,
  `c` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test_table`
--

INSERT INTO `test_table` (`user_id`, `a`, `b`, `c`) VALUES
(0, 3, 7, 8),
(1, 3, 9, 8);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`ref_id`);

--
-- Indexes for table `test_table`
--
ALTER TABLE `test_table`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `test_table`
--
ALTER TABLE `test_table`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
