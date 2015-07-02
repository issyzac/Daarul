CREATE DATABASE IF NOT EXISTS pmngdb;

USE pmngdb;

DROP TABLE IF EXISTS allocation;

CREATE TABLE `allocation` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO allocation VALUES("1","Engineering","1");
INSERT INTO allocation VALUES("2","Cleanliness","1");
INSERT INTO allocation VALUES("3","Cardboxes","1");
INSERT INTO allocation VALUES("4","Ironing","1");



DROP TABLE IF EXISTS attendance;

CREATE TABLE `attendance` (
  `date` date NOT NULL,
  `employeeid` int(10) NOT NULL,
  `status` varchar(10) NOT NULL,
  `shiftid` int(10) NOT NULL,
  `allocationid` int(10) NOT NULL,
  PRIMARY KEY (`date`,`employeeid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO attendance VALUES("2013-04-21","1","1","2","0");
INSERT INTO attendance VALUES("2013-04-21","4","2","2","0");
INSERT INTO attendance VALUES("2013-04-21","103","2","4","0");
INSERT INTO attendance VALUES("2013-07-09","1","1","3","0");
INSERT INTO attendance VALUES("2013-07-09","4","1","5","0");
INSERT INTO attendance VALUES("2013-07-09","103","1","2","0");



DROP TABLE IF EXISTS attendance_status;

CREATE TABLE `attendance_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO attendance_status VALUES("1","Sick");
INSERT INTO attendance_status VALUES("2","Absentees");
INSERT INTO attendance_status VALUES("3","Leave");



DROP TABLE IF EXISTS blend;

CREATE TABLE `blend` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `status` varchar(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

INSERT INTO blend VALUES("3","Mufindi Blend","1");
INSERT INTO blend VALUES("4","Rungwe Blend","1");
INSERT INTO blend VALUES("5","Dar Blend","0");
INSERT INTO blend VALUES("6","Dar Blend","0");
INSERT INTO blend VALUES("7","Trial Blend","0");



DROP TABLE IF EXISTS blendformula;

CREATE TABLE `blendformula` (
  `productid` int(15) NOT NULL,
  `materialid` int(15) NOT NULL,
  `percentage` double(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`productid`,`materialid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS branch_sales;

CREATE TABLE `branch_sales` (
  `salesid` int(10) NOT NULL AUTO_INCREMENT,
  `receipt` varchar(50) NOT NULL,
  `productid` int(11) NOT NULL,
  `branchid` int(11) NOT NULL,
  `quantity` double NOT NULL,
  `date` date NOT NULL,
  `type` varchar(5) NOT NULL,
  PRIMARY KEY (`salesid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

INSERT INTO branch_sales VALUES("2","2","4","4","7","2013-04-15","sales");
INSERT INTO branch_sales VALUES("3","3","4","4","2","2013-04-14","van");
INSERT INTO branch_sales VALUES("4","3000","5","4","1000","2013-07-09","sales");
INSERT INTO branch_sales VALUES("5","568990","5","4","2000","2014-02-24","sales");



DROP TABLE IF EXISTS branch_stock;

CREATE TABLE `branch_stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transitid` int(11) NOT NULL,
  `branchid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `quantity` double NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

INSERT INTO branch_stock VALUES("3","5","4","4","10","2013-04-15");
INSERT INTO branch_stock VALUES("4","5","6","5","111","2013-07-01");
INSERT INTO branch_stock VALUES("5","5","6","20","11","2013-07-01");
INSERT INTO branch_stock VALUES("6","5","6","30","9","2013-07-01");
INSERT INTO branch_stock VALUES("7","7","4","30","9","2013-07-08");
INSERT INTO branch_stock VALUES("8","5","4","5","100","2013-07-10");
INSERT INTO branch_stock VALUES("9","5","4","20","200","2013-07-10");
INSERT INTO branch_stock VALUES("10","5","4","30","300","2013-07-10");
INSERT INTO branch_stock VALUES("11","7","6","5","400","2013-07-09");
INSERT INTO branch_stock VALUES("12","7","6","20","500","2013-07-09");
INSERT INTO branch_stock VALUES("13","7","6","30","600","2013-07-09");
INSERT INTO branch_stock VALUES("14","5","4","5","8390320","2013-07-09");
INSERT INTO branch_stock VALUES("15","5","4","20","2","2013-07-09");
INSERT INTO branch_stock VALUES("16","5","4","30","32763732","2013-07-09");
INSERT INTO branch_stock VALUES("17","5","4","31","9000","2013-07-09");
INSERT INTO branch_stock VALUES("18","5","4","5","500","2014-02-24");
INSERT INTO branch_stock VALUES("19","5","4","20","500","2014-02-24");
INSERT INTO branch_stock VALUES("20","5","4","30","500","2014-02-24");
INSERT INTO branch_stock VALUES("21","7","6","5","700","2014-02-24");
INSERT INTO branch_stock VALUES("22","7","6","20","700","2014-02-24");
INSERT INTO branch_stock VALUES("23","7","6","30","700","2014-02-24");



DROP TABLE IF EXISTS branch_stock_tracking;

CREATE TABLE `branch_stock_tracking` (
  `storeid` int(10) NOT NULL,
  `productid` int(12) NOT NULL,
  `openingstock` int(10) NOT NULL DEFAULT '0',
  `closingstock` int(10) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`storeid`,`productid`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO branch_stock_tracking VALUES("4","5","0","8390320","2013-07-09");
INSERT INTO branch_stock_tracking VALUES("4","20","0","2","2013-07-09");
INSERT INTO branch_stock_tracking VALUES("4","30","0","32763732","2013-07-09");
INSERT INTO branch_stock_tracking VALUES("4","31","0","9000","2013-07-09");
INSERT INTO branch_stock_tracking VALUES("5","5","0","0","2014-02-24");
INSERT INTO branch_stock_tracking VALUES("5","5","0","0","2014-02-25");
INSERT INTO branch_stock_tracking VALUES("5","20","0","0","2014-02-24");
INSERT INTO branch_stock_tracking VALUES("5","20","0","0","2014-02-25");
INSERT INTO branch_stock_tracking VALUES("5","30","0","0","2014-02-24");
INSERT INTO branch_stock_tracking VALUES("5","30","0","0","2014-02-25");
INSERT INTO branch_stock_tracking VALUES("6","5","0","400","2013-07-09");
INSERT INTO branch_stock_tracking VALUES("6","20","0","500","2013-07-09");
INSERT INTO branch_stock_tracking VALUES("6","30","0","600","2013-07-09");
INSERT INTO branch_stock_tracking VALUES("7","5","0","0","2014-02-24");
INSERT INTO branch_stock_tracking VALUES("7","5","0","0","2014-02-25");
INSERT INTO branch_stock_tracking VALUES("7","20","0","0","2014-02-24");
INSERT INTO branch_stock_tracking VALUES("7","20","0","0","2014-02-25");
INSERT INTO branch_stock_tracking VALUES("7","30","0","0","2014-02-24");
INSERT INTO branch_stock_tracking VALUES("7","30","0","0","2014-02-25");
INSERT INTO branch_stock_tracking VALUES("8","5","0","0","2013-07-09");
INSERT INTO branch_stock_tracking VALUES("8","20","0","0","2013-07-09");
INSERT INTO branch_stock_tracking VALUES("8","30","0","0","2013-07-09");



DROP TABLE IF EXISTS dailyinput;

CREATE TABLE `dailyinput` (
  `productid` int(10) NOT NULL,
  `materialid` int(10) NOT NULL,
  `date` date NOT NULL,
  `quantity` decimal(10,0) NOT NULL,
  PRIMARY KEY (`productid`,`materialid`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO dailyinput VALUES("4","5","2013-04-21","3");
INSERT INTO dailyinput VALUES("4","6","2013-04-21","10");



DROP TABLE IF EXISTS dailyoutput;

CREATE TABLE `dailyoutput` (
  `productid` int(10) NOT NULL,
  `date` date NOT NULL,
  `quantity` int(10) NOT NULL,
  PRIMARY KEY (`productid`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO dailyoutput VALUES("4","2013-04-21","4");
INSERT INTO dailyoutput VALUES("20","2014-02-13","677");



DROP TABLE IF EXISTS employee;

CREATE TABLE `employee` (
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

INSERT INTO employee VALUES("1","Azizi","S","Likumbo","M","1990-04-04","2013-04-08","Casual Grade","1");
INSERT INTO employee VALUES("103","admin","admin","admin","M","2013-07-08","2013-07-08","Casual Grade","1");
INSERT INTO employee VALUES("278h7r872","Great","man","Ever","M","2014-02-01","2014-02-24","MG","1");
INSERT INTO employee VALUES("4","Mathew","S","Erenest","M","2013-04-01","2013-04-08","MG","1");
INSERT INTO employee VALUES("534677gdh74","greatestest","woman","ofsometimes","F","2014-02-07","2014-02-23","Casual Grade","0");



DROP TABLE IF EXISTS employee_shift;

CREATE TABLE `employee_shift` (
  `shift_id` int(11) NOT NULL,
  `total_emp` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`shift_id`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS event;

CREATE TABLE `event` (
  `employee_id` int(20) NOT NULL,
  `event` text NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `item_id` varchar(50) NOT NULL,
  PRIMARY KEY (`employee_id`,`date`,`time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO event VALUES("103","Logged out of the System","2013-04-21","05:28:30","System","");
INSERT INTO event VALUES("103","Registered a new Blend","2013-04-21","05:30:29","blend","");
INSERT INTO event VALUES("103","Registered a new Blend","2013-04-21","05:31:09","blend","");
INSERT INTO event VALUES("103","Edited item from table","2013-04-21","05:31:22","blend","3");
INSERT INTO event VALUES("103","Inserted item from table","2013-04-21","05:38:13","material","");
INSERT INTO event VALUES("103","Inserted item from table","2013-04-21","05:38:51","material","");
INSERT INTO event VALUES("103","Registered a new shift","2013-04-21","05:40:18","shift","2");
INSERT INTO event VALUES("103","Edited item from table","2013-04-21","05:40:30","shift","2");
INSERT INTO event VALUES("103","Registered a new shift","2013-04-21","05:42:00","shift","3");
INSERT INTO event VALUES("103","Initialized Opening Stock for the Product","2013-04-21","05:51:45","product_stock","4");
INSERT INTO event VALUES("103","Initialized Opening Stock for the Material","2013-04-21","05:51:56","material_stock","5");
INSERT INTO event VALUES("103","Entered sold quantity for the product","2013-04-21","05:56:06","sales","4");
INSERT INTO event VALUES("103","Uploaded Excel file containing sales Data","2013-04-21","05:57:44","material_purchase","");
INSERT INTO event VALUES("103","Logged into the System","2013-04-21","07:51:09","System","");
INSERT INTO event VALUES("103","Registered a new Blend","2013-04-21","08:01:05","blend","");
INSERT INTO event VALUES("103","Edited item from table","2013-04-21","08:01:16","blend","5");
INSERT INTO event VALUES("103","Deleted item from table","2013-04-21","08:01:25","blend","5");
INSERT INTO event VALUES("103","Registered a new shift","2013-04-21","08:10:36","shift","4");
INSERT INTO event VALUES("103","Inserted item from table","2013-04-21","08:16:09","material","");
INSERT INTO event VALUES("103","Inserted item from table","2013-04-21","08:16:26","material","");
INSERT INTO event VALUES("103","Inserted item from table","2013-04-21","08:17:15","material","");
INSERT INTO event VALUES("103","Logged out of the System","2013-04-21","09:16:23","System","");
INSERT INTO event VALUES("103","Logged into the System","2013-04-21","09:16:56","System","");
INSERT INTO event VALUES("103","Logged out of the System","2013-04-21","09:17:37","System","");
INSERT INTO event VALUES("103","Logged into the System","2013-04-21","09:17:48","System","");
INSERT INTO event VALUES("103","Registered a new Blend","2013-04-21","09:18:52","blend","");
INSERT INTO event VALUES("103","Edited item from table","2013-04-21","09:19:08","blend","6");
INSERT INTO event VALUES("103","Deleted item from table","2013-04-21","09:20:50","shift","4");
INSERT INTO event VALUES("103","Registered a new shift","2013-04-21","09:21:27","shift","5");
INSERT INTO event VALUES("103","Changed employee details in a database","2013-04-22","10:32:30","employee","1");
INSERT INTO event VALUES("103","Logged into the System","2013-05-16","10:07:49","System","");
INSERT INTO event VALUES("103","Logged into the System","2013-05-16","10:16:42","System","");
INSERT INTO event VALUES("103","Logged into the System","2013-05-16","11:18:40","System","");
INSERT INTO event VALUES("103","Logged into the System","2013-05-16","16:21:02","System","");
INSERT INTO event VALUES("103","Inserted a material to be returned.","2013-05-16","19:18:31","material_returned","");
INSERT INTO event VALUES("103","Deleted item from table","2013-05-19","10:20:49","blend","6");
INSERT INTO event VALUES("103","Logged out of the System","2013-06-08","14:46:29","System","");
INSERT INTO event VALUES("103","Initialized Opening Stock for the Product","2013-07-07","14:57:04","product_stock","5");
INSERT INTO event VALUES("103","Changed employee details in a database","2013-07-08","05:57:57","employee","103");
INSERT INTO event VALUES("103","Changed employee details in a database","2013-07-08","05:59:04","employee","103");
INSERT INTO event VALUES("103","Changed employee details in a database","2013-07-08","05:59:20","employee","103");
INSERT INTO event VALUES("103","Logged into the System","2013-07-08","13:14:44","System","");
INSERT INTO event VALUES("103","Logged out of the System","2013-07-09","07:13:40","System","");
INSERT INTO event VALUES("103","Logged out of the System","2013-07-09","14:54:08","System","");
INSERT INTO event VALUES("103","Logged out of the System","2013-07-10","12:17:21","System","");
INSERT INTO event VALUES("103","Logged out of the System","2013-07-10","12:53:25","System","");
INSERT INTO event VALUES("103","Logged out of the System","2013-07-10","12:57:34","System","");
INSERT INTO event VALUES("103","Logged into the System","2013-07-10","12:57:43","System","");
INSERT INTO event VALUES("103","Logged into the System","2013-07-10","13:00:43","System","");
INSERT INTO event VALUES("103","Logged out of the System","2014-01-26","18:55:40","System","");
INSERT INTO event VALUES("103","Logged into the System","2014-01-26","18:56:46","System","");
INSERT INTO event VALUES("103","Logged out of the System","2014-01-26","19:05:15","System","");
INSERT INTO event VALUES("103","Logged into the System","2014-02-13","13:32:43","System","");
INSERT INTO event VALUES("103","Changed employee details in a database","2014-02-13","14:06:09","employee","1");
INSERT INTO event VALUES("103","Changed employee details in a database","2014-02-13","14:06:20","employee","1");
INSERT INTO event VALUES("103","Logged out of the System","2014-02-13","14:22:21","System","");
INSERT INTO event VALUES("103","Changed values for products opening stock","2014-02-24","06:28:01","dailyinput","");
INSERT INTO event VALUES("103","Changed employee details in a database","2014-02-24","08:00:49","employee","534677gdh74");
INSERT INTO event VALUES("103","Dropped all privileges for a user","2014-02-24","08:01:01","user","534677gdh74");
INSERT INTO event VALUES("103","Deleted Employee from a database","2014-02-24","08:01:43","employee","534677gdh74");
INSERT INTO event VALUES("103","Edited item from table","2014-02-24","08:04:11","blend","3");
INSERT INTO event VALUES("103","Edited item from table","2014-02-24","08:07:04","blend","3");
INSERT INTO event VALUES("103","Edited item from table","2014-02-24","08:07:21","blend","3");
INSERT INTO event VALUES("103","Edited item from table","2014-02-24","08:07:50","blend","3");
INSERT INTO event VALUES("103","Registered a new Blend","2014-02-24","08:08:07","blend","");
INSERT INTO event VALUES("103","Initialized Opening Stock for the Product","2014-02-24","08:19:19","product_stock","32");
INSERT INTO event VALUES("103","Edited item from table","2014-02-24","08:24:22","shift","2");
INSERT INTO event VALUES("103","Edited item from table","2014-02-24","08:31:14","shift","3");
INSERT INTO event VALUES("103","Edited item from table","2014-02-24","08:32:37","shift","3");
INSERT INTO event VALUES("103","Deleted item from table","2014-02-24","08:44:05","blend","7");



DROP TABLE IF EXISTS invoice;

CREATE TABLE `invoice` (
  `invoiceid` varchar(50) NOT NULL,
  `storeid` int(10) NOT NULL,
  `productid` int(10) NOT NULL,
  `date` date NOT NULL,
  `quantity` float(15,2) NOT NULL,
  PRIMARY KEY (`invoiceid`,`storeid`,`productid`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS manpower;

CREATE TABLE `manpower` (
  `job_id` int(11) NOT NULL,
  `manpower` int(11) NOT NULL,
  `shift_id` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`job_id`,`shift_id`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO manpower VALUES("1","12","2","2013-07-09");
INSERT INTO manpower VALUES("1","20","4","2013-04-21");
INSERT INTO manpower VALUES("2","5","2","2013-07-09");
INSERT INTO manpower VALUES("2","4","4","2013-04-21");
INSERT INTO manpower VALUES("3","7","2","2013-07-09");
INSERT INTO manpower VALUES("4","10","2","2013-07-09");



DROP TABLE IF EXISTS material;

CREATE TABLE `material` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` varchar(30) NOT NULL,
  `criticallevel` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

INSERT INTO material VALUES("5","Cardbox","Packing Material","200","1");
INSERT INTO material VALUES("6","PF1","Blending Material","200","1");
INSERT INTO material VALUES("7","Dust1","Blending Material","200","1");
INSERT INTO material VALUES("8","BMF","Blending Material","200","1");
INSERT INTO material VALUES("9","Satchet","Packing Material","2000","1");



DROP TABLE IF EXISTS material_purchase;

CREATE TABLE `material_purchase` (
  `materialid` int(12) NOT NULL,
  `refno` varchar(50) NOT NULL,
  `purchaseqty` int(20) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`materialid`,`purchaseqty`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS material_returned;

CREATE TABLE `material_returned` (
  `materialid` int(10) NOT NULL,
  `date` date NOT NULL,
  `quantity` int(10) NOT NULL,
  PRIMARY KEY (`materialid`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO material_returned VALUES("5","2013-05-16","34");



DROP TABLE IF EXISTS material_stock;

CREATE TABLE `material_stock` (
  `materialid` int(12) NOT NULL,
  `openingstock` int(12) NOT NULL,
  `repack` int(10) NOT NULL,
  `closingstock` int(12) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`materialid`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO material_stock VALUES("5","20","0","20","2013-04-21");
INSERT INTO material_stock VALUES("6","20","0","20","2013-04-21");
INSERT INTO material_stock VALUES("6","20","0","20","2013-05-16");
INSERT INTO material_stock VALUES("6","20","0","20","2013-06-08");
INSERT INTO material_stock VALUES("6","20","0","20","2013-06-29");
INSERT INTO material_stock VALUES("6","20","0","20","2013-07-07");
INSERT INTO material_stock VALUES("6","20","0","20","2013-07-08");
INSERT INTO material_stock VALUES("6","20","0","20","2013-07-09");
INSERT INTO material_stock VALUES("6","20","0","20","2013-07-10");
INSERT INTO material_stock VALUES("6","20","0","20","2014-01-26");
INSERT INTO material_stock VALUES("6","20","0","20","2014-02-13");
INSERT INTO material_stock VALUES("6","20","0","20","2014-02-24");
INSERT INTO material_stock VALUES("6","20","0","20","2014-02-25");
INSERT INTO material_stock VALUES("7","20","0","20","2013-04-22");
INSERT INTO material_stock VALUES("8","20","0","20","2013-04-22");
INSERT INTO material_stock VALUES("9","20","0","20","2013-05-16");
INSERT INTO material_stock VALUES("9","20","0","20","2013-06-08");
INSERT INTO material_stock VALUES("9","20","0","20","2013-06-29");
INSERT INTO material_stock VALUES("9","20","0","20","2013-07-07");
INSERT INTO material_stock VALUES("9","20","0","20","2013-07-08");
INSERT INTO material_stock VALUES("9","20","0","20","2013-07-09");
INSERT INTO material_stock VALUES("9","20","0","20","2013-07-10");
INSERT INTO material_stock VALUES("9","20","0","20","2014-01-26");
INSERT INTO material_stock VALUES("9","20","0","20","2014-02-13");
INSERT INTO material_stock VALUES("9","20","0","20","2014-02-24");
INSERT INTO material_stock VALUES("9","20","0","20","2014-02-25");



DROP TABLE IF EXISTS offstock_product;

CREATE TABLE `offstock_product` (
  `storeid` int(10) NOT NULL,
  `productid` int(10) NOT NULL,
  `quantity` bigint(20) NOT NULL,
  `dispatch` varchar(30) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO offstock_product VALUES("8","5","4000","7989","2013-07-10");
INSERT INTO offstock_product VALUES("8","20","4000","7989","2013-07-10");
INSERT INTO offstock_product VALUES("8","30","4000","7989","2013-07-10");



DROP TABLE IF EXISTS on_transit;

CREATE TABLE `on_transit` (
  `branchid` int(11) NOT NULL,
  `transitid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `dispatch` varchar(50) NOT NULL,
  `quantity` double NOT NULL,
  `delivery` tinyint(1) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`branchid`,`transitid`,`productid`,`dispatch`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO on_transit VALUES("4","5","4","2","10","1","2013-04-21");
INSERT INTO on_transit VALUES("4","5","5","4688669283","8390320","1","2013-07-10");
INSERT INTO on_transit VALUES("4","5","5","79","500","1","2014-02-24");
INSERT INTO on_transit VALUES("4","5","5","8509095","100","1","2013-07-10");
INSERT INTO on_transit VALUES("4","5","20","4688669283","2","1","2013-07-10");
INSERT INTO on_transit VALUES("4","5","20","79","500","1","2014-02-24");
INSERT INTO on_transit VALUES("4","5","20","8509095","200","1","2013-07-10");
INSERT INTO on_transit VALUES("4","5","30","4688669283","32763732","1","2013-07-10");
INSERT INTO on_transit VALUES("4","5","30","79","500","1","2014-02-24");
INSERT INTO on_transit VALUES("4","5","30","8509095","300","1","2013-07-10");
INSERT INTO on_transit VALUES("4","5","31","","9000","1","2013-07-10");
INSERT INTO on_transit VALUES("4","7","4","3","30","0","2013-04-21");
INSERT INTO on_transit VALUES("4","7","30","9779006","9","1","2013-07-07");
INSERT INTO on_transit VALUES("6","5","5","57868998","111","1","2013-07-07");
INSERT INTO on_transit VALUES("6","5","20","57868998","11","1","2013-07-07");
INSERT INTO on_transit VALUES("6","5","30","57868998","1","1","2013-07-07");
INSERT INTO on_transit VALUES("6","7","5","12098","700","1","2014-02-24");
INSERT INTO on_transit VALUES("6","7","5","jhhgiuer","400","1","2013-07-10");
INSERT INTO on_transit VALUES("6","7","20","12098","700","1","2014-02-24");
INSERT INTO on_transit VALUES("6","7","20","jhhgiuer","500","1","2013-07-10");
INSERT INTO on_transit VALUES("6","7","30","12098","700","1","2014-02-24");
INSERT INTO on_transit VALUES("6","7","30","jhhgiuer","600","1","2013-07-10");



DROP TABLE IF EXISTS product;

CREATE TABLE `product` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `blendid` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `wtperprimaryunit` float NOT NULL,
  `wtpercarton` float NOT NULL,
  `primaryunitpercarton` float NOT NULL,
  `pkts` float NOT NULL,
  `criticallevel` double(20,2) NOT NULL DEFAULT '200.00',
  PRIMARY KEY (`id`,`blendid`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

INSERT INTO product VALUES("5","Rungwe 3g","4","1","3","1.8","2","600","300.00");
INSERT INTO product VALUES("20","Majani ya chai","4","1","2","2","2","2","200.00");
INSERT INTO product VALUES("30","Majani makavu","4","1","6","7","7","8","200.00");
INSERT INTO product VALUES("31","mandazi","3","1","7","9","3","8","8900.00");
INSERT INTO product VALUES("32","pro1","7","1","3","5","65","23","600.00");
INSERT INTO product VALUES("33","pro1","4","1","2","675","9","9","5.00");
INSERT INTO product VALUES("34","	pro1","4","1","56","98","12","90","200.00");
INSERT INTO product VALUES("35","ab","3","1","8","8","8","8","8.00");
INSERT INTO product VALUES("36","ab","4","1","8","8","8","8","8.00");



DROP TABLE IF EXISTS product_stock;

CREATE TABLE `product_stock` (
  `productid` int(12) NOT NULL,
  `openingstock` int(10) NOT NULL DEFAULT '0',
  `closingstock` int(10) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`productid`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO product_stock VALUES("4","400","20","2013-04-21");
INSERT INTO product_stock VALUES("5","39000","38889","2013-07-07");
INSERT INTO product_stock VALUES("5","38889","38889","2013-07-08");
INSERT INTO product_stock VALUES("5","38889","38889","2013-07-09");
INSERT INTO product_stock VALUES("5","38889","-8351931","2013-07-10");
INSERT INTO product_stock VALUES("5","-8351931","-8351931","2014-01-26");
INSERT INTO product_stock VALUES("5","-8351931","-8351931","2014-02-13");
INSERT INTO product_stock VALUES("5","-8351931","-8353131","2014-02-24");
INSERT INTO product_stock VALUES("5","-8353131","-8353131","2014-02-25");
INSERT INTO product_stock VALUES("6","600","30","2013-04-22");
INSERT INTO product_stock VALUES("6","800","40","2013-05-16");
INSERT INTO product_stock VALUES("6","40","40","2013-06-08");
INSERT INTO product_stock VALUES("6","40","40","2013-06-29");
INSERT INTO product_stock VALUES("6","40","40","2013-07-07");
INSERT INTO product_stock VALUES("6","40","40","2013-07-08");
INSERT INTO product_stock VALUES("6","40","40","2013-07-09");
INSERT INTO product_stock VALUES("6","40","40","2013-07-10");
INSERT INTO product_stock VALUES("6","40","40","2014-01-26");
INSERT INTO product_stock VALUES("6","40","40","2014-02-13");
INSERT INTO product_stock VALUES("6","40","40","2014-02-24");
INSERT INTO product_stock VALUES("6","40","40","2014-02-25");
INSERT INTO product_stock VALUES("20","0","80","2013-05-15");
INSERT INTO product_stock VALUES("30","100","100","2013-05-16");
INSERT INTO product_stock VALUES("30","100","100","2013-06-08");
INSERT INTO product_stock VALUES("30","100","100","2013-06-29");
INSERT INTO product_stock VALUES("30","100","90","2013-07-07");
INSERT INTO product_stock VALUES("30","90","90","2013-07-08");
INSERT INTO product_stock VALUES("30","90","90","2013-07-09");
INSERT INTO product_stock VALUES("30","90","-32764542","2013-07-10");
INSERT INTO product_stock VALUES("30","-32764542","-32764542","2014-01-26");
INSERT INTO product_stock VALUES("30","-32764542","-32764542","2014-02-13");
INSERT INTO product_stock VALUES("30","-32764542","-32765742","2014-02-24");
INSERT INTO product_stock VALUES("30","-32765742","-32765742","2014-02-25");
INSERT INTO product_stock VALUES("31","39000","39000","2013-07-07");
INSERT INTO product_stock VALUES("31","39000","39000","2013-07-08");
INSERT INTO product_stock VALUES("31","39000","39000","2013-07-09");
INSERT INTO product_stock VALUES("31","39000","30000","2013-07-10");
INSERT INTO product_stock VALUES("31","30000","30000","2014-01-26");
INSERT INTO product_stock VALUES("31","30000","30000","2014-02-13");
INSERT INTO product_stock VALUES("31","30000","30000","2014-02-24");
INSERT INTO product_stock VALUES("31","30000","30000","2014-02-25");
INSERT INTO product_stock VALUES("32","500","500","2014-02-24");
INSERT INTO product_stock VALUES("32","500","500","2014-02-25");



DROP TABLE IF EXISTS returned_product;

CREATE TABLE `returned_product` (
  `retid` bigint(20) NOT NULL AUTO_INCREMENT,
  `blendid` int(2) NOT NULL,
  `storeid` int(10) NOT NULL,
  `productid` int(10) NOT NULL,
  `quantity` int(30) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`retid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO returned_product VALUES("1","3","0","5","1222400","2013-05-06");
INSERT INTO returned_product VALUES("2","4","6","20","1000","2013-07-02");
INSERT INTO returned_product VALUES("3","3","4","0","0","2014-02-13");



DROP TABLE IF EXISTS sales;

CREATE TABLE `sales` (
  `Product_ID` int(10) NOT NULL,
  `action` varchar(10) NOT NULL DEFAULT 'Sales',
  `receiptnumber` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`Product_ID`,`action`,`receiptnumber`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO sales VALUES("4","Sales","1","10","2013-04-21");
INSERT INTO sales VALUES("4","Sales","2","30","2013-04-21");



DROP TABLE IF EXISTS shift;

CREATE TABLE `shift` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL,
  `starttime` time NOT NULL,
  `endtime` time NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `noemployee` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

INSERT INTO shift VALUES("2","A","07:00:00","03:00:00","1","50");
INSERT INTO shift VALUES("3","B","03:00:00","11:00:00","1","50");
INSERT INTO shift VALUES("4","C","11:00:00","07:00:00","0","50");
INSERT INTO shift VALUES("5","C","11:00:00","07:00:00","1","30");



DROP TABLE IF EXISTS store;

CREATE TABLE `store` (
  `storeid` int(10) NOT NULL AUTO_INCREMENT,
  `storename` varchar(100) NOT NULL,
  `contacts` varchar(100) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `belongto` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`storeid`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

INSERT INTO store VALUES("4","Mbeya Branch","Huruma, 0713245456","1","1","0");
INSERT INTO store VALUES("5","T 167 AAA","Juma, 0787656564","2","1","0");
INSERT INTO store VALUES("6","Mufindi Branch","Fredrick, 0713946607","1","1","0");
INSERT INTO store VALUES("7","T 168 AAA","Massawe, 0784946607","2","1","0");
INSERT INTO store VALUES("8","Supplier","DSM","3","1","0");
INSERT INTO store VALUES("9","Dodoma Branch","Dodoma","1","1","0");
INSERT INTO store VALUES("10","sales person","SP","4","1","9");



DROP TABLE IF EXISTS store_type;

CREATE TABLE `store_type` (
  `typeid` tinyint(4) NOT NULL AUTO_INCREMENT,
  `typename` varchar(30) NOT NULL,
  `description` varchar(200) NOT NULL DEFAULT '-',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`typeid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO store_type VALUES("1","BRANCH","","1");
INSERT INTO store_type VALUES("2","TRANSIT","zinasafirishwa...","1");
INSERT INTO store_type VALUES("3","SALES VAN","wanazunguka na magari","1");
INSERT INTO store_type VALUES("4","SALES LADIES/MEN","retail sellers			","1");



DROP TABLE IF EXISTS user;

CREATE TABLE `user` (
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

INSERT INTO user VALUES("1","azizi","e10adc3949ba59abbe56e057f20f883e","Administrator","1","1","1","1","1","1","1","1");
INSERT INTO user VALUES("103","admin","21232f297a57a5a743894a0e4a801fc3","Administrator","1","1","1","1","1","1","1","1");
INSERT INTO user VALUES("4","mathew","e10adc3949ba59abbe56e057f20f883e","Production Manager","0","1","1","1","1","1","1","1");



