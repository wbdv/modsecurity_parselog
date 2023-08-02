/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Table structure for table `modsec`
--

CREATE TABLE `modsec` (
  `date` datetime NOT NULL,
  `cc` varchar(2) NOT NULL DEFAULT '',
  `ip` varchar(16) NOT NULL DEFAULT '',
  `method` varchar(6) DEFAULT NULL,
  `url` varchar(128) DEFAULT NULL,
  `response_code` varchar(3) DEFAULT NULL,
  `rule_id` mediumint(8) UNSIGNED DEFAULT NULL,
  `score` tinyint(3) UNSIGNED NOT NULL,
  `msg` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
