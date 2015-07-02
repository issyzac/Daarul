-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 11, 2014 at 10:45 PM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `pmngdb`
--
CREATE DATABASE IF NOT EXISTS `pmngdb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `pmngdb`;

-- --------------------------------------------------------

--
-- Table structure for table `allocation`
--

CREATE TABLE IF NOT EXISTS `allocation` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `allocation`
--

INSERT INTO `allocation` (`id`, `name`, `enabled`) VALUES
(1, 'kudeki', 1),
(2, 'kufagia', 1),
(3, 'ironing', 1);

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE IF NOT EXISTS `attendance` (
  `date` date NOT NULL,
  `employeeid` varchar(20) NOT NULL,
  `status` varchar(10) NOT NULL,
  `shiftid` int(10) NOT NULL,
  `allocationid` int(10) NOT NULL,
  PRIMARY KEY (`date`,`employeeid`),
  KEY `employeeid` (`employeeid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`date`, `employeeid`, `status`, `shiftid`, `allocationid`) VALUES
('2014-02-27', '278h7r872', '2', 2, 0),
('2014-02-27', '4', '2', 2, 0),
('2014-02-27', 'T00Z', '3', 2, 0),
('2014-02-28', '1', '4', 2, 0),
('2014-02-28', '103', '5', 2, 0),
('2014-02-28', '278h7r872', '4', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `attendance_status`
--

CREATE TABLE IF NOT EXISTS `attendance_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `attendance_status`
--

INSERT INTO `attendance_status` (`id`, `name`) VALUES
(4, 'sick'),
(5, 'leave');

-- --------------------------------------------------------

--
-- Table structure for table `blend`
--

CREATE TABLE IF NOT EXISTS `blend` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `status` varchar(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `blend`
--

INSERT INTO `blend` (`id`, `name`, `status`) VALUES
(3, 'Blend A', '1'),
(4, 'Blend B', '1'),
(7, 'Blend  c', '1'),
(8, 'Blend D', '1');

-- --------------------------------------------------------

--
-- Table structure for table `blendformula`
--

CREATE TABLE IF NOT EXISTS `blendformula` (
  `productid` int(15) NOT NULL,
  `materialid` int(10) NOT NULL,
  `percentage` double(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`productid`,`materialid`),
  KEY `materialid` (`materialid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `branch_sales`
--

CREATE TABLE IF NOT EXISTS `branch_sales` (
  `salesid` int(10) NOT NULL AUTO_INCREMENT,
  `receipt` varchar(50) NOT NULL,
  `productid` int(15) NOT NULL,
  `branchid` int(11) NOT NULL,
  `quantity` double NOT NULL,
  `date` date NOT NULL,
  `type` varchar(5) NOT NULL,
  PRIMARY KEY (`salesid`),
  KEY `productid` (`productid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `branch_stock`
--

CREATE TABLE IF NOT EXISTS `branch_stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transitid` int(11) NOT NULL,
  `branchid` int(10) NOT NULL,
  `productid` int(15) NOT NULL,
  `quantity` double NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `productid` (`productid`),
  KEY `transitid` (`transitid`,`branchid`,`productid`),
  KEY `transitid_2` (`transitid`,`branchid`,`productid`),
  KEY `branchid` (`branchid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `branch_stock`
--

INSERT INTO `branch_stock` (`id`, `transitid`, `branchid`, `productid`, `quantity`, `date`) VALUES
(1, 5, 4, 31, 10, '2014-02-20'),
(2, 5, 4, 35, 20, '2014-02-20');

-- --------------------------------------------------------

--
-- Table structure for table `branch_stock_tracking`
--

CREATE TABLE IF NOT EXISTS `branch_stock_tracking` (
  `storeid` int(10) NOT NULL,
  `productid` int(15) NOT NULL,
  `openingstock` int(10) NOT NULL DEFAULT '0',
  `closingstock` int(10) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`storeid`,`productid`,`date`),
  KEY `productid` (`productid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `branch_stock_tracking`
--

INSERT INTO `branch_stock_tracking` (`storeid`, `productid`, `openingstock`, `closingstock`, `date`) VALUES
(4, 31, 0, 10, '2014-02-27'),
(4, 35, 0, 20, '2014-02-27'),
(5, 31, 0, 10, '2014-02-27'),
(5, 35, 0, 20, '2014-02-27'),
(5, 37, 0, 0, '2014-03-03'),
(5, 37, 0, 0, '2014-03-06'),
(5, 37, 0, 0, '2014-03-07'),
(5, 37, 0, 0, '2014-03-10'),
(5, 37, 0, 0, '2014-03-11');

-- --------------------------------------------------------

--
-- Table structure for table `dailyinput`
--

CREATE TABLE IF NOT EXISTS `dailyinput` (
  `productid` int(15) NOT NULL,
  `materialid` int(10) NOT NULL,
  `date` date NOT NULL,
  `quantity` decimal(10,0) NOT NULL,
  PRIMARY KEY (`productid`,`materialid`,`date`),
  KEY `materialid` (`materialid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dailyinput`
--

INSERT INTO `dailyinput` (`productid`, `materialid`, `date`, `quantity`) VALUES
(33, 5, '2014-03-03', '25'),
(33, 6, '2014-03-03', '25'),
(33, 7, '2014-03-03', '25'),
(33, 8, '2014-03-03', '25'),
(33, 8, '2014-03-11', '106'),
(33, 9, '2014-03-03', '25'),
(33, 11, '2014-03-03', '25'),
(34, 5, '2014-03-03', '25'),
(34, 6, '2014-03-03', '25'),
(34, 7, '2014-03-03', '25'),
(34, 8, '2014-03-03', '25'),
(34, 9, '2014-03-03', '25'),
(34, 11, '2014-03-03', '25'),
(35, 5, '2014-03-03', '25'),
(35, 6, '2014-03-03', '25'),
(35, 7, '2014-03-03', '25'),
(35, 8, '2014-03-03', '25'),
(35, 9, '2014-03-03', '25'),
(35, 11, '2014-03-03', '25'),
(36, 5, '2014-03-03', '25'),
(36, 6, '2014-03-03', '25'),
(36, 7, '2014-03-03', '25'),
(36, 8, '2014-03-03', '25'),
(36, 9, '2014-03-03', '25'),
(36, 11, '2014-03-03', '25');

-- --------------------------------------------------------

--
-- Table structure for table `dailyoutput`
--

CREATE TABLE IF NOT EXISTS `dailyoutput` (
  `productid` int(15) NOT NULL,
  `date` date NOT NULL,
  `quantity` int(10) NOT NULL,
  PRIMARY KEY (`productid`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE IF NOT EXISTS `employee` (
  `id` varchar(20) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `gender` char(1) NOT NULL,
  `dob` date NOT NULL,
  `doe` date NOT NULL,
  `toe` varchar(20) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `firstname`, `middlename`, `surname`, `gender`, `dob`, `doe`, `toe`, `status`) VALUES
('1', 'Azizi', 'S', 'Likumbo', 'M', '1990-04-04', '2013-04-08', 'Casual Grade', 1),
('103', 'admin', 'admin', 'admin', 'M', '2013-07-08', '2013-07-08', 'Casual Grade', 1),
('278h7r872', 'Great', 'man', 'Ever', 'M', '2014-02-01', '2014-02-24', 'MG', 1),
('4', 'Mathew', 'S', 'Erenest', 'M', '2013-04-01', '2013-04-08', 'MG', 1),
('534677gdh74', 'greatestest', 'woman', 'ofsometimes', 'F', '2014-02-07', '2014-02-23', 'Casual Grade', 1),
('T00Z', 'Charles', 'Newe', 'Seleman', 'M', '2014-02-06', '2014-02-07', 'Casual Grade', 1);

-- --------------------------------------------------------

--
-- Table structure for table `employee_shift`
--

CREATE TABLE IF NOT EXISTS `employee_shift` (
  `shift_id` int(3) NOT NULL,
  `total_emp` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`shift_id`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE IF NOT EXISTS `event` (
  `employee_id` varchar(20) NOT NULL,
  `event` text NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `item_id` varchar(50) NOT NULL,
  PRIMARY KEY (`employee_id`,`date`,`time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`employee_id`, `event`, `date`, `time`, `table_name`, `item_id`) VALUES
('103', 'Logged into the System', '2014-03-10', '20:08:17', 'System', ''),
('103', 'Uploaded Excel file containing materials order Data', '2014-03-10', '20:33:16', 'sales', ''),
('103', 'Entered a new material purchase entry in the database', '2014-03-10', '20:47:27', 'material_purchase', '5'),
('103', 'Entered a new material purchase entry in the database', '2014-03-10', '20:48:23', 'material_purchase', '5'),
('103', 'Entered a new material purchase entry in the database', '2014-03-10', '20:49:14', 'material_purchase', '5'),
('103', 'Entered a new material purchase entry in the database', '2014-03-10', '20:55:11', 'material_purchase', '5'),
('103', 'Entered a new material purchase entry in the database', '2014-03-10', '20:59:55', 'material_purchase', '7'),
('103', 'Entered a new material purchase entry in the database', '2014-03-10', '21:00:10', 'material_purchase', '7'),
('103', 'Entered a new material purchase entry in the database', '2014-03-10', '21:29:55', 'material_purchase', '8'),
('103', 'Deleted material purchase entry in the database', '2014-03-10', '22:03:52', 'material_purchase', ''),
('103', 'Deleted material purchase entry in the database', '2014-03-10', '22:04:40', 'material_purchase', ''),
('103', 'Deleted material purchase entry in the database', '2014-03-10', '22:05:16', 'material_purchase', ''),
('103', 'Entered a new material purchase entry in the database', '2014-03-10', '22:09:46', 'material_purchase', '5'),
('103', 'Logged out of the System', '2014-03-10', '22:25:48', 'System', ''),
('103', 'Entered a new material purchase entry in the database', '2014-03-11', '19:22:54', 'material_purchase', '5'),
('103', 'Entered a new material purchase entry in the database', '2014-03-11', '20:31:29', 'material_purchase', '8'),
('103', 'Entered a new material purchase entry in the database', '2014-03-11', '20:32:02', 'material_purchase', '8'),
('103', 'Logged out of the System', '2014-03-11', '21:11:19', 'System', ''),
('103', 'Logged into the System', '2014-03-11', '21:11:20', 'System', ''),
('103', 'Logged out of the System', '2014-03-11', '21:11:42', 'System', ''),
('103', 'Logged into the System', '2014-03-11', '21:11:44', 'System', ''),
('103', 'Logged out of the System', '2014-03-11', '21:38:23', 'System', ''),
('103', 'Logged into the System', '2014-03-11', '21:38:25', 'System', ''),
('103', 'Logged out of the System', '2014-03-11', '21:39:10', 'System', ''),
('103', 'Logged into the System', '2014-03-11', '22:31:38', 'System', ''),
('103', 'Logged out of the System', '2014-03-11', '22:44:05', 'System', '');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE IF NOT EXISTS `invoice` (
  `invoiceid` varchar(50) NOT NULL,
  `storeid` int(10) NOT NULL,
  `productid` int(15) NOT NULL,
  `date` date NOT NULL,
  `quantity` float(15,2) NOT NULL,
  PRIMARY KEY (`invoiceid`,`storeid`,`productid`,`date`),
  KEY `productid` (`productid`),
  KEY `storeid` (`storeid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `manpower`
--

CREATE TABLE IF NOT EXISTS `manpower` (
  `job_id` int(11) NOT NULL,
  `manpower` int(11) NOT NULL,
  `shift_id` int(3) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`job_id`,`shift_id`,`date`),
  KEY `shift_id` (`shift_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `manpower`
--

INSERT INTO `manpower` (`job_id`, `manpower`, `shift_id`, `date`) VALUES
(1, 5, 2, '2014-03-03'),
(2, 9, 2, '2014-03-03'),
(3, 10, 2, '2014-03-03');

-- --------------------------------------------------------

--
-- Table structure for table `material`
--

CREATE TABLE IF NOT EXISTS `material` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` varchar(30) NOT NULL,
  `criticallevel` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `material`
--

INSERT INTO `material` (`id`, `name`, `type`, `criticallevel`, `status`) VALUES
(5, 'Cardbox', 'Packing Material', 200, 1),
(6, 'PF1', 'Blending Material', 200, 1),
(7, 'Dust1', 'Blending Material', 200, 1),
(8, 'BMF', 'Blending Material', 200, 1),
(9, 'Satchet', 'Packing Material', 2000, 1),
(10, 'www', 'Packing Material', 67, 0),
(11, 'hh', 'Packing Material', 88, 1),
(12, 'Dust2', 'Blending Material', 50, 1);

-- --------------------------------------------------------

--
-- Table structure for table `material_purchase`
--

CREATE TABLE IF NOT EXISTS `material_purchase` (
  `orderid` bigint(20) NOT NULL,
  `refno` varchar(50) NOT NULL,
  `purchaseqty` int(20) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`orderid`,`refno`),
  KEY `orderid` (`orderid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `material_purchase`
--

INSERT INTO `material_purchase` (`orderid`, `refno`, `purchaseqty`, `date`) VALUES
(4, '99', 197, '2014-03-11'),
(7, '134', 2000, '2014-03-10'),
(7, '445576', 99, '2014-03-11'),
(10, '768', 170, '2014-03-11');

-- --------------------------------------------------------

--
-- Table structure for table `material_returned`
--

CREATE TABLE IF NOT EXISTS `material_returned` (
  `materialid` int(10) NOT NULL,
  `date` date NOT NULL,
  `quantity` int(10) NOT NULL,
  PRIMARY KEY (`materialid`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `material_returned`
--

INSERT INTO `material_returned` (`materialid`, `date`, `quantity`) VALUES
(6, '2014-02-25', 50),
(6, '2014-02-26', 777),
(8, '2014-03-03', 7);

-- --------------------------------------------------------

--
-- Table structure for table `material_stock`
--

CREATE TABLE IF NOT EXISTS `material_stock` (
  `materialid` int(10) NOT NULL,
  `openingstock` int(12) NOT NULL,
  `repack` int(10) NOT NULL,
  `closingstock` int(12) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`materialid`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `material_stock`
--

INSERT INTO `material_stock` (`materialid`, `openingstock`, `repack`, `closingstock`, `date`) VALUES
(5, 200, 0, 200, '2014-02-25'),
(5, 200, 0, 200, '2014-02-28'),
(5, 200, 0, 200, '2014-03-02'),
(5, 200, 0, 200, '2014-03-03'),
(5, 200, 0, 200, '2014-03-06'),
(5, 200, 0, 200, '2014-03-07'),
(5, 200, 0, 200, '2014-03-10'),
(5, 200, 0, 200, '2014-03-11'),
(6, 200, 0, 150, '2014-02-25'),
(6, 150, 0, 150, '2014-02-28'),
(6, 150, 0, 150, '2014-03-02'),
(6, 150, 0, 150, '2014-03-03'),
(6, 150, 0, 150, '2014-03-06'),
(6, 150, 0, 150, '2014-03-07'),
(6, 150, 0, 150, '2014-03-10'),
(6, 150, 0, 150, '2014-03-11'),
(7, 200, 0, 200, '2014-02-25'),
(7, 200, 0, 200, '2014-02-28'),
(7, 200, 0, 200, '2014-03-02'),
(7, 200, 0, 200, '2014-03-03'),
(7, 200, 0, 200, '2014-03-06'),
(7, 200, 0, 200, '2014-03-07'),
(7, 200, 0, 200, '2014-03-10'),
(7, 200, 0, 200, '2014-03-11'),
(8, 200, 0, 200, '2014-02-25'),
(8, 200, 0, 200, '2014-02-28'),
(8, 200, 0, 200, '2014-03-02'),
(8, 200, 0, 193, '2014-03-03'),
(8, 193, 0, 193, '2014-03-06'),
(8, 193, 0, 193, '2014-03-07'),
(8, 193, 0, 193, '2014-03-10'),
(8, 193, 0, 193, '2014-03-11'),
(9, 200, 0, 200, '2014-02-25'),
(9, 200, 0, 200, '2014-02-28'),
(9, 200, 0, 200, '2014-03-02'),
(9, 200, 0, 200, '2014-03-03'),
(9, 200, 0, 200, '2014-03-06'),
(9, 200, 0, 200, '2014-03-07'),
(9, 200, 0, 200, '2014-03-10'),
(9, 200, 0, 200, '2014-03-11'),
(11, 500, 0, 500, '2014-03-03'),
(11, 500, 0, 500, '2014-03-06'),
(11, 500, 0, 500, '2014-03-07'),
(11, 500, 0, 500, '2014-03-10'),
(11, 500, 0, 500, '2014-03-11');

-- --------------------------------------------------------

--
-- Table structure for table `offstock_product`
--

CREATE TABLE IF NOT EXISTS `offstock_product` (
  `storeid` int(10) NOT NULL,
  `productid` int(15) NOT NULL,
  `quantity` bigint(20) NOT NULL,
  `dispatch` varchar(30) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`storeid`,`productid`,`dispatch`,`date`),
  KEY `productid` (`productid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `on_transit`
--

CREATE TABLE IF NOT EXISTS `on_transit` (
  `branchid` int(10) NOT NULL,
  `transitid` int(10) NOT NULL,
  `productid` int(15) NOT NULL,
  `dispatch` varchar(50) NOT NULL,
  `quantity` double NOT NULL,
  `delivery` tinyint(1) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`branchid`,`transitid`,`productid`,`dispatch`,`date`),
  KEY `productid` (`productid`),
  KEY `transitid` (`transitid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `on_transit`
--

INSERT INTO `on_transit` (`branchid`, `transitid`, `productid`, `dispatch`, `quantity`, `delivery`, `date`) VALUES
(4, 5, 31, '333', 10, 1, '2014-02-27'),
(4, 5, 35, '333', 20, 1, '2014-02-27'),
(9, 5, 31, '767787E6', 7, 0, '2014-03-03'),
(9, 5, 35, '767787E6', 75, 0, '2014-03-03'),
(9, 5, 37, '767787E6', 56, 0, '2014-03-03');

-- --------------------------------------------------------

--
-- Table structure for table `ordered_material`
--

CREATE TABLE IF NOT EXISTS `ordered_material` (
  `orderid` bigint(20) NOT NULL AUTO_INCREMENT,
  `lpo` bigint(20) NOT NULL,
  `supplierid` int(12) NOT NULL,
  `materialid` int(10) NOT NULL,
  `quantity` int(20) NOT NULL,
  `outstanding` bigint(20) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`orderid`),
  KEY `supplierid` (`supplierid`,`materialid`),
  KEY `materialid` (`materialid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `ordered_material`
--

INSERT INTO `ordered_material` (`orderid`, `lpo`, `supplierid`, `materialid`, `quantity`, `outstanding`, `date`) VALUES
(2, 65745673, 3, 6, 2789, 2789, '2014-03-06'),
(3, 65745673, 3, 7, 3778, 3778, '2014-03-06'),
(4, 65745673, 3, 8, 237, 40, '2014-03-06'),
(5, 65745673, 3, 9, 568, 568, '2014-03-06'),
(6, 65745673, 3, 11, 3000, 3000, '2014-03-06'),
(7, 19985, 4, 5, 6789, 4690, '2014-03-10'),
(8, 19985, 4, 6, 400, 400, '2014-03-10'),
(9, 19985, 4, 7, 357, 357, '2014-03-10'),
(10, 19985, 4, 8, 256, 86, '2014-03-10'),
(11, 19985, 4, 9, 368, 368, '2014-03-10'),
(12, 19985, 4, 11, 578, 578, '2014-03-10'),
(13, 19985, 4, 12, 54799, 54799, '2014-03-10');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `blendid` int(2) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `wtperprimaryunit` float NOT NULL,
  `wtpercarton` float NOT NULL,
  `primaryunitpercarton` float NOT NULL,
  `pkts` float NOT NULL,
  `criticallevel` double(20,2) NOT NULL DEFAULT '200.00',
  PRIMARY KEY (`id`,`blendid`),
  KEY `blendid` (`blendid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `blendid`, `status`, `wtperprimaryunit`, `wtpercarton`, `primaryunitpercarton`, `pkts`, `criticallevel`) VALUES
(5, 'Rungwe 3g', 4, 1, 3, 1.8, 2, 600, 300.00),
(20, 'Majani ya chai', 4, 1, 2, 2, 2, 2, 200.00),
(30, 'Majani makavu', 4, 1, 6, 7, 7, 8, 200.00),
(31, 'mandazi', 3, 1, 7, 9, 3, 8, 8900.00),
(32, 'pro1', 7, 1, 3, 5, 65, 23, 600.00),
(33, 'pro1', 4, 1, 2, 675, 9, 9, 5.00),
(34, '	pro1', 4, 1, 56, 98, 12, 90, 200.00),
(35, 'ab', 3, 1, 8, 8, 8, 8, 8.00),
(36, 'ab', 4, 1, 8, 8, 8, 8, 8.00),
(37, '3g', 3, 1, 1.8, 2, 9, 5, 200.00);

-- --------------------------------------------------------

--
-- Table structure for table `product_stock`
--

CREATE TABLE IF NOT EXISTS `product_stock` (
  `productid` int(15) NOT NULL,
  `openingstock` int(10) NOT NULL DEFAULT '0',
  `closingstock` int(10) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`productid`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_stock`
--

INSERT INTO `product_stock` (`productid`, `openingstock`, `closingstock`, `date`) VALUES
(5, 100, 100, '2014-02-25'),
(5, 100, 100, '2014-02-28'),
(5, 100, 100, '2014-03-02'),
(5, 100, 100, '2014-03-03'),
(5, 100, 100, '2014-03-06'),
(5, 100, 100, '2014-03-07'),
(5, 100, 100, '2014-03-10'),
(5, 100, 100, '2014-03-11'),
(20, 100, 100, '2014-02-25'),
(20, 100, 100, '2014-02-28'),
(20, 100, 100, '2014-03-02'),
(20, 100, 100, '2014-03-03'),
(20, 100, 100, '2014-03-06'),
(20, 100, 100, '2014-03-07'),
(20, 100, 100, '2014-03-10'),
(20, 100, 100, '2014-03-11'),
(30, 100, 100, '2014-02-25'),
(30, 100, 100, '2014-02-28'),
(30, 100, 100, '2014-03-02'),
(30, 100, 100, '2014-03-03'),
(30, 100, 100, '2014-03-06'),
(30, 100, 100, '2014-03-07'),
(30, 100, 100, '2014-03-10'),
(30, 100, 100, '2014-03-11'),
(31, 100, 100, '2014-02-25'),
(31, 100, 100, '2014-02-28'),
(31, 100, 100, '2014-03-02'),
(31, 100, 93, '2014-03-03'),
(31, 93, 93, '2014-03-06'),
(31, 93, 93, '2014-03-07'),
(31, 93, 93, '2014-03-10'),
(31, 93, 93, '2014-03-11'),
(32, 100, 100, '2014-02-25'),
(32, 100, 100, '2014-02-28'),
(32, 100, 100, '2014-03-02'),
(32, 100, 100, '2014-03-03'),
(32, 100, 100, '2014-03-06'),
(32, 100, 100, '2014-03-07'),
(32, 100, 100, '2014-03-10'),
(32, 100, 100, '2014-03-11'),
(33, 100, 100, '2014-02-25'),
(33, 100, 100, '2014-02-28'),
(33, 100, 100, '2014-03-02'),
(33, 100, 100, '2014-03-03'),
(33, 100, 100, '2014-03-06'),
(33, 100, 100, '2014-03-07'),
(33, 100, 100, '2014-03-10'),
(33, 100, 100, '2014-03-11'),
(34, 100, 100, '2014-02-25'),
(34, 100, 100, '2014-02-28'),
(34, 100, 100, '2014-03-02'),
(34, 100, 100, '2014-03-03'),
(34, 100, 100, '2014-03-06'),
(34, 100, 100, '2014-03-07'),
(34, 100, 100, '2014-03-10'),
(34, 100, 100, '2014-03-11'),
(35, 100, 100, '2014-02-25'),
(35, 100, 100, '2014-02-28'),
(35, 100, 100, '2014-03-02'),
(35, 100, 25, '2014-03-03'),
(35, 25, 25, '2014-03-06'),
(35, 25, 25, '2014-03-07'),
(35, 25, 25, '2014-03-10'),
(35, 25, 25, '2014-03-11'),
(36, 100, 100, '2014-02-25'),
(36, 100, 100, '2014-02-28'),
(36, 100, 100, '2014-03-02'),
(36, 100, 100, '2014-03-03'),
(36, 100, 100, '2014-03-06'),
(36, 100, 100, '2014-03-07'),
(36, 100, 100, '2014-03-10'),
(36, 100, 100, '2014-03-11');

-- --------------------------------------------------------

--
-- Table structure for table `returned_product`
--

CREATE TABLE IF NOT EXISTS `returned_product` (
  `retid` bigint(20) NOT NULL AUTO_INCREMENT,
  `blendid` int(2) NOT NULL,
  `storeid` int(10) NOT NULL,
  `productid` int(15) NOT NULL,
  `quantity` int(30) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`retid`),
  KEY `blendid` (`blendid`,`storeid`,`productid`),
  KEY `productid` (`productid`),
  KEY `storeid` (`storeid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE IF NOT EXISTS `sales` (
  `Product_ID` int(15) NOT NULL,
  `action` varchar(10) NOT NULL DEFAULT 'Sales',
  `receiptnumber` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`Product_ID`,`action`,`receiptnumber`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `shift`
--

CREATE TABLE IF NOT EXISTS `shift` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL,
  `starttime` time NOT NULL,
  `endtime` time NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `noemployee` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `shift`
--

INSERT INTO `shift` (`id`, `name`, `starttime`, `endtime`, `status`, `noemployee`) VALUES
(2, 'A', '07:00:00', '03:00:00', 1, 50),
(3, 'B', '03:00:00', '11:00:00', 1, 50),
(4, 'C', '11:00:00', '07:00:00', 0, 50),
(5, 'C', '11:00:00', '07:00:00', 1, 30);

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE IF NOT EXISTS `store` (
  `storeid` int(10) NOT NULL AUTO_INCREMENT,
  `storename` varchar(100) NOT NULL,
  `contacts` varchar(100) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `belongto` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`storeid`),
  KEY `type` (`type`,`belongto`),
  KEY `belongto` (`belongto`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `store`
--

INSERT INTO `store` (`storeid`, `storename`, `contacts`, `type`, `status`, `belongto`) VALUES
(0, 'Main Branch', 'DSM', 1, 1, 0),
(4, 'Mbeya Branch', 'Huruma, 0713245456', 1, 1, 0),
(5, 'T 167 AAA', 'Juma, 0787656564', 2, 1, 0),
(6, 'Mufindi Branch', 'Fredrick, 0713946607', 1, 1, 0),
(7, 'T 168 AAA', 'Massawe, 0784946607', 2, 1, 0),
(8, 'Supplier', 'DSM', 3, 1, 0),
(9, 'Dodoma Branch', 'Dodoma', 1, 1, 0),
(10, 'sales person', 'SP', 4, 1, 9);

-- --------------------------------------------------------

--
-- Table structure for table `store_type`
--

CREATE TABLE IF NOT EXISTS `store_type` (
  `typeid` tinyint(4) NOT NULL AUTO_INCREMENT,
  `typename` varchar(30) NOT NULL,
  `description` varchar(200) NOT NULL DEFAULT '-',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`typeid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `store_type`
--

INSERT INTO `store_type` (`typeid`, `typename`, `description`, `status`) VALUES
(1, 'BRANCH', 'Branches', 1),
(2, 'TRANSIT', '-', 1),
(3, 'SALES VAN', '-', 1),
(4, 'SALES MAN/LADIES', '-', 1);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE IF NOT EXISTS `supplier` (
  `supplierid` int(12) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `contact` varchar(14) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`supplierid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplierid`, `name`, `contact`, `status`) VALUES
(2, 'supplier1', '222222', 1),
(3, 'supplier2', '666666', 1),
(4, 'supplier3', '097766555', 1),
(5, 'supplier4', '84567890-', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL,
  `privilege` varchar(30) NOT NULL,
  `materials` int(1) NOT NULL,
  `employees` int(1) NOT NULL,
  `production` int(1) NOT NULL,
  `allocation` int(1) NOT NULL,
  `stock` int(1) NOT NULL,
  `branches` int(1) NOT NULL,
  `reports` int(1) NOT NULL,
  `configurations` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `privilege`, `materials`, `employees`, `production`, `allocation`, `stock`, `branches`, `reports`, `configurations`) VALUES
('1', 'azizi', 'e10adc3949ba59abbe56e057f20f883e', 'Administrator', 1, 1, 1, 1, 1, 1, 1, 1),
('103', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrator', 1, 1, 1, 1, 1, 1, 1, 1),
('4', 'mathew', 'e10adc3949ba59abbe56e057f20f883e', 'Production Manager', 0, 1, 1, 1, 1, 1, 1, 1),
('T00Z', 'newe', '7f506971f50a84862d7992c30cbc2509', 'General Manager', 0, 1, 0, 0, 0, 0, 1, 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`employeeid`) REFERENCES `employee` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `blendformula`
--
ALTER TABLE `blendformula`
  ADD CONSTRAINT `blendformula_ibfk_1` FOREIGN KEY (`productid`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `blendformula_ibfk_2` FOREIGN KEY (`materialid`) REFERENCES `material` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `branch_sales`
--
ALTER TABLE `branch_sales`
  ADD CONSTRAINT `branch_sales_ibfk_1` FOREIGN KEY (`productid`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `branch_stock`
--
ALTER TABLE `branch_stock`
  ADD CONSTRAINT `branch_stock_ibfk_1` FOREIGN KEY (`productid`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `branch_stock_ibfk_2` FOREIGN KEY (`branchid`) REFERENCES `store` (`storeid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `branch_stock_tracking`
--
ALTER TABLE `branch_stock_tracking`
  ADD CONSTRAINT `branch_stock_tracking_ibfk_1` FOREIGN KEY (`storeid`) REFERENCES `store` (`storeid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `branch_stock_tracking_ibfk_2` FOREIGN KEY (`productid`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dailyinput`
--
ALTER TABLE `dailyinput`
  ADD CONSTRAINT `dailyinput_ibfk_1` FOREIGN KEY (`productid`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dailyinput_ibfk_2` FOREIGN KEY (`materialid`) REFERENCES `material` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dailyoutput`
--
ALTER TABLE `dailyoutput`
  ADD CONSTRAINT `dailyoutput_ibfk_1` FOREIGN KEY (`productid`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employee_shift`
--
ALTER TABLE `employee_shift`
  ADD CONSTRAINT `employee_shift_ibfk_1` FOREIGN KEY (`shift_id`) REFERENCES `shift` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `event_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`productid`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `invoice_ibfk_2` FOREIGN KEY (`storeid`) REFERENCES `store` (`storeid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `manpower`
--
ALTER TABLE `manpower`
  ADD CONSTRAINT `manpower_ibfk_1` FOREIGN KEY (`shift_id`) REFERENCES `shift` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `material_purchase`
--
ALTER TABLE `material_purchase`
  ADD CONSTRAINT `material_purchase_ibfk_1` FOREIGN KEY (`orderid`) REFERENCES `ordered_material` (`orderid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `material_returned`
--
ALTER TABLE `material_returned`
  ADD CONSTRAINT `material_returned_ibfk_1` FOREIGN KEY (`materialid`) REFERENCES `material` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `material_stock`
--
ALTER TABLE `material_stock`
  ADD CONSTRAINT `material_stock_ibfk_1` FOREIGN KEY (`materialid`) REFERENCES `material` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `offstock_product`
--
ALTER TABLE `offstock_product`
  ADD CONSTRAINT `offstock_product_ibfk_1` FOREIGN KEY (`productid`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `offstock_product_ibfk_2` FOREIGN KEY (`storeid`) REFERENCES `store` (`storeid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `on_transit`
--
ALTER TABLE `on_transit`
  ADD CONSTRAINT `on_transit_ibfk_1` FOREIGN KEY (`productid`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `on_transit_ibfk_2` FOREIGN KEY (`transitid`) REFERENCES `store` (`storeid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `on_transit_ibfk_3` FOREIGN KEY (`branchid`) REFERENCES `store` (`storeid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ordered_material`
--
ALTER TABLE `ordered_material`
  ADD CONSTRAINT `ordered_material_ibfk_1` FOREIGN KEY (`supplierid`) REFERENCES `supplier` (`supplierid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ordered_material_ibfk_2` FOREIGN KEY (`materialid`) REFERENCES `material` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`blendid`) REFERENCES `blend` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_stock`
--
ALTER TABLE `product_stock`
  ADD CONSTRAINT `product_stock_ibfk_1` FOREIGN KEY (`productid`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `returned_product`
--
ALTER TABLE `returned_product`
  ADD CONSTRAINT `returned_product_ibfk_1` FOREIGN KEY (`productid`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `returned_product_ibfk_2` FOREIGN KEY (`storeid`) REFERENCES `store` (`storeid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`Product_ID`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `store`
--
ALTER TABLE `store`
  ADD CONSTRAINT `store_ibfk_1` FOREIGN KEY (`belongto`) REFERENCES `store` (`storeid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `store_ibfk_2` FOREIGN KEY (`type`) REFERENCES `store_type` (`typeid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id`) REFERENCES `employee` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
