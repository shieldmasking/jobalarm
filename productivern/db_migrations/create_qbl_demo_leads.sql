CREATE TABLE IF NOT EXISTS `qbl_demo_leads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile` varchar(10) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `qbl_value` decimal(8,2) DEFAULT NULL,
  `total_weight` decimal(8,2) DEFAULT NULL,
  `supplies_json` text DEFAULT NULL,
  `contact_name` varchar(100) DEFAULT NULL,
  `contact_hospital` varchar(150) DEFAULT NULL,
  `contact_email` varchar(150) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `contact_message` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
