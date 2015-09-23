-- MySQL dump 10.10
--
-- Host: localhost    Database: dictionary
-- ------------------------------------------------------
-- Server version	5.0.15-nt-max

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES euckr */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `trans_kadj_business`
--

DROP TABLE IF EXISTS `trans_kadj_business`;
CREATE TABLE `trans_kadj_business` (
  `ktitle_adj` tinytext collate utf8_unicode_ci NOT NULL,
  `ismasculin` tinyint(1) NOT NULL default '0',
  `isfeminine` tinyint(1) NOT NULL default '0',
  `isthing` tinyint(1) NOT NULL,
  `isgroup` tinyint(1) NOT NULL,
  `isplace` tinyint(1) NOT NULL default '0',
  `etitle_adj` tinytext collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`ktitle_adj`(3))
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `trans_kadj_business`
--


/*!40000 ALTER TABLE `trans_kadj_business` DISABLE KEYS */;
LOCK TABLES `trans_kadj_business` WRITE;
INSERT INTO `trans_kadj_business` VALUES ('회의',0,0,0,0,0,'meeting'),('박람회',0,0,0,0,0,'exhibition'),('명예',0,0,0,0,0,'honorary'),('현대',0,0,0,0,0,'Hyundai'),('자동차',0,0,0,0,0,'Motors'),('현대자동차',0,0,0,0,0,'Hyundai Motors'),('기아',0,0,0,0,0,'Kia'),('기아자동차',0,0,0,0,0,'Kia Motors'),('대우',0,0,0,0,0,'Daewoo'),('대우자동차',0,0,0,0,0,'Daewoo Motors'),('전',0,0,0,0,0,'former'),('삼익',0,0,0,0,0,'Samick'),('삼성',0,0,0,0,0,'Samsung'),('메가박스',0,0,0,0,0,'Megabox'),('엘지',0,0,0,0,0,'LG'),('LG',0,0,0,0,0,'LG'),('롯데',0,0,0,0,0,'Lotte'),('은행',0,0,0,0,0,'bank'),('신한',0,0,0,0,0,'Shinhan'),('외환',0,0,0,0,0,'Korean Exchange'),('우리',0,0,0,0,0,'Woori'),('마인드브릿지',0,0,0,0,0,'Mindbridge'),('농협',0,0,0,0,0,'Agricultural Workers Credit Union'),('국민',0,0,0,0,0,'KB'),('복합기업',0,0,0,0,0,'Conglomerate'),('전자',0,0,0,0,0,'Electronics'),('삼성전자',0,0,0,0,0,'Samsung Electronics'),('지점',0,0,0,0,0,'branch office'),('부서',0,0,0,0,0,'division'),('부',0,0,0,0,0,'Vice'),('예비',0,0,0,0,0,'future'),('국민은행',0,0,0,0,0,'KB Bank'),('신한은행',0,0,0,0,0,'Shinhan Bank'),('외환은행',0,0,0,0,0,'Korean Exchange Bank'),('우리은행',0,0,0,0,0,'Woori Bank'),('LG전자',0,0,0,0,0,'LG Electronics');
UNLOCK TABLES;
/*!40000 ALTER TABLE `trans_kadj_business` ENABLE KEYS */;

--
-- Table structure for table `trans_kadj_edu`
--

DROP TABLE IF EXISTS `trans_kadj_edu`;
CREATE TABLE `trans_kadj_edu` (
  `ktitle_adj` tinytext collate utf8_unicode_ci NOT NULL,
  `ismasculin` tinyint(1) NOT NULL default '0',
  `isfeminine` tinyint(1) NOT NULL default '0',
  `isthing` tinyint(1) NOT NULL,
  `isgroup` tinyint(1) NOT NULL,
  `isplace` tinyint(1) NOT NULL default '0',
  `etitle_adj` tinytext collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`ktitle_adj`(3))
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `trans_kadj_edu`
--


/*!40000 ALTER TABLE `trans_kadj_edu` DISABLE KEYS */;
LOCK TABLES `trans_kadj_edu` WRITE;
INSERT INTO `trans_kadj_edu` VALUES ('대학',0,0,0,0,0,'university'),('고등학교',0,0,0,0,0,'high school'),('중학교',0,0,0,0,0,'middle school'),('전문대학',0,0,0,0,0,'college'),('담임',0,0,0,0,0,'homeroom'),('교감',0,0,0,0,0,''),('교장',0,0,0,0,0,'principal'),('명예',0,0,0,0,0,''),('전임',0,0,0,0,0,'full-time'),('주임',0,0,0,0,0,'');
UNLOCK TABLES;
/*!40000 ALTER TABLE `trans_kadj_edu` ENABLE KEYS */;

--
-- Table structure for table `trans_kadj_family`
--

DROP TABLE IF EXISTS `trans_kadj_family`;
CREATE TABLE `trans_kadj_family` (
  `ktitle_adj` tinytext collate utf8_unicode_ci NOT NULL,
  `ismasculin` tinyint(1) NOT NULL default '0',
  `isfeminine` tinyint(1) NOT NULL default '0',
  `isthing` tinyint(1) NOT NULL,
  `isgroup` tinyint(1) NOT NULL,
  `isplace` tinyint(1) NOT NULL default '0',
  `etitle_adj` tinytext collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`ktitle_adj`(3))
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `trans_kadj_family`
--


/*!40000 ALTER TABLE `trans_kadj_family` DISABLE KEYS */;
LOCK TABLES `trans_kadj_family` WRITE;
INSERT INTO `trans_kadj_family` VALUES ('고모',0,0,0,0,0,''),('이모',0,0,0,0,0,'');
UNLOCK TABLES;
/*!40000 ALTER TABLE `trans_kadj_family` ENABLE KEYS */;

--
-- Table structure for table `trans_kadj_gov`
--

DROP TABLE IF EXISTS `trans_kadj_gov`;
CREATE TABLE `trans_kadj_gov` (
  `ktitle_adj` tinytext collate utf8_unicode_ci NOT NULL,
  `ismasculin` tinyint(1) NOT NULL default '0',
  `isfeminine` tinyint(1) NOT NULL default '0',
  `isthing` tinyint(1) NOT NULL,
  `isgroup` tinyint(1) NOT NULL,
  `isplace` tinyint(1) NOT NULL default '0',
  `etitle_adj` tinytext collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`ktitle_adj`(3))
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `trans_kadj_gov`
--


/*!40000 ALTER TABLE `trans_kadj_gov` DISABLE KEYS */;
LOCK TABLES `trans_kadj_gov` WRITE;
INSERT INTO `trans_kadj_gov` VALUES ('대선',0,0,0,0,0,'presidential election'),('예비',0,0,0,0,0,'preliminary'),('경선',0,0,0,0,0,'competitive election'),('국방부',0,0,0,0,0,'national defense'),('여성부',0,0,0,0,0,'gender equality'),('통일부',0,0,0,0,0,'Ministry of National Unification'),('외교통상부',0,0,0,0,0,'Ministry of Foreign Affairs and Trade'),('재정경제부',0,0,0,0,0,'Ministry of Finance and Economy'),('교육인적자원부',0,0,0,0,0,'Ministry of Education and Human Resources Development'),('과학기술부',0,0,0,0,0,'Ministry of Science and Technology'),('법무부',0,0,0,0,0,'Ministry of Justice'),('행정자치부',0,0,0,0,0,'Ministry of Government and Home Affairs'),('문화관광부',0,0,0,0,0,'Ministry of Culture and Tourism'),('농림부',0,0,0,0,0,'Ministry of Agriculture and Forestry'),('산업자원부',0,0,0,0,0,'Ministry of Commerce, Industry and Energy'),('정보통신부',0,0,0,0,0,'Ministry of Information and Communication'),('보건복지부',0,0,0,0,0,'Ministry of Health and Welfare'),('환경부',0,0,0,0,0,'Ministry of Environment'),('노동부',0,0,0,0,0,'Ministry of Labor'),('여성가족부',0,0,0,0,0,'gender equality and family'),('건설교통부',0,0,0,0,0,'Ministry of Construction and Transportation'),('해양수산부',0,0,0,0,0,'Ministry of Maritime Affairs and Fisheries'),('교육부',0,0,0,0,0,'Ministry of Education'),('외교부',0,0,0,0,0,'Ministry of Foreign Affairs'),('행정부',0,0,0,0,0,'Ministry of Administration'),('전',0,0,0,0,0,'former'),('대통령',0,0,0,0,0,'presidential'),('강원도',0,0,0,0,0,'Gangwon Province'),('경기도',0,0,0,0,0,'Gyeonggi Province'),('경상남도',0,0,0,0,0,'Gyeongsang-nam Province'),('경상도',0,0,0,0,0,'Gyeongsang Province'),('경상북도',0,0,0,0,0,'Gyeongsang-buk Province'),('광주광역시',0,0,0,0,0,'Gwangju'),('대구광역시',0,0,0,0,0,'Daegu'),('대전광역시',0,0,0,0,0,'Daejeon'),('부',0,0,0,0,0,'Vice'),('부산광역시',0,0,0,0,0,'Busan'),('서울특별시',0,0,0,0,0,'Seoul'),('울산광역시',0,0,0,0,0,'Ulsan'),('인천광역시',0,0,0,0,0,'Incheon'),('전라남도',0,0,0,0,0,'Jeolla-nam Province'),('전라도',0,0,0,0,0,'Jeolla Province'),('전라북도',0,0,0,0,0,'Jeolla-buk Province'),('제주도',0,0,0,0,0,'Jeju Island'),('제주특별자치도',0,0,0,0,0,'Jeju Island Autonomous Government'),('충청도',0,0,0,0,0,'Chungcheong Province'),('충청북도',0,0,0,0,0,'Chungcheong-buk Province'),('충청남도',0,0,0,0,0,'Chungcheong-nam Province');
UNLOCK TABLES;
/*!40000 ALTER TABLE `trans_kadj_gov` ENABLE KEYS */;

--
-- Table structure for table `trans_kadj_hospital`
--

DROP TABLE IF EXISTS `trans_kadj_hospital`;
CREATE TABLE `trans_kadj_hospital` (
  `ktitle_adj` tinytext collate utf8_unicode_ci NOT NULL,
  `ismasculin` tinyint(1) NOT NULL default '0',
  `isfeminine` tinyint(1) NOT NULL default '0',
  `isthing` tinyint(1) NOT NULL,
  `isgroup` tinyint(1) NOT NULL,
  `isplace` tinyint(1) NOT NULL default '0',
  `etitle_adj` tinytext collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`ktitle_adj`(3))
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `trans_kadj_hospital`
--


/*!40000 ALTER TABLE `trans_kadj_hospital` DISABLE KEYS */;
LOCK TABLES `trans_kadj_hospital` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `trans_kadj_hospital` ENABLE KEYS */;

--
-- Table structure for table `trans_kadj_military`
--

DROP TABLE IF EXISTS `trans_kadj_military`;
CREATE TABLE `trans_kadj_military` (
  `ktitle_adj` tinytext collate utf8_unicode_ci NOT NULL,
  `ismasculin` tinyint(1) NOT NULL default '0',
  `isfeminine` tinyint(1) NOT NULL default '0',
  `isthing` tinyint(1) NOT NULL,
  `isgroup` tinyint(1) NOT NULL,
  `isplace` tinyint(1) NOT NULL default '0',
  `etitle_adj` tinytext collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`ktitle_adj`(3))
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `trans_kadj_military`
--


/*!40000 ALTER TABLE `trans_kadj_military` DISABLE KEYS */;
LOCK TABLES `trans_kadj_military` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `trans_kadj_military` ENABLE KEYS */;

--
-- Table structure for table `trans_kadj_religion`
--

DROP TABLE IF EXISTS `trans_kadj_religion`;
CREATE TABLE `trans_kadj_religion` (
  `ktitle_adj` tinytext collate utf8_unicode_ci NOT NULL,
  `ismasculin` tinyint(1) NOT NULL default '0',
  `isfeminine` tinyint(1) NOT NULL default '0',
  `isthing` tinyint(1) NOT NULL,
  `isgroup` tinyint(1) NOT NULL,
  `isplace` tinyint(1) NOT NULL default '0',
  `etitle_adj` tinytext collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`ktitle_adj`(3))
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `trans_kadj_religion`
--


/*!40000 ALTER TABLE `trans_kadj_religion` DISABLE KEYS */;
LOCK TABLES `trans_kadj_religion` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `trans_kadj_religion` ENABLE KEYS */;

--
-- Table structure for table `trans_kback_part`
--

DROP TABLE IF EXISTS `trans_kback_part`;
CREATE TABLE `trans_kback_part` (
  `pin` int(8) NOT NULL auto_increment,
  `context` int(3) NOT NULL,
  `particle` tinytext collate utf8_unicode_ci NOT NULL,
  `connector` tinyint(4) NOT NULL default '0' COMMENT '0 is space, 1 is hyphen, 2 is nothing',
  `withhuman` tinyint(1) NOT NULL default '0',
  `withplace` tinyint(1) NOT NULL default '0',
  `withthing` tinyint(1) NOT NULL default '0',
  `meaning` tinytext collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`pin`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `trans_kback_part`
--


/*!40000 ALTER TABLE `trans_kback_part` DISABLE KEYS */;
LOCK TABLES `trans_kback_part` WRITE;
INSERT INTO `trans_kback_part` VALUES (1,1,'의',2,1,1,1,'\'s'),(2,1,'들',2,1,1,1,'s'),(3,1,'과',0,1,1,1,'and');
UNLOCK TABLES;
/*!40000 ALTER TABLE `trans_kback_part` ENABLE KEYS */;

--
-- Table structure for table `trans_kclause`
--

DROP TABLE IF EXISTS `trans_kclause`;
CREATE TABLE `trans_kclause` (
  `nin` int(8) NOT NULL auto_increment,
  `no_of_words` int(1) NOT NULL,
  `clause1` text collate utf8_unicode_ci NOT NULL,
  `clause2` text collate utf8_unicode_ci,
  `entry_date` date NOT NULL,
  `entry_id` varchar(8) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`nin`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `trans_kclause`
--


/*!40000 ALTER TABLE `trans_kclause` DISABLE KEYS */;
LOCK TABLES `trans_kclause` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `trans_kclause` ENABLE KEYS */;

--
-- Table structure for table `trans_kclause_meanings`
--

DROP TABLE IF EXISTS `trans_kclause_meanings`;
CREATE TABLE `trans_kclause_meanings` (
  `nin` int(8) NOT NULL,
  `context` int(3) NOT NULL default '1',
  `isplural` tinyint(1) NOT NULL default '0',
  `isperson` tinyint(1) NOT NULL default '0',
  `isgroup` tinyint(1) NOT NULL default '0',
  `isthing` tinyint(1) NOT NULL default '0',
  `isplace` tinyint(1) NOT NULL default '0',
  `isabstract` tinyint(1) NOT NULL default '0',
  `meaning` text collate utf8_unicode_ci NOT NULL,
  `entry_date` date NOT NULL,
  `entry_id` varchar(8) collate utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='1 is TRUE 0 is FALSE';

--
-- Dumping data for table `trans_kclause_meanings`
--


/*!40000 ALTER TABLE `trans_kclause_meanings` DISABLE KEYS */;
LOCK TABLES `trans_kclause_meanings` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `trans_kclause_meanings` ENABLE KEYS */;

--
-- Table structure for table `trans_kfront_part`
--

DROP TABLE IF EXISTS `trans_kfront_part`;
CREATE TABLE `trans_kfront_part` (
  `pin` int(8) NOT NULL auto_increment,
  `context` int(3) NOT NULL,
  `particle` tinytext collate utf8_unicode_ci NOT NULL,
  `connector` tinyint(4) NOT NULL COMMENT '0 is space, 1 is hyphen, 2 is nothing',
  `withhuman` tinyint(1) NOT NULL default '0',
  `withplace` tinyint(1) NOT NULL default '0',
  `withthing` tinyint(1) NOT NULL default '0',
  `meaning` tinytext collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`pin`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `trans_kfront_part`
--


/*!40000 ALTER TABLE `trans_kfront_part` DISABLE KEYS */;
LOCK TABLES `trans_kfront_part` WRITE;
INSERT INTO `trans_kfront_part` VALUES (1,1,'햇',0,0,0,1,'a new crop of');
UNLOCK TABLES;
/*!40000 ALTER TABLE `trans_kfront_part` ENABLE KEYS */;

--
-- Table structure for table `trans_kgivennames`
--

DROP TABLE IF EXISTS `trans_kgivennames`;
CREATE TABLE `trans_kgivennames` (
  `kname` tinytext collate utf8_unicode_ci NOT NULL,
  `ismasculin` tinyint(1) NOT NULL default '0',
  `isfeminine` tinyint(1) NOT NULL default '0',
  `isthing` tinyint(1) NOT NULL,
  `isgroup` tinyint(1) NOT NULL,
  `isplace` tinyint(1) NOT NULL default '0',
  `ename` tinytext collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`kname`(3))
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `trans_kgivennames`
--


/*!40000 ALTER TABLE `trans_kgivennames` DISABLE KEYS */;
LOCK TABLES `trans_kgivennames` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `trans_kgivennames` ENABLE KEYS */;

--
-- Table structure for table `trans_knames`
--

DROP TABLE IF EXISTS `trans_knames`;
CREATE TABLE `trans_knames` (
  `kname` tinytext collate utf8_unicode_ci NOT NULL,
  `ismasculin` tinyint(1) NOT NULL default '0',
  `isfeminine` tinyint(1) NOT NULL default '0',
  `isthing` tinyint(1) NOT NULL,
  `isgroup` tinyint(1) NOT NULL,
  `isplace` tinyint(1) NOT NULL default '0',
  `ename` tinytext collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`kname`(3))
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `trans_knames`
--


/*!40000 ALTER TABLE `trans_knames` DISABLE KEYS */;
LOCK TABLES `trans_knames` WRITE;
INSERT INTO `trans_knames` VALUES ('소냐',0,0,0,0,0,'Sonia'),('신',0,0,0,0,0,'Shin');
UNLOCK TABLES;
/*!40000 ALTER TABLE `trans_knames` ENABLE KEYS */;

--
-- Table structure for table `trans_knoun_meanings`
--

DROP TABLE IF EXISTS `trans_knoun_meanings`;
CREATE TABLE `trans_knoun_meanings` (
  `nin` int(8) NOT NULL,
  `context` int(3) NOT NULL default '1',
  `isplural` tinyint(1) NOT NULL default '0',
  `isperson` tinyint(1) NOT NULL default '0',
  `isgroup` tinyint(1) NOT NULL default '0',
  `isthing` tinyint(1) NOT NULL default '0',
  `isplace` tinyint(1) NOT NULL default '0',
  `isabstract` tinyint(1) NOT NULL default '0',
  `meaning` tinytext collate utf8_unicode_ci NOT NULL,
  `entry_date` date NOT NULL,
  `entry_id` varchar(8) collate utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='1 is TRUE 0 is FALSE';

--
-- Dumping data for table `trans_knoun_meanings`
--


/*!40000 ALTER TABLE `trans_knoun_meanings` DISABLE KEYS */;
LOCK TABLES `trans_knoun_meanings` WRITE;
INSERT INTO `trans_knoun_meanings` VALUES (1,1,0,0,1,0,0,0,'government','0000-00-00',''),(2,1,0,0,0,0,1,0,'Pyeongchang','0000-00-00',''),(3,1,0,0,0,1,0,0,'Olympic','0000-00-00',''),(4,1,0,0,0,1,1,0,'Hollywood.com','0000-00-00',''),(5,1,1,0,0,0,0,0,'and others','0000-00-00',''),(7,1,1,0,1,0,0,0,'foreign media','0000-00-00',''),(8,1,0,0,0,1,0,0,'apple','0000-00-00',''),(9,1,1,0,0,1,0,0,'vehicles','0000-00-00',''),(10,1,0,0,0,0,0,0,'situation','0000-00-00',''),(11,1,0,0,0,1,0,0,'automatic','0000-00-00',''),(12,1,0,0,1,1,0,0,'Daewoo','0000-00-00',''),(13,1,0,0,0,1,0,0,'Nubira','0000-00-00',''),(14,1,0,0,0,0,0,0,'option','0000-00-00',''),(15,1,0,0,0,1,0,0,'standard','0000-00-00',''),(16,1,0,0,0,1,0,0,'forcasted figures','0000-00-00',''),(17,1,0,0,0,0,0,0,'by far','0000-00-00',''),(18,1,1,0,0,0,0,0,'response','0000-00-00',''),(19,1,1,1,1,0,0,0,'police','0000-00-00',''),(20,1,0,0,0,0,0,0,'family','0000-00-00',''),(21,1,0,0,0,0,0,0,'procedure','0000-00-00',''),(22,1,0,0,0,0,0,0,'usually','0000-00-00',''),(23,1,0,0,0,0,1,0,'Korea','2007-04-08',''),(24,1,0,0,0,0,1,0,'Japan','0000-00-00',''),(25,1,0,0,0,0,1,0,'China','0000-00-00',''),(26,1,0,0,0,1,0,1,'sandwich','0000-00-00',''),(27,1,0,0,0,0,1,0,'our country','0000-00-00',''),(28,1,0,0,0,0,0,1,'fact','0000-00-00',''),(29,1,0,0,0,0,1,1,'along a difficult path','0000-00-00',''),(30,1,0,0,0,0,0,0,'in the gap','0000-00-00',''),(31,1,0,0,0,0,0,1,'a recent occurance','0000-00-00',''),(32,1,0,0,0,0,0,1,'many','2007-04-07','');
UNLOCK TABLES;
/*!40000 ALTER TABLE `trans_knoun_meanings` ENABLE KEYS */;

--
-- Table structure for table `trans_knouns`
--

DROP TABLE IF EXISTS `trans_knouns`;
CREATE TABLE `trans_knouns` (
  `nin` int(8) NOT NULL auto_increment,
  `noun1` tinytext collate utf8_unicode_ci NOT NULL,
  `noun2` tinytext collate utf8_unicode_ci,
  `entry_date` date NOT NULL,
  `entry_id` varchar(8) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`nin`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `trans_knouns`
--


/*!40000 ALTER TABLE `trans_knouns` DISABLE KEYS */;
LOCK TABLES `trans_knouns` WRITE;
INSERT INTO `trans_knouns` VALUES (1,'정부',NULL,'0000-00-00',''),(2,'평창',NULL,'0000-00-00',''),(3,'올림픽',NULL,'0000-00-00',''),(4,'할리우드 닷컴',NULL,'0000-00-00',''),(5,'등',NULL,'0000-00-00',''),(7,'해외 언론들',NULL,'0000-00-00',''),(8,'사과',NULL,'0000-00-00',''),(9,'차량',NULL,'0000-00-00',''),(10,'경우',NULL,'0000-00-00',''),(11,'오토',NULL,'0000-00-00',''),(12,'대우',NULL,'0000-00-00',''),(13,'누비라',NULL,'0000-00-00',''),(14,'미장착',NULL,'0000-00-00',''),(15,'수준',NULL,'0000-00-00',''),(16,'예상치인',NULL,'0000-00-00',''),(17,'훨씬',NULL,'0000-00-00',''),(18,'대책',NULL,'0000-00-00',''),(19,'경찰',NULL,'0000-00-00',''),(20,'가족',NULL,'0000-00-00',''),(21,'절차',NULL,'0000-00-00',''),(22,'요즘',NULL,'0000-00-00',''),(23,'한국',NULL,'0000-00-00',''),(24,'일본',NULL,'0000-00-00',''),(25,'중국',NULL,'0000-00-00',''),(26,'샌드위치',NULL,'0000-00-00',''),(27,'우리나라',NULL,'0000-00-00',''),(28,'사실',NULL,'0000-00-00',''),(29,'고생길로',NULL,'0000-00-00',''),(30,'틈바구니에서',NULL,'0000-00-00',''),(31,'어제오늘 일',NULL,'0000-00-00',''),(32,'많이','많','0000-00-00','');
UNLOCK TABLES;
/*!40000 ALTER TABLE `trans_knouns` ENABLE KEYS */;

--
-- Table structure for table `trans_kspsurnames`
--

DROP TABLE IF EXISTS `trans_kspsurnames`;
CREATE TABLE `trans_kspsurnames` (
  `kname` tinytext collate utf8_unicode_ci NOT NULL,
  `ismasculin` tinyint(1) NOT NULL default '0',
  `isfeminine` tinyint(1) NOT NULL default '0',
  `isthing` tinyint(1) NOT NULL,
  `isgroup` tinyint(1) NOT NULL,
  `isplace` tinyint(1) NOT NULL default '0',
  `ename` tinytext collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`kname`(3))
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `trans_kspsurnames`
--


/*!40000 ALTER TABLE `trans_kspsurnames` DISABLE KEYS */;
LOCK TABLES `trans_kspsurnames` WRITE;
INSERT INTO `trans_kspsurnames` VALUES ('선우',0,0,0,0,0,'Sunwoo'),('남궁',0,0,0,0,0,'Namgoong'),('독고',0,0,0,0,0,'Dokgo'),('황보',0,0,0,0,0,'Hwangbo');
UNLOCK TABLES;
/*!40000 ALTER TABLE `trans_kspsurnames` ENABLE KEYS */;

--
-- Table structure for table `trans_ksurnames`
--

DROP TABLE IF EXISTS `trans_ksurnames`;
CREATE TABLE `trans_ksurnames` (
  `kname` tinytext collate utf8_unicode_ci NOT NULL,
  `ismasculin` tinyint(1) NOT NULL default '0',
  `isfeminine` tinyint(1) NOT NULL default '0',
  `isthing` tinyint(1) NOT NULL,
  `isgroup` tinyint(1) NOT NULL,
  `isplace` tinyint(1) NOT NULL default '0',
  `ename` tinytext collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`kname`(3))
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `trans_ksurnames`
--


/*!40000 ALTER TABLE `trans_ksurnames` DISABLE KEYS */;
LOCK TABLES `trans_ksurnames` WRITE;
INSERT INTO `trans_ksurnames` VALUES ('김',0,0,0,0,0,'Kim'),('이',0,0,0,0,0,'Lee'),('박',0,0,0,0,0,'Park'),('최',0,0,0,0,0,'Choi'),('조',0,0,0,0,0,'Cho'),('정',0,0,0,0,0,'Jung'),('배',0,0,0,0,0,'Bae'),('임',0,0,0,0,0,'Lim'),('강',0,0,0,0,0,'Kang'),('윤',0,0,0,0,0,'Yoon'),('장',0,0,0,0,0,'Jang'),('한',0,0,0,0,0,'Han'),('신',0,0,0,0,0,'Shin'),('오',0,0,0,0,0,'Oh'),('서',0,0,0,0,0,'Seo'),('노',0,0,0,0,0,'Roh');
UNLOCK TABLES;
/*!40000 ALTER TABLE `trans_ksurnames` ENABLE KEYS */;

--
-- Table structure for table `trans_ktitle`
--

DROP TABLE IF EXISTS `trans_ktitle`;
CREATE TABLE `trans_ktitle` (
  `ktitle` tinytext collate utf8_unicode_ci NOT NULL,
  `ismasculin` tinyint(1) NOT NULL default '0',
  `isfeminine` tinyint(1) NOT NULL default '0',
  `isthing` tinyint(1) NOT NULL,
  `isgroup` tinyint(1) NOT NULL,
  `isplace` tinyint(1) NOT NULL default '0',
  `catagory` varchar(30) collate utf8_unicode_ci NOT NULL,
  `etitle` tinytext collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`ktitle`(3))
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `trans_ktitle`
--


/*!40000 ALTER TABLE `trans_ktitle` DISABLE KEYS */;
LOCK TABLES `trans_ktitle` WRITE;
INSERT INTO `trans_ktitle` VALUES ('대선후보',0,0,0,0,0,'gov','presidential candidate'),('예비후보',0,0,0,0,0,'gov','prepared candidate'),('후보',0,0,0,0,0,'gov','candidate'),('이장',0,0,0,0,0,'gov','villiage headman'),('통장',0,0,0,0,0,'gov','city councillor'),('반장',0,0,0,0,0,'gov','neighborhood association leader'),('시장',0,0,0,0,0,'gov','mayor'),('동장',0,0,0,0,0,'gov','sub-district leader'),('구청장',0,0,0,0,0,'gov',''),('회장',0,0,0,0,0,'business','company president'),('사장',0,0,0,0,0,'business','company president'),('직원',0,0,0,0,0,'business','staff'),('이사',0,0,0,0,0,'business','director'),('부장',0,0,0,0,0,'business','department head'),('차장',0,0,0,0,0,'business','vice-director'),('과장',0,0,0,0,0,'business','department manager'),('대리',0,0,0,0,0,'business','assistant department manager'),('실장',0,0,0,0,0,'business','section chief'),('사무장',0,0,0,0,0,'business','head official'),('주임',0,0,0,0,0,'business','team leader(lower manager)'),('면장',0,0,0,0,0,'gov','town headman'),('참석자',0,0,0,0,0,'business','attendant'),('참여자',0,0,0,0,0,'business','participant'),('고모',0,0,0,0,0,'family','aunt'),('고모부',0,0,0,0,0,'family','uncle'),('선생',0,0,0,0,0,'edu','teacher'),('담임선생',0,0,0,0,0,'edu','homeroom teacher'),('대통령',0,0,0,0,0,'gov','president'),('이모',0,0,0,0,0,'family','aunt'),('강사',0,0,0,0,0,'edu',''),('교감',0,0,0,0,0,'edu',''),('교감선생',0,0,0,0,0,'edu',''),('교수',0,0,0,0,0,'edu','professor'),('교장',0,0,0,0,0,'edu','principal'),('교장선생',0,0,0,0,0,'edu','principal'),('대학교수',0,0,0,0,0,'edu','university professor'),('명예박사',0,0,0,0,0,'edu',''),('박사',0,0,0,0,0,'edu',''),('은사',0,0,0,0,0,'edu',''),('전임강사',0,0,0,0,0,'edu','full-time instructor'),('조교',0,0,0,0,0,'edu','assistant instructor'),('조교수',0,0,0,0,0,'edu','assistant professor'),('주임선생',0,0,0,0,0,'edu',''),('총장',0,0,0,0,0,'edu','president'),('학생',0,0,0,0,0,'edu','student'),('학생주임',0,0,0,0,0,'edu',''),('학장',0,0,0,0,0,'edu','dean'),('지사',0,0,0,0,0,'gov','provincial governor'),('비서실장',0,0,0,0,0,'business','executive secretary'),('부사장',0,0,0,0,0,'business','vice-president'),('감사원장',0,0,0,0,0,'gov','Chairman of the board of Audit and Inspection'),('검찰청장',0,0,0,0,0,'gov','Chief Public Prosecutor'),('경찰청장',0,0,0,0,0,'gov','Chief of Police'),('경호실장',0,0,0,0,0,'gov','Chief officer of Presidential Security'),('관세청장',0,0,0,0,0,'gov','Customs Commisioner'),('국가정보원장',0,0,0,0,0,'gov','Chief Officer of National Intelligence Service'),('국무총리',0,0,0,0,0,'gov','prime minister'),('국세청장',0,0,0,0,0,'gov','Director of Office of National Tax Administration'),('국회의원',0,0,0,0,0,'gov','congressman'),('당원',0,0,0,0,0,'gov','party member'),('병무청장',0,0,0,0,0,'gov','Chief of Military Administration'),('의원',0,0,0,0,0,'gov','assemblyman'),('의장',0,0,0,0,0,'gov','chairman'),('장관',0,0,0,0,0,'gov','minister'),('총수',0,0,0,0,0,'gov','leader');
UNLOCK TABLES;
/*!40000 ALTER TABLE `trans_ktitle` ENABLE KEYS */;

--
-- Table structure for table `trans_kverb_meanings`
--

DROP TABLE IF EXISTS `trans_kverb_meanings`;
CREATE TABLE `trans_kverb_meanings` (
  `vin` varchar(8) collate utf8_unicode_ci NOT NULL,
  `context` varchar(3) collate utf8_unicode_ci NOT NULL default '1',
  `type` char(1) collate utf8_unicode_ci NOT NULL default '0' COMMENT '0 is intrinsitive 1 is seperable 2 is inseperable',
  `ktype` tinyint(1) NOT NULL COMMENT '0action verb or 1description verb',
  `withhuman` tinyint(1) NOT NULL default '0' COMMENT 'includes groups and organizations of people',
  `withthing` tinyint(1) NOT NULL default '0',
  `withplace` tinyint(1) NOT NULL default '0',
  `withabstract` tinyint(1) NOT NULL default '0',
  `english` tinytext collate utf8_unicode_ci NOT NULL,
  `adjective` tinytext collate utf8_unicode_ci NOT NULL,
  `noun` tinytext collate utf8_unicode_ci NOT NULL,
  `entry_date` date NOT NULL,
  `entry_id` varchar(8) collate utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='1 is TRUE 0 is FALSE';

--
-- Dumping data for table `trans_kverb_meanings`
--


/*!40000 ALTER TABLE `trans_kverb_meanings` DISABLE KEYS */;
LOCK TABLES `trans_kverb_meanings` WRITE;
INSERT INTO `trans_kverb_meanings` VALUES ('1','1','0',0,1,1,0,0,'go','','','0000-00-00',''),('2','1','0',0,1,1,1,0,'come','','','0000-00-00',''),('3','1','0',0,1,1,1,0,'is','','','0000-00-00',''),('4','1','0',0,1,0,0,0,'write','','','0000-00-00',''),('5','1','0',0,1,0,0,0,'be bore','','','0000-00-00',''),('5','1','0',0,0,1,0,0,'be not salted enough','','','0000-00-00',''),('6','1','0',0,1,1,0,0,'hurt oneself','','','0000-00-00',''),('7','1','0',0,0,1,0,0,'occur','','','0000-00-00',''),('7','1','0',0,1,0,0,0,'have an increasing space between','','','0000-00-00',''),('8','1','0',0,1,1,0,0,'do','','','0000-00-00',''),('13','1','0',0,1,1,1,0,'is','','','0000-00-00',''),('14','1','0',0,0,1,1,0,'open','','','0000-00-00',''),('15','1','0',0,1,1,1,1,'elicit','','','0000-00-00',''),('16','1','0',0,1,1,0,1,'come to realize','','','0000-00-00',''),('17','1','0',0,1,1,1,0,'be unsuccessful','','','0000-00-00',''),('18','1','0',0,1,1,0,0,'eat','','','0000-00-00',''),('19','1','0',0,0,1,0,0,'be not salty enough','','','0000-00-00',''),('19','1','0',0,1,0,0,0,'be bore','','','0000-00-00',''),('20','1','0',0,1,0,0,0,'live','','','0000-00-00',''),('21','1','0',0,1,1,0,0,'fall down','','','0000-00-00',''),('22','1','2',0,1,1,1,0,'be absent','','','0000-00-00',''),('23','1','0',0,1,0,0,0,'explain','','','0000-00-00',''),('24','1','0',0,1,1,0,1,'be able','been able','becoming','0000-00-00',''),('25','1','0',0,0,1,1,1,'enter','','entering','0000-00-00',''),('26','1','1',1,1,1,1,1,'not be','non-','not the case','0000-00-00',''),('28','1','0',0,1,1,0,1,'request','requesting','request','0000-00-00',''),('29','1','0',0,0,1,0,1,'be able to start','ready','ready','2007-05-25','danichem');
UNLOCK TABLES;
/*!40000 ALTER TABLE `trans_kverb_meanings` ENABLE KEYS */;

--
-- Table structure for table `trans_kverbs`
--

DROP TABLE IF EXISTS `trans_kverbs`;
CREATE TABLE `trans_kverbs` (
  `vin` int(8) NOT NULL auto_increment,
  `knocon` tinytext collate utf8_unicode_ci NOT NULL,
  `kcon` tinytext collate utf8_unicode_ci,
  `other` tinytext collate utf8_unicode_ci,
  `entry_date` date NOT NULL,
  `entry_id` varchar(8) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`vin`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `trans_kverbs`
--


/*!40000 ALTER TABLE `trans_kverbs` DISABLE KEYS */;
LOCK TABLES `trans_kverbs` WRITE;
INSERT INTO `trans_kverbs` VALUES (1,'가',NULL,NULL,'0000-00-00',''),(2,'오','와',NULL,'0000-00-00',''),(3,'이','이에',NULL,'0000-00-00',''),(4,'쓰','써',NULL,'0000-00-00',''),(5,'심심하다',NULL,NULL,'0000-00-00',''),(6,'자해하',NULL,NULL,'0000-00-00',''),(7,'벌어지','벌어져',NULL,'0000-00-00',''),(8,'하','해',NULL,'0000-00-00',''),(9,'춥','추워','추우','0000-00-00',''),(10,'듣','들',NULL,'0000-00-00',''),(11,'돕','도워','도우','0000-00-00',''),(12,'덥','더워','더우','0000-00-00',''),(13,'있','있어',NULL,'0000-00-00',''),(14,'열','열어',NULL,'0000-00-00',''),(15,'자아내',NULL,NULL,'0000-00-00',''),(16,'실현해오','실현해와',NULL,'0000-00-00',''),(17,'실패하','실패해',NULL,'0000-00-00',''),(18,'먹','먹어',NULL,'0000-00-00',''),(19,'싱겁','싱거워',NULL,'0000-00-00',''),(20,'살','살아','사','0000-00-00',''),(21,'넘어지','넘어져',NULL,'0000-00-00',''),(22,'없','없어',NULL,'0000-00-00',''),(23,'설명하','설명해',NULL,'0000-00-00',''),(24,'되','돼',NULL,'0000-00-00',''),(25,'접어들','접어들어',NULL,'0000-00-00',''),(26,'아니','아니에','아니예','0000-00-00',''),(27,'먹','먹어',NULL,'2007-05-13','niche'),(28,'부탁하','부탁해',NULL,'2007-05-21','danichem'),(29,'시작되','시작돼',NULL,'2007-05-25','danichem');
UNLOCK TABLES;
/*!40000 ALTER TABLE `trans_kverbs` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

