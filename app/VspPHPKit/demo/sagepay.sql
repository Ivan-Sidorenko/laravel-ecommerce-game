DROP TABLE IF EXISTS `payment`;
DROP TABLE IF EXISTS `product`;
DROP TABLE IF EXISTS `customercard`;
DROP TABLE IF EXISTS `customer`;

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `vendorTxCode` varchar(40) NOT NULL,
  `addressResult` varchar(20) DEFAULT NULL,
  `addressStatus` varchar(20) DEFAULT NULL,
  `amount` decimal(19,2) NOT NULL,
  `avsCv2` varchar(50) DEFAULT NULL,
  `basket` longtext,
  `basketXml` longtext,
  `billingAddress1` varchar(100) DEFAULT NULL,
  `billingAddress2` varchar(100) DEFAULT NULL,
  `billingCity` varchar(40) DEFAULT NULL,
  `billingCountry` varchar(2) DEFAULT NULL,
  `billingFirstnames` varchar(20) DEFAULT NULL,
  `billingPhone` varchar(20) DEFAULT NULL,
  `billingPostCode` varchar(10) DEFAULT NULL,
  `billingState` varchar(2) DEFAULT NULL,
  `billingSurname` varchar(20) DEFAULT NULL,
  `capturedAmount` decimal(19,2) DEFAULT NULL,
  `cardType` varchar(15) DEFAULT NULL,
  `cavv` varchar(32) DEFAULT NULL,
  `createToken` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `currency` varchar(3) DEFAULT NULL,
  `customerEmail` varchar(200) DEFAULT NULL,
  `cv2Result` varchar(20) DEFAULT NULL,
  `deliveryAddress1` varchar(100) DEFAULT NULL,
  `deliveryAddress2` varchar(100) DEFAULT NULL,
  `deliveryCity` varchar(40) DEFAULT NULL,
  `deliveryCountry` varchar(2) DEFAULT NULL,
  `deliveryFirstnames` varchar(20) DEFAULT NULL,
  `deliveryPhone` varchar(20) DEFAULT NULL,
  `deliveryPostCode` varchar(10) DEFAULT NULL,
  `deliveryState` varchar(2) DEFAULT NULL,
  `deliverySurname` varchar(20) DEFAULT NULL,
  `expiryDate` varchar(4) DEFAULT NULL,
  `giftAid` int(11) DEFAULT NULL,
  `last4Digits` varchar(4) DEFAULT NULL,
  `modified` datetime NOT NULL,
  `payerId` varchar(15) DEFAULT NULL,
  `payerStatus` varchar(20) DEFAULT NULL,
  `postCodeResult` varchar(20) DEFAULT NULL,
  `relatedVendorTxCode` varchar(40) DEFAULT NULL,
  `securityKey` varchar(10) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `statusMessage` varchar(255) DEFAULT NULL,
  `surcharge` decimal(19,2) DEFAULT NULL,
  `threeDSecureStatus` varchar(50) DEFAULT NULL,
  `token` varchar(40) DEFAULT NULL,
  `transactionType` varchar(32) NOT NULL,
  `txAuthNo` int(11) DEFAULT NULL,
  `vpsTxId` varchar(64) DEFAULT NULL,
  `bankAuthCode` varchar(20) DEFAULT NULL,
  `declineCode` varchar(20) DEFAULT NULL,
  `fraudResponse` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`vendorTxCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `price` float NOT NULL,
  `sku` varchar(20) NOT NULL,
  `code` int(11) NOT NULL,
  `tax` decimal(7,2) NOT NULL,
  `image` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `email` varchar(60) NOT NULL,
  `hashedPassword` varchar(32) NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `customercard`
--

CREATE TABLE `customercard` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `last4digits` varchar(4) NOT NULL,
  `modified` datetime NOT NULL,
  `token` varchar(40) NOT NULL,
  `customer_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_customercard` (`customer_id`),
  CONSTRAINT `FK_tbl_customercard` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product`
--

INSERT INTO `product` VALUES
(1,'Shaolin Soccer',9.95,'DVD1SKU',1236871,0.25,'assets/images/product-shaolin-soccer'),
(2,'Batman - The Dark Knight',10.99,'DVD2SKU',9256370,0.50,'assets/images/product-batman-the-dark-knight'),
(3,'IronMan',8.75,'DVD3SKU',84661832,0.10,'assets/images/product-ironman');
