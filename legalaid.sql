-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 01, 2026 at 04:56 PM
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
-- Database: `legalaid`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `lawyer_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `reason` text NOT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `client_id`, `lawyer_id`, `appointment_date`, `appointment_time`, `reason`, `status`, `created_at`) VALUES
(1, 2, 1, '2026-08-26', '10:35:00', 'Anything', 'accepted', '2026-08-26 07:36:50'),
(2, 2, 2, '2026-08-29', '14:01:00', 'Related family murder case', 'cancelled', '2026-08-30 16:17:32'),
(3, 2, 1, '2026-08-31', '07:10:00', 'AH', 'pending', '2026-08-31 12:10:59'),
(4, 2, 11, '2026-08-29', '20:47:00', 'Family issue', 'pending', '2026-08-31 16:47:22'),
(5, 2, 11, '2026-09-11', '00:36:00', 'Murder a family member', 'pending', '2026-09-01 03:36:55'),
(6, 2, 11, '2026-09-23', '21:43:00', 'MKLJ', 'pending', '2026-09-01 03:43:48'),
(7, 2, 2, '2026-09-24', '01:44:00', 'JJJJ', 'accepted', '2026-09-01 03:45:03'),
(8, 2, 2, '2026-09-16', '23:53:00', 'GGGGG', 'accepted', '2026-09-01 14:55:40');

-- --------------------------------------------------------

--
-- Table structure for table `cases`
--

CREATE TABLE `cases` (
  `case_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `lawyer_id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `case_title` varchar(150) NOT NULL,
  `case_type` varchar(100) NOT NULL,
  `case_description` text NOT NULL,
  `status` varchar(30) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cases`
--

INSERT INTO `cases` (`case_id`, `client_id`, `lawyer_id`, `appointment_id`, `case_title`, `case_type`, `case_description`, `status`, `created_at`) VALUES
(1, 2, 1, 1, 'Property Dispute', 'Property Law', 'Dispute regarding land ownership', 'Open', '2026-08-26 07:40:52');

-- --------------------------------------------------------

--
-- Table structure for table `client_profiles`
--

CREATE TABLE `client_profiles` (
  `client_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lawyer_profiles`
--

CREATE TABLE `lawyer_profiles` (
  `lawyer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `specialization_id` int(11) NOT NULL,
  `experience` int(11) NOT NULL,
  `chamber_name` varchar(150) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `consultation_fee` int(100) NOT NULL,
  `verification_status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lawyer_profiles`
--

INSERT INTO `lawyer_profiles` (`lawyer_id`, `user_id`, `specialization_id`, `experience`, `chamber_name`, `address`, `bio`, `consultation_fee`, `verification_status`) VALUES
(1, 3, 1, 5, 'ABC law chamber', 'Dhaka', 'Experienced Criminal lawyer', 5000, 'verified'),
(2, 5, 1, 3, 'Lawfirm', 'Dhaka', 'Just a lawyer', 1003, 'verified'),
(3, 6, 1, 3, 'People\'s Legal chamber', 'Dhaka', 'Just a lawyer', 5000, 'verified'),
(4, 7, 1, 3, 'Justice Point Law Firm', 'Dhaka', 'Experienced Criminal lawyer', 3000, 'verified'),
(5, 8, 5, 4, 'Dhaka Law House', 'Dhaka', 'Passionate about Cyber law', 500, 'verified'),
(6, 9, 5, 5, 'Trust Legal Consultants', 'Dhaka', 'Experienced Cyber lawyer', 3000, 'verified'),
(7, 10, 2, 4, 'Supreme Legal Consultants', 'Dhaka', 'Just a lawyer', 1500, 'verified'),
(8, 11, 1, 20, 'Dhaka Law House', 'Dhaka', 'Passionate about Criminal law', 4000, 'verified'),
(9, 12, 5, 15, 'Fair Trial chamber', 'Dhaka', 'Dedicated Cyber law practitioner', 2000, 'verified'),
(10, 13, 5, 10, 'City Legal Aid chamber', 'Dhaka', 'Well-known Cyber lawyer in Dhaka', 4000, 'pending'),
(11, 14, 1, 4, 'City Legal Aid chamber', 'Dhaka', 'Specializes in Criminal law, 4 years of experience', 3000, 'verified'),
(12, 15, 1, 22, 'Lawfirm', 'Dhaka', 'Specializes in Criminal law, 22 years of experience', 5000, 'verified'),
(13, 16, 4, 19, 'Rights First Law Firm', 'Dhaka', 'Experienced Property lawyer', 4000, 'verified'),
(14, 17, 1, 2, 'Bar & Bench Associates', 'Dhaka', 'Well-known Criminal lawyer in Dhaka', 2000, 'verified'),
(15, 18, 4, 22, 'Fair Trial chamber', 'Dhaka', 'Specializes in Property law, 22 years of experience', 500, 'pending'),
(16, 19, 4, 2, 'Dhaka Law House', 'Dhaka', 'Dedicated Property law practitioner', 2000, 'verified'),
(17, 20, 1, 6, 'National Law Associates', 'Dhaka', 'Dedicated Criminal law practitioner', 3000, 'verified'),
(18, 21, 4, 12, 'Themis Law chamber', 'Dhaka', 'Dedicated Property law practitioner', 3000, 'pending'),
(19, 22, 2, 22, 'Dhaka Law House', 'Dhaka', 'Just a lawyer', 500, 'verified'),
(20, 23, 1, 5, 'Justice Point Law Firm', 'Dhaka', 'Just a lawyer', 5000, 'verified'),
(21, 24, 5, 20, 'Themis Law chamber', 'Dhaka', 'Well-known Cyber lawyer in Dhaka', 500, 'verified'),
(22, 25, 4, 13, 'Lawfirm', 'Dhaka', 'Experienced Property lawyer', 4000, 'verified'),
(23, 26, 4, 6, 'Lawfirm', 'Dhaka', 'Experienced Property lawyer', 2500, 'verified'),
(24, 27, 5, 4, 'Fair Trial chamber', 'Dhaka', 'Dedicated Cyber law practitioner', 500, 'verified'),
(25, 28, 3, 12, 'Supreme Legal Consultants', 'Dhaka', 'Experienced Corporate lawyer', 2500, 'verified'),
(26, 29, 4, 10, 'Lawfirm', 'Dhaka', 'Specializes in Property law, 10 years of experience', 1000, 'verified'),
(27, 30, 2, 17, 'ABC law chamber', 'Dhaka', 'Just a lawyer', 1500, 'pending'),
(28, 31, 5, 1, 'Rights First Law Firm', 'Dhaka', 'Well-known Cyber lawyer in Dhaka', 5000, 'verified'),
(29, 32, 3, 6, 'Fair Trial chamber', 'Dhaka', 'Just a lawyer', 1500, 'verified'),
(30, 33, 2, 8, 'People\'s Legal chamber', 'Dhaka', 'Dedicated Family law practitioner', 3000, 'verified'),
(31, 34, 3, 24, 'ABC law chamber', 'Dhaka', 'Passionate about Corporate law', 500, 'verified'),
(32, 35, 5, 12, 'National Law Associates', 'Dhaka', 'Specializes in Cyber law, 12 years of experience', 2500, 'pending'),
(33, 36, 2, 16, 'Dhaka Law House', 'Dhaka', 'Just a lawyer', 2500, 'verified'),
(34, 37, 3, 21, 'Lawfirm', 'Dhaka', 'Well-known Corporate lawyer in Dhaka', 800, 'pending'),
(35, 38, 2, 14, 'Rights First Law Firm', 'Dhaka', 'Well-known Family lawyer in Dhaka', 2500, 'verified'),
(36, 39, 1, 24, 'Bright Justice chamber', 'Dhaka', 'Experienced Criminal lawyer', 1000, 'pending'),
(37, 40, 2, 20, 'People\'s Legal chamber', 'Dhaka', 'Specializes in Family law, 20 years of experience', 4000, 'verified'),
(38, 41, 2, 1, 'ABC law chamber', 'Dhaka', 'Dedicated Family law practitioner', 800, 'verified'),
(39, 42, 1, 9, 'Dhaka Law House', 'Dhaka', 'Just a lawyer', 2000, 'verified'),
(40, 43, 4, 5, 'ABC law chamber', 'Dhaka', 'Well-known Property lawyer in Dhaka', 2500, 'pending'),
(41, 44, 5, 5, 'City Legal Aid chamber', 'Dhaka', 'Experienced Cyber lawyer', 1000, 'verified'),
(42, 45, 1, 25, 'Rights First Law Firm', 'Dhaka', 'Passionate about Criminal law', 1000, 'verified'),
(43, 46, 1, 11, 'Themis Law chamber', 'Dhaka', 'Passionate about Criminal law', 5000, 'verified'),
(44, 47, 1, 8, 'Dhaka Law House', 'Dhaka', 'Experienced Criminal lawyer', 2000, 'verified'),
(45, 48, 1, 25, 'Metro Law Associates', 'Dhaka', 'Just a lawyer', 800, 'verified'),
(46, 49, 2, 23, 'Trust Legal Consultants', 'Dhaka', 'Passionate about Family law', 4000, 'verified'),
(47, 50, 5, 9, 'Metro Law Associates', 'Dhaka', 'Dedicated Cyber law practitioner', 5000, 'pending'),
(48, 51, 1, 13, 'National Law Associates', 'Dhaka', 'Dedicated Criminal law practitioner', 2500, 'verified'),
(49, 52, 3, 4, 'Metro Law Associates', 'Dhaka', 'Well-known Corporate lawyer in Dhaka', 1000, 'pending'),
(50, 53, 3, 5, 'National Law Associates', 'Dhaka', 'Experienced Corporate lawyer', 1500, 'verified'),
(51, 54, 2, 6, 'Bar & Bench Associates', 'Dhaka', 'Passionate about Family law', 3000, 'verified'),
(52, 55, 3, 11, 'Lawfirm', 'Dhaka', 'Just a lawyer', 2500, 'verified');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `reported_by` int(11) NOT NULL,
  `reported_user` int(11) NOT NULL,
  `appointment_id` int(11) DEFAULT NULL,
  `reason` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`report_id`, `reported_by`, `reported_user`, `appointment_id`, `reason`, `description`, `status`, `created_at`) VALUES
(1, 2, 14, 4, 'Fraud', 'Rude', 'pending', '2026-08-31 16:50:00'),
(2, 5, 2, 2, 'Harassment', 'HARRASED ME', 'pending', '2026-09-01 03:42:30');

-- --------------------------------------------------------

--
-- Table structure for table `specializations`
--

CREATE TABLE `specializations` (
  `specialization_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `specializations`
--

INSERT INTO `specializations` (`specialization_id`, `name`, `description`) VALUES
(1, 'Criminal Law', 'Criminal related cases'),
(2, 'Family Law', 'Family and divorce cases'),
(3, 'Corporate Law', 'Business and corporate cases'),
(4, 'Property Law', 'Property related cases'),
(5, 'Cyber Law', 'Cyber crime related cases');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `phone`, `role`, `created_at`, `status`) VALUES
(1, 'Sadia', '12saa@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '00009886677', 'client', '2026-08-23 14:34:33', 'active'),
(2, 'Sadia', 'sadia.aniqua@gmail.com', '$2y$10$qzyqjT.KsK4swjP40N0zHeewNvFv6CTYBmX4KTyFxgl.y/7KXyPSe', '123', 'client', '2026-08-30 16:16:47', 'active'),
(3, 'GA', 'ga@gmail.com', '$2y$10$zWj7jyMZ5blM9KsM7T2fxu5TK6VX/NoPpQEPqFHbyS5wmCsc6Vpse', '1234', 'lawyer', '2026-08-26 05:58:27', 'active'),
(4, 'Admin', 'admin@legalaid.com', '$2y$10$ajb84jVXXRyodujWHJqkLeaBerxs.Sjb8FVSWYL3Z5OZNAVz.VkE6', '01892929292', 'admin', '2026-08-27 16:19:18', 'active'),
(5, 'venti', 'venti@gmail.com', '$2y$10$VuMuET9iV2x8NUCeyd/fp.m8bK7v7LEvffLKvzbwx2OAx3Br1PUvq', '019202919100', 'lawyer', '2026-08-28 07:47:35', 'active'),
(6, 'Asif Hossain', 'asif.hossain6@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01811969249', 'lawyer', '2026-08-28 15:28:35', 'active'),
(7, 'Farhana Alam', 'farhana.alam7@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01715265209', 'lawyer', '2026-08-28 15:28:35', 'active'),
(8, 'Rina Uddin', 'rina.uddin8@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01631734710', 'lawyer', '2026-08-28 15:28:35', 'active'),
(9, 'Farhana Rahman', 'farhana.rahman9@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01625008886', 'lawyer', '2026-08-28 15:28:35', 'active'),
(10, 'Mizanur Chowdhury', 'mizanur.chowdhury10@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01900785835', 'lawyer', '2026-08-28 15:28:35', 'active'),
(11, 'Ferdousi Islam', 'ferdousi.islam11@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01902992920', 'lawyer', '2026-08-28 15:28:35', 'active'),
(12, 'Momtaz Khan', 'momtaz.khan12@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01849968098', 'lawyer', '2026-08-28 15:28:35', 'active'),
(13, 'Nurul Rahman', 'nurul.rahman13@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01643945575', 'lawyer', '2026-08-28 15:28:35', 'active'),
(14, 'Anwar Chowdhury', 'anwar.chowdhury14@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01926932383', 'lawyer', '2026-08-28 15:28:35', 'active'),
(15, 'Rumana Akter', 'rumana.akter15@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01826397581', 'lawyer', '2026-08-28 15:28:35', 'active'),
(16, 'Nadia Khan', 'nadia.khan16@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01919099897', 'lawyer', '2026-08-28 15:28:35', 'active'),
(17, 'Sultana Akter', 'sultana.akter17@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01974221608', 'lawyer', '2026-08-28 15:28:35', 'active'),
(18, 'Anwar Chowdhury', 'anwar.chowdhury18@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01984736618', 'lawyer', '2026-08-28 15:28:35', 'active'),
(19, 'Iqbal Sarkar', 'iqbal.sarkar19@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01662865327', 'lawyer', '2026-08-28 15:28:35', 'active'),
(20, 'Rowshan Uddin', 'rowshan.uddin20@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01866560007', 'lawyer', '2026-08-28 15:28:35', 'active'),
(21, 'Marium Alam', 'marium.alam21@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01749476169', 'lawyer', '2026-08-28 15:28:35', 'active'),
(22, 'Sadia Hossain', 'sadia.hossain22@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01681227703', 'lawyer', '2026-08-28 15:28:35', 'active'),
(23, 'Shirin Chowdhury', 'shirin.chowdhury23@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01751360407', 'lawyer', '2026-08-28 15:28:35', 'active'),
(24, 'Asif Hossain', 'asif.hossain24@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01970705957', 'lawyer', '2026-08-28 15:28:35', 'active'),
(25, 'Rina Uddin', 'rina.uddin25@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01813712004', 'lawyer', '2026-08-28 15:28:35', 'active'),
(26, 'Jashim Islam', 'jashim.islam26@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01712078881', 'lawyer', '2026-08-28 15:28:35', 'active'),
(27, 'Rahim Sarkar', 'rahim.sarkar27@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01681209743', 'lawyer', '2026-08-28 15:28:35', 'active'),
(28, 'Parvin Uddin', 'parvin.uddin28@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01679752435', 'lawyer', '2026-08-28 15:28:35', 'active'),
(29, 'Nazma Akter', 'nazma.akter29@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01857910157', 'lawyer', '2026-08-28 15:28:35', 'active'),
(30, 'Shahida Chowdhury', 'shahida.chowdhury30@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01856958196', 'lawyer', '2026-08-28 15:28:35', 'active'),
(31, 'Salma Hossain', 'salma.hossain31@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01970477212', 'lawyer', '2026-08-28 15:28:35', 'active'),
(32, 'Sadia Chowdhury', 'sadia.chowdhury32@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01878312195', 'lawyer', '2026-08-28 15:28:35', 'active'),
(33, 'Nadia Rahman', 'nadia.rahman33@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01929224394', 'lawyer', '2026-08-28 15:28:35', 'active'),
(34, 'Jashim Alam', 'jashim.alam34@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01864560237', 'lawyer', '2026-08-28 15:28:35', 'active'),
(35, 'Habib Rahman', 'habib.rahman35@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01971794884', 'lawyer', '2026-08-28 15:28:35', 'active'),
(36, 'Sadia Rahman', 'sadia.rahman36@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01654845201', 'lawyer', '2026-08-28 15:28:35', 'active'),
(37, 'Parvin Ahmed', 'parvin.ahmed37@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01857415335', 'lawyer', '2026-08-28 15:28:35', 'active'),
(38, 'Rashed Rahman', 'rashed.rahman38@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01856641874', 'lawyer', '2026-08-28 15:28:35', 'active'),
(39, 'Rowshan Akter', 'rowshan.akter39@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01815492905', 'lawyer', '2026-08-28 15:28:35', 'active'),
(40, 'Rumana Sarkar', 'rumana.sarkar40@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01849834963', 'lawyer', '2026-08-28 15:28:35', 'active'),
(41, 'Rumana Alam', 'rumana.alam41@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01894358571', 'lawyer', '2026-08-28 15:28:35', 'active'),
(42, 'Marium Rahman', 'marium.rahman42@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01713302495', 'lawyer', '2026-08-28 15:28:35', 'active'),
(43, 'Asif Chowdhury', 'asif.chowdhury43@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01892247165', 'lawyer', '2026-08-28 15:28:35', 'active'),
(44, 'Hasina Alam', 'hasina.alam44@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01825823083', 'lawyer', '2026-08-28 15:28:35', 'active'),
(45, 'Anwar Hossain', 'anwar.hossain45@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01926715286', 'lawyer', '2026-08-28 15:28:35', 'active'),
(46, 'Parvin Islam', 'parvin.islam46@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01898755579', 'lawyer', '2026-08-28 15:28:35', 'active'),
(47, 'Momtaz Islam', 'momtaz.islam47@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01900806699', 'lawyer', '2026-08-28 15:28:35', 'active'),
(48, 'Golam Akter', 'golam.akter48@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01901576168', 'lawyer', '2026-08-28 15:28:35', 'active'),
(49, 'Golam Sarkar', 'golam.sarkar49@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01874964599', 'lawyer', '2026-08-28 15:28:35', 'active'),
(50, 'Golam Rahman', 'golam.rahman50@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01975389743', 'lawyer', '2026-08-28 15:28:35', 'active'),
(51, 'Anwar Hossain', 'anwar.hossain51@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01823680316', 'lawyer', '2026-08-28 15:28:35', 'active'),
(52, 'Marium Islam', 'marium.islam52@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01714186965', 'lawyer', '2026-08-28 15:28:35', 'active'),
(53, 'Manzur Khan', 'manzur.khan53@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01676761264', 'lawyer', '2026-08-28 15:28:35', 'active'),
(54, 'Rowshan Akter', 'rowshan.akter54@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01687399988', 'lawyer', '2026-08-28 15:28:35', 'active'),
(55, 'Nadia Uddin', 'nadia.uddin55@gmail.com', '$2y$10$98VzcY6Ya9..yq5Tt1k3RuOJroYeHWOZjhEJVfIR592kvndcq4T/e', '01705089618', 'lawyer', '2026-08-28 15:28:35', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`);

--
-- Indexes for table `cases`
--
ALTER TABLE `cases`
  ADD PRIMARY KEY (`case_id`);

--
-- Indexes for table `client_profiles`
--
ALTER TABLE `client_profiles`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `lawyer_profiles`
--
ALTER TABLE `lawyer_profiles`
  ADD PRIMARY KEY (`lawyer_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`);

--
-- Indexes for table `specializations`
--
ALTER TABLE `specializations`
  ADD PRIMARY KEY (`specialization_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `cases`
--
ALTER TABLE `cases`
  MODIFY `case_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `client_profiles`
--
ALTER TABLE `client_profiles`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lawyer_profiles`
--
ALTER TABLE `lawyer_profiles`
  MODIFY `lawyer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `specializations`
--
ALTER TABLE `specializations`
  MODIFY `specialization_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
