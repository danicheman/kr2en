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
INSERT INTO `trans_kadj_business` VALUES ('ȸ��',0,0,0,0,0,'meeting'),('�ڶ�ȸ',0,0,0,0,0,'exhibition'),('��',0,0,0,0,0,'honorary'),('����',0,0,0,0,0,'Hyundai'),('�ڵ���',0,0,0,0,0,'Motors'),('�����ڵ���',0,0,0,0,0,'Hyundai Motors'),('���',0,0,0,0,0,'Kia'),('����ڵ���',0,0,0,0,0,'Kia Motors'),('���',0,0,0,0,0,'Daewoo'),('����ڵ���',0,0,0,0,0,'Daewoo Motors'),('��',0,0,0,0,0,'former'),('����',0,0,0,0,0,'Samick'),('�Ｚ',0,0,0,0,0,'Samsung'),('�ް��ڽ�',0,0,0,0,0,'Megabox'),('����',0,0,0,0,0,'LG'),('LG',0,0,0,0,0,'LG'),('�Ե�',0,0,0,0,0,'Lotte'),('����',0,0,0,0,0,'bank'),('����',0,0,0,0,0,'Shinhan'),('��ȯ',0,0,0,0,0,'Korean Exchange'),('�츮',0,0,0,0,0,'Woori'),('���ε�긴��',0,0,0,0,0,'Mindbridge'),('����',0,0,0,0,0,'Agricultural Workers Credit Union'),('����',0,0,0,0,0,'KB'),('���ձ��',0,0,0,0,0,'Conglomerate'),('����',0,0,0,0,0,'Electronics'),('�Ｚ����',0,0,0,0,0,'Samsung Electronics'),('����',0,0,0,0,0,'branch office'),('�μ�',0,0,0,0,0,'division'),('��',0,0,0,0,0,'Vice'),('����',0,0,0,0,0,'future'),('��������',0,0,0,0,0,'KB Bank'),('��������',0,0,0,0,0,'Shinhan Bank'),('��ȯ����',0,0,0,0,0,'Korean Exchange Bank'),('�츮����',0,0,0,0,0,'Woori Bank'),('LG����',0,0,0,0,0,'LG Electronics');
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
INSERT INTO `trans_kadj_edu` VALUES ('����',0,0,0,0,0,'university'),('����б�',0,0,0,0,0,'high school'),('���б�',0,0,0,0,0,'middle school'),('��������',0,0,0,0,0,'college'),('����',0,0,0,0,0,'homeroom'),('����',0,0,0,0,0,''),('����',0,0,0,0,0,'principal'),('��',0,0,0,0,0,''),('����',0,0,0,0,0,'full-time'),('����',0,0,0,0,0,'');
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
INSERT INTO `trans_kadj_family` VALUES ('���',0,0,0,0,0,''),('�̸�',0,0,0,0,0,'');
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
INSERT INTO `trans_kadj_gov` VALUES ('�뼱',0,0,0,0,0,'presidential election'),('����',0,0,0,0,0,'preliminary'),('�漱',0,0,0,0,0,'competitive election'),('�����',0,0,0,0,0,'national defense'),('������',0,0,0,0,0,'gender equality'),('���Ϻ�',0,0,0,0,0,'Ministry of National Unification'),('�ܱ�����',0,0,0,0,0,'Ministry of Foreign Affairs and Trade'),('����������',0,0,0,0,0,'Ministry of Finance and Economy'),('���������ڿ���',0,0,0,0,0,'Ministry of Education and Human Resources Development'),('���б����',0,0,0,0,0,'Ministry of Science and Technology'),('������',0,0,0,0,0,'Ministry of Justice'),('������ġ��',0,0,0,0,0,'Ministry of Government and Home Affairs'),('��ȭ������',0,0,0,0,0,'Ministry of Culture and Tourism'),('�󸲺�',0,0,0,0,0,'Ministry of Agriculture and Forestry'),('����ڿ���',0,0,0,0,0,'Ministry of Commerce, Industry and Energy'),('������ź�',0,0,0,0,0,'Ministry of Information and Communication'),('���Ǻ�����',0,0,0,0,0,'Ministry of Health and Welfare'),('ȯ���',0,0,0,0,0,'Ministry of Environment'),('�뵿��',0,0,0,0,0,'Ministry of Labor'),('����������',0,0,0,0,0,'gender equality and family'),('�Ǽ������',0,0,0,0,0,'Ministry of Construction and Transportation'),('�ؾ�����',0,0,0,0,0,'Ministry of Maritime Affairs and Fisheries'),('������',0,0,0,0,0,'Ministry of Education'),('�ܱ���',0,0,0,0,0,'Ministry of Foreign Affairs'),('������',0,0,0,0,0,'Ministry of Administration'),('��',0,0,0,0,0,'former'),('�����',0,0,0,0,0,'presidential'),('������',0,0,0,0,0,'Gangwon Province'),('��⵵',0,0,0,0,0,'Gyeonggi Province'),('��󳲵�',0,0,0,0,0,'Gyeongsang-nam Province'),('���',0,0,0,0,0,'Gyeongsang Province'),('���ϵ�',0,0,0,0,0,'Gyeongsang-buk Province'),('���ֱ�����',0,0,0,0,0,'Gwangju'),('�뱸������',0,0,0,0,0,'Daegu'),('����������',0,0,0,0,0,'Daejeon'),('��',0,0,0,0,0,'Vice'),('�λ걤����',0,0,0,0,0,'Busan'),('����Ư����',0,0,0,0,0,'Seoul'),('��걤����',0,0,0,0,0,'Ulsan'),('��õ������',0,0,0,0,0,'Incheon'),('���󳲵�',0,0,0,0,0,'Jeolla-nam Province'),('����',0,0,0,0,0,'Jeolla Province'),('����ϵ�',0,0,0,0,0,'Jeolla-buk Province'),('���ֵ�',0,0,0,0,0,'Jeju Island'),('����Ư����ġ��',0,0,0,0,0,'Jeju Island Autonomous Government'),('��û��',0,0,0,0,0,'Chungcheong Province'),('��û�ϵ�',0,0,0,0,0,'Chungcheong-buk Province'),('��û����',0,0,0,0,0,'Chungcheong-nam Province');
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
INSERT INTO `trans_kback_part` VALUES (1,1,'��',2,1,1,1,'\'s'),(2,1,'��',2,1,1,1,'s'),(3,1,'��',0,1,1,1,'and');
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
INSERT INTO `trans_kfront_part` VALUES (1,1,'��',0,0,0,1,'a new crop of');
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
INSERT INTO `trans_knames` VALUES ('�ҳ�',0,0,0,0,0,'Sonia'),('��',0,0,0,0,0,'Shin');
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
INSERT INTO `trans_knouns` VALUES (1,'����',NULL,'0000-00-00',''),(2,'��â',NULL,'0000-00-00',''),(3,'�ø���',NULL,'0000-00-00',''),(4,'�Ҹ���� ����',NULL,'0000-00-00',''),(5,'��',NULL,'0000-00-00',''),(7,'�ؿ� ��е�',NULL,'0000-00-00',''),(8,'���',NULL,'0000-00-00',''),(9,'����',NULL,'0000-00-00',''),(10,'���',NULL,'0000-00-00',''),(11,'����',NULL,'0000-00-00',''),(12,'���',NULL,'0000-00-00',''),(13,'�����',NULL,'0000-00-00',''),(14,'������',NULL,'0000-00-00',''),(15,'����',NULL,'0000-00-00',''),(16,'����ġ��',NULL,'0000-00-00',''),(17,'�ξ�',NULL,'0000-00-00',''),(18,'��å',NULL,'0000-00-00',''),(19,'����',NULL,'0000-00-00',''),(20,'����',NULL,'0000-00-00',''),(21,'����',NULL,'0000-00-00',''),(22,'����',NULL,'0000-00-00',''),(23,'�ѱ�',NULL,'0000-00-00',''),(24,'�Ϻ�',NULL,'0000-00-00',''),(25,'�߱�',NULL,'0000-00-00',''),(26,'������ġ',NULL,'0000-00-00',''),(27,'�츮����',NULL,'0000-00-00',''),(28,'���',NULL,'0000-00-00',''),(29,'������',NULL,'0000-00-00',''),(30,'ƴ�ٱ��Ͽ���',NULL,'0000-00-00',''),(31,'�������� ��',NULL,'0000-00-00',''),(32,'����','��','0000-00-00','');
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
INSERT INTO `trans_kspsurnames` VALUES ('����',0,0,0,0,0,'Sunwoo'),('����',0,0,0,0,0,'Namgoong'),('����',0,0,0,0,0,'Dokgo'),('Ȳ��',0,0,0,0,0,'Hwangbo');
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
INSERT INTO `trans_ksurnames` VALUES ('��',0,0,0,0,0,'Kim'),('��',0,0,0,0,0,'Lee'),('��',0,0,0,0,0,'Park'),('��',0,0,0,0,0,'Choi'),('��',0,0,0,0,0,'Cho'),('��',0,0,0,0,0,'Jung'),('��',0,0,0,0,0,'Bae'),('��',0,0,0,0,0,'Lim'),('��',0,0,0,0,0,'Kang'),('��',0,0,0,0,0,'Yoon'),('��',0,0,0,0,0,'Jang'),('��',0,0,0,0,0,'Han'),('��',0,0,0,0,0,'Shin'),('��',0,0,0,0,0,'Oh'),('��',0,0,0,0,0,'Seo'),('��',0,0,0,0,0,'Roh');
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
INSERT INTO `trans_ktitle` VALUES ('�뼱�ĺ�',0,0,0,0,0,'gov','presidential candidate'),('�����ĺ�',0,0,0,0,0,'gov','prepared candidate'),('�ĺ�',0,0,0,0,0,'gov','candidate'),('����',0,0,0,0,0,'gov','villiage headman'),('����',0,0,0,0,0,'gov','city councillor'),('����',0,0,0,0,0,'gov','neighborhood association leader'),('����',0,0,0,0,0,'gov','mayor'),('����',0,0,0,0,0,'gov','sub-district leader'),('��û��',0,0,0,0,0,'gov',''),('ȸ��',0,0,0,0,0,'business','company president'),('����',0,0,0,0,0,'business','company president'),('����',0,0,0,0,0,'business','staff'),('�̻�',0,0,0,0,0,'business','director'),('����',0,0,0,0,0,'business','department head'),('����',0,0,0,0,0,'business','vice-director'),('����',0,0,0,0,0,'business','department manager'),('�븮',0,0,0,0,0,'business','assistant department manager'),('����',0,0,0,0,0,'business','section chief'),('�繫��',0,0,0,0,0,'business','head official'),('����',0,0,0,0,0,'business','team leader(lower manager)'),('����',0,0,0,0,0,'gov','town headman'),('������',0,0,0,0,0,'business','attendant'),('������',0,0,0,0,0,'business','participant'),('���',0,0,0,0,0,'family','aunt'),('����',0,0,0,0,0,'family','uncle'),('����',0,0,0,0,0,'edu','teacher'),('���Ӽ���',0,0,0,0,0,'edu','homeroom teacher'),('�����',0,0,0,0,0,'gov','president'),('�̸�',0,0,0,0,0,'family','aunt'),('����',0,0,0,0,0,'edu',''),('����',0,0,0,0,0,'edu',''),('��������',0,0,0,0,0,'edu',''),('����',0,0,0,0,0,'edu','professor'),('����',0,0,0,0,0,'edu','principal'),('���弱��',0,0,0,0,0,'edu','principal'),('���б���',0,0,0,0,0,'edu','university professor'),('���ڻ�',0,0,0,0,0,'edu',''),('�ڻ�',0,0,0,0,0,'edu',''),('����',0,0,0,0,0,'edu',''),('���Ӱ���',0,0,0,0,0,'edu','full-time instructor'),('����',0,0,0,0,0,'edu','assistant instructor'),('������',0,0,0,0,0,'edu','assistant professor'),('���Ӽ���',0,0,0,0,0,'edu',''),('����',0,0,0,0,0,'edu','president'),('�л�',0,0,0,0,0,'edu','student'),('�л�����',0,0,0,0,0,'edu',''),('����',0,0,0,0,0,'edu','dean'),('����',0,0,0,0,0,'gov','provincial governor'),('�񼭽���',0,0,0,0,0,'business','executive secretary'),('�λ���',0,0,0,0,0,'business','vice-president'),('�������',0,0,0,0,0,'gov','Chairman of the board of Audit and Inspection'),('����û��',0,0,0,0,0,'gov','Chief Public Prosecutor'),('����û��',0,0,0,0,0,'gov','Chief of Police'),('��ȣ����',0,0,0,0,0,'gov','Chief officer of Presidential Security'),('����û��',0,0,0,0,0,'gov','Customs Commisioner'),('������������',0,0,0,0,0,'gov','Chief Officer of National Intelligence Service'),('�����Ѹ�',0,0,0,0,0,'gov','prime minister'),('����û��',0,0,0,0,0,'gov','Director of Office of National Tax Administration'),('��ȸ�ǿ�',0,0,0,0,0,'gov','congressman'),('���',0,0,0,0,0,'gov','party member'),('����û��',0,0,0,0,0,'gov','Chief of Military Administration'),('�ǿ�',0,0,0,0,0,'gov','assemblyman'),('����',0,0,0,0,0,'gov','chairman'),('���',0,0,0,0,0,'gov','minister'),('�Ѽ�',0,0,0,0,0,'gov','leader');
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
INSERT INTO `trans_kverbs` VALUES (1,'��',NULL,NULL,'0000-00-00',''),(2,'��','��',NULL,'0000-00-00',''),(3,'��','�̿�',NULL,'0000-00-00',''),(4,'��','��',NULL,'0000-00-00',''),(5,'�ɽ��ϴ�',NULL,NULL,'0000-00-00',''),(6,'������',NULL,NULL,'0000-00-00',''),(7,'������','������',NULL,'0000-00-00',''),(8,'��','��',NULL,'0000-00-00',''),(9,'��','�߿�','�߿�','0000-00-00',''),(10,'��','��',NULL,'0000-00-00',''),(11,'��','����','����','0000-00-00',''),(12,'��','����','����','0000-00-00',''),(13,'��','�־�',NULL,'0000-00-00',''),(14,'��','����',NULL,'0000-00-00',''),(15,'�ھƳ�',NULL,NULL,'0000-00-00',''),(16,'�����ؿ�','�����ؿ�',NULL,'0000-00-00',''),(17,'������','������',NULL,'0000-00-00',''),(18,'��','�Ծ�',NULL,'0000-00-00',''),(19,'�̰�','�̰ſ�',NULL,'0000-00-00',''),(20,'��','���','��','0000-00-00',''),(21,'�Ѿ���','�Ѿ���',NULL,'0000-00-00',''),(22,'��','����',NULL,'0000-00-00',''),(23,'������','������',NULL,'0000-00-00',''),(24,'��','��',NULL,'0000-00-00',''),(25,'�����','������',NULL,'0000-00-00',''),(26,'�ƴ�','�ƴϿ�','�ƴϿ�','0000-00-00',''),(27,'��','�Ծ�',NULL,'2007-05-13','niche'),(28,'��Ź��','��Ź��',NULL,'2007-05-21','danichem'),(29,'���۵�','���۵�',NULL,'2007-05-25','danichem');
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

