-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 08, 2017 at 01:34 AM
-- Server version: 10.1.8-MariaDB
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ppub`
--

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

CREATE TABLE `article` (
  `article_id` int(11) NOT NULL,
  `article_title` varchar(256) DEFAULT NULL,
  `creator_id` int(11) DEFAULT NULL,
  `abstract` text,
  `header_id` int(3) DEFAULT NULL,
  `article_pdf` varchar(45) DEFAULT NULL,
  `article_text` longtext,
  `type_code` char(2) DEFAULT NULL,
  `fic_ind` char(1) DEFAULT NULL,
  `issue_id` int(11) DEFAULT NULL,
  `order_in_issue` int(11) DEFAULT NULL,
  `length` int(3) DEFAULT NULL,
  `arange` varchar(40) DEFAULT NULL,
  `rights` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `article_subject`
--

CREATE TABLE `article_subject` (
  `article_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `contact_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`contact_id`) VALUES
(1);

-- --------------------------------------------------------

--
-- Table structure for table `creator`
--

CREATE TABLE `creator` (
  `creator_id` int(11) NOT NULL,
  `first_nm` varchar(60) DEFAULT NULL,
  `middle` varchar(60) DEFAULT NULL,
  `last_nm` varchar(60) DEFAULT NULL,
  `fac_ind` char(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `creator`
--

INSERT INTO `creator` (`creator_id`, `first_nm`, `middle`, `last_nm`, `fac_ind`) VALUES
(1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `header`
--

CREATE TABLE `header` (
  `header_id` int(3) NOT NULL,
  `header_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `header`
--

INSERT INTO `header` (`header_id`, `header_name`) VALUES
(1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE `image` (
  `image_id` int(11) NOT NULL,
  `image_title` varchar(45) DEFAULT NULL,
  `img_type_code` char(2) DEFAULT NULL,
  `cover_ind` char(1) DEFAULT NULL,
  `caption` varchar(100) DEFAULT NULL,
  `creator_id` int(11) DEFAULT NULL,
  `file` varchar(45) DEFAULT NULL,
  `article_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `image_subject`
--

CREATE TABLE `image_subject` (
  `image_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `img_type`
--

CREATE TABLE `img_type` (
  `img_type_code` char(2) NOT NULL,
  `img_type_name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `issue`
--

CREATE TABLE `issue` (
  `issue_id` int(11) NOT NULL,
  `issue_ed` varchar(45) DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  `volume` int(11) DEFAULT NULL,
  `issue_length` int(3) DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `cover_id` int(11) DEFAULT NULL,
  `issue_cover` varchar(45) DEFAULT NULL,
  `pub_ind` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pub`
--

CREATE TABLE `pub` (
  `title_id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `ystart` year(4) NOT NULL,
  `yend` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pub`
--

INSERT INTO `pub` (`title_id`, `title`, `ystart`, `yend`) VALUES
(1, 'Sample Journal Title', 1901, 2155);

-- --------------------------------------------------------

--
-- Table structure for table `site_data`
--

CREATE TABLE `site_data` (
  `maintext` text,
  `resetl` varchar(256) NOT NULL,
  `ldom_ind` tinyint(1) NOT NULL DEFAULT '0',
  `ldom` varchar(256) NOT NULL,
  `logo` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `site_data`
--

INSERT INTO `site_data` (`maintext`, `resetl`, `ldom_ind`, `ldom`, `logo`) VALUES
('This is a description of your periodical.<br/> <em>Note that you can use some HTML in your layout</em>\r\n<p/>\r\nAs is further demonstrated here.  ', 'yoursite.org/samplejournal', 0, 'gmail.com', 'operi.png');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subject_id` int(11) NOT NULL,
  `subject_name` varchar(45) DEFAULT NULL,
  `type_code` varchar(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `type`
--

CREATE TABLE `type` (
  `type_code` char(2) NOT NULL,
  `type_name` varchar(45) DEFAULT NULL,
  `display` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `type`
--

INSERT INTO `type` (`type_code`, `type_name`, `display`) VALUES
('BC', 'Back Cover', 0),
('CF', 'Call for Papers', 0),
('CN', 'Contents', 0),
('CO', 'Cover', 0),
('CS', 'Contributor Notes', 1),
('CW', 'Creative Writing', 1),
('ES', 'Essay', 1),
('EW', 'Endword', 1),
('FP', 'Facing Page', 0),
('FW', 'Foreword', 1),
('IN', 'Introduction', 1),
('MA', 'Masthead', 1),
('PO', 'Poetry', 1),
('SC', 'Section Cover', 0),
('SS', 'Short Story', 1),
('TE', 'Thesis Excerpt', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(30) NOT NULL,
  `email` varchar(256) DEFAULT NULL,
  `password` char(40) NOT NULL,
  `first_name` varchar(30) DEFAULT NULL,
  `last_name` varchar(30) DEFAULT NULL,
  `admin` int(1) NOT NULL,
  `token` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_name`, `email`, `password`, `first_name`, `last_name`, `admin`, `token`) VALUES
(1, 'admin', NULL, 'd033e22ae348aeb5660fc2140aec35850c4da997', 'administrator', 'account', 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`article_id`),
  ADD KEY `issue_id_idx` (`issue_id`),
  ADD KEY `header_code_idx` (`header_id`),
  ADD KEY `header_id` (`header_id`),
  ADD KEY `creator_id` (`creator_id`),
  ADD KEY `type_code` (`type_code`);
ALTER TABLE `article` ADD FULLTEXT KEY `article_title` (`article_title`);

--
-- Indexes for table `article_subject`
--
ALTER TABLE `article_subject`
  ADD PRIMARY KEY (`article_id`,`subject_id`),
  ADD KEY `assubject_id` (`subject_id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`contact_id`);

--
-- Indexes for table `creator`
--
ALTER TABLE `creator`
  ADD PRIMARY KEY (`creator_id`);
ALTER TABLE `creator` ADD FULLTEXT KEY `first_nm` (`first_nm`,`middle`,`last_nm`);

--
-- Indexes for table `header`
--
ALTER TABLE `header`
  ADD PRIMARY KEY (`header_id`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `article_id_idx` (`article_id`),
  ADD KEY `creator_id_idx` (`creator_id`),
  ADD KEY `type_code_idx` (`img_type_code`);

--
-- Indexes for table `image_subject`
--
ALTER TABLE `image_subject`
  ADD PRIMARY KEY (`image_id`,`subject_id`),
  ADD KEY `subject_id_idx` (`subject_id`);

--
-- Indexes for table `img_type`
--
ALTER TABLE `img_type`
  ADD PRIMARY KEY (`img_type_code`);

--
-- Indexes for table `issue`
--
ALTER TABLE `issue`
  ADD PRIMARY KEY (`issue_id`),
  ADD KEY `cover_id_idx` (`cover_id`);

--
-- Indexes for table `pub`
--
ALTER TABLE `pub`
  ADD PRIMARY KEY (`title_id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subject_id`);
ALTER TABLE `subject` ADD FULLTEXT KEY `subject_name` (`subject_name`);

--
-- Indexes for table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`type_code`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `token` (`token`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `article`
--
ALTER TABLE `article`
  MODIFY `article_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `creator`
--
ALTER TABLE `creator`
  MODIFY `creator_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `header`
--
ALTER TABLE `header`
  MODIFY `header_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `issue`
--
ALTER TABLE `issue`
  MODIFY `issue_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pub`
--
ALTER TABLE `pub`
  MODIFY `title_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `contact` FOREIGN KEY (`contact_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
