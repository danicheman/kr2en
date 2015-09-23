-- phpMyAdmin SQL Dump
-- version 2.6.4-pl3
-- http://www.phpmyadmin.net
-- 
-- 호스트: localhost
-- 처리한 시간: 07-06-14 12:56 
-- 서버 버전: 5.0.15
-- PHP 버전: 5.0.5
-- 
-- 데이터베이스: `dictionary`
-- 

-- --------------------------------------------------------

-- 
-- 테이블 구조 `trans_kback_part`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- 
-- 테이블의 덤프 데이터 `trans_kback_part`
-- 

INSERT INTO `trans_kback_part` (`pin`, `context`, `particle`, `connector`, `withhuman`, `withplace`, `withthing`, `meaning`) VALUES (1, 1, '의', 2, 1, 1, 1, '''s'),
(2, 1, '들', 2, 1, 1, 1, 's'),
(3, 1, '과', 0, 1, 1, 1, 'and');

-- --------------------------------------------------------

-- 
-- 테이블 구조 `trans_kclause`
-- 

CREATE TABLE `trans_kclause` (
  `nin` int(8) NOT NULL auto_increment,
  `no_of_words` int(1) NOT NULL,
  `clause1` text collate utf8_unicode_ci NOT NULL,
  `clause2` text collate utf8_unicode_ci,
  `entry_date` date NOT NULL,
  `entry_id` varchar(8) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`nin`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- 테이블의 덤프 데이터 `trans_kclause`
-- 


-- --------------------------------------------------------

-- 
-- 테이블 구조 `trans_kclause_meanings`
-- 

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
-- 테이블의 덤프 데이터 `trans_kclause_meanings`
-- 


-- --------------------------------------------------------

-- 
-- 테이블 구조 `trans_kfront_part`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- 
-- 테이블의 덤프 데이터 `trans_kfront_part`
-- 

INSERT INTO `trans_kfront_part` (`pin`, `context`, `particle`, `connector`, `withhuman`, `withplace`, `withthing`, `meaning`) VALUES (1, 1, '햇', 0, 0, 0, 1, 'a new crop of');

-- --------------------------------------------------------

-- 
-- 테이블 구조 `trans_kgivennames`
-- 

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
-- 테이블의 덤프 데이터 `trans_kgivennames`
-- 


-- --------------------------------------------------------

-- 
-- 테이블 구조 `trans_knames`
-- 

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
-- 테이블의 덤프 데이터 `trans_knames`
-- 

INSERT INTO `trans_knames` (`kname`, `ismasculin`, `isfeminine`, `isthing`, `isgroup`, `isplace`, `ename`) VALUES ('소냐', 0, 0, 0, 0, 0, 'Sonia'),
('신', 0, 0, 0, 0, 0, 'Shin');

-- --------------------------------------------------------

-- 
-- 테이블 구조 `trans_knoun_meanings`
-- 

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
-- 테이블의 덤프 데이터 `trans_knoun_meanings`
-- 

INSERT INTO `trans_knoun_meanings` (`nin`, `context`, `isplural`, `isperson`, `isgroup`, `isthing`, `isplace`, `isabstract`, `meaning`, `entry_date`, `entry_id`) VALUES (1, 1, 0, 0, 1, 0, 0, 0, 'government', '0000-00-00', ''),
(2, 1, 0, 0, 0, 0, 1, 0, 'Pyeongchang', '0000-00-00', ''),
(3, 1, 0, 0, 0, 1, 0, 0, 'Olympic', '0000-00-00', ''),
(4, 1, 0, 0, 0, 1, 1, 0, 'Hollywood.com', '0000-00-00', ''),
(5, 1, 1, 0, 0, 0, 0, 0, 'and others', '0000-00-00', ''),
(7, 1, 1, 0, 1, 0, 0, 0, 'foreign media', '0000-00-00', ''),
(8, 1, 0, 0, 0, 1, 0, 0, 'apple', '0000-00-00', ''),
(9, 1, 1, 0, 0, 1, 0, 0, 'vehicles', '0000-00-00', ''),
(10, 1, 0, 0, 0, 0, 0, 0, 'situation', '0000-00-00', ''),
(11, 1, 0, 0, 0, 1, 0, 0, 'automatic', '0000-00-00', ''),
(12, 1, 0, 0, 1, 1, 0, 0, 'Daewoo', '0000-00-00', ''),
(13, 1, 0, 0, 0, 1, 0, 0, 'Nubira', '0000-00-00', ''),
(14, 1, 0, 0, 0, 0, 0, 0, 'option', '0000-00-00', ''),
(15, 1, 0, 0, 0, 1, 0, 0, 'standard', '0000-00-00', ''),
(16, 1, 0, 0, 0, 1, 0, 0, 'forcasted figures', '0000-00-00', ''),
(17, 1, 0, 0, 0, 0, 0, 0, 'by far', '0000-00-00', ''),
(18, 1, 1, 0, 0, 0, 0, 0, 'response', '0000-00-00', ''),
(19, 1, 1, 1, 1, 0, 0, 0, 'police', '0000-00-00', ''),
(20, 1, 0, 0, 0, 0, 0, 0, 'family', '0000-00-00', ''),
(21, 1, 0, 0, 0, 0, 0, 0, 'procedure', '0000-00-00', ''),
(22, 1, 0, 0, 0, 0, 0, 0, 'usually', '0000-00-00', ''),
(23, 1, 0, 0, 0, 0, 1, 0, 'Korea', '2007-04-08', ''),
(24, 1, 0, 0, 0, 0, 1, 0, 'Japan', '0000-00-00', ''),
(25, 1, 0, 0, 0, 0, 1, 0, 'China', '0000-00-00', ''),
(26, 1, 0, 0, 0, 1, 0, 1, 'sandwich', '0000-00-00', ''),
(27, 1, 0, 0, 0, 0, 1, 0, 'our country', '0000-00-00', ''),
(28, 1, 0, 0, 0, 0, 0, 1, 'fact', '0000-00-00', ''),
(29, 1, 0, 0, 0, 0, 1, 1, 'along a difficult path', '0000-00-00', ''),
(30, 1, 0, 0, 0, 0, 0, 0, 'in the gap', '0000-00-00', ''),
(31, 1, 0, 0, 0, 0, 0, 1, 'a recent occurance', '0000-00-00', ''),
(32, 1, 0, 0, 0, 0, 0, 1, 'many', '2007-04-07', '');

-- --------------------------------------------------------

-- 
-- 테이블 구조 `trans_knouns`
-- 

CREATE TABLE `trans_knouns` (
  `nin` int(8) NOT NULL auto_increment,
  `noun1` tinytext collate utf8_unicode_ci NOT NULL,
  `noun2` tinytext collate utf8_unicode_ci,
  `entry_date` date NOT NULL,
  `entry_id` varchar(8) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`nin`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=33 ;

-- 
-- 테이블의 덤프 데이터 `trans_knouns`
-- 

INSERT INTO `trans_knouns` (`nin`, `noun1`, `noun2`, `entry_date`, `entry_id`) VALUES (1, '정부', NULL, '0000-00-00', ''),
(2, '평창', NULL, '0000-00-00', ''),
(3, '올림픽', NULL, '0000-00-00', ''),
(4, '할리우드 닷컴', NULL, '0000-00-00', ''),
(5, '등', NULL, '0000-00-00', ''),
(7, '해외 언론들', NULL, '0000-00-00', ''),
(8, '사과', NULL, '0000-00-00', ''),
(9, '차량', NULL, '0000-00-00', ''),
(10, '경우', NULL, '0000-00-00', ''),
(11, '오토', NULL, '0000-00-00', ''),
(12, '대우', NULL, '0000-00-00', ''),
(13, '누비라', NULL, '0000-00-00', ''),
(14, '미장착', NULL, '0000-00-00', ''),
(15, '수준', NULL, '0000-00-00', ''),
(16, '예상치인', NULL, '0000-00-00', ''),
(17, '훨씬', NULL, '0000-00-00', ''),
(18, '대책', NULL, '0000-00-00', ''),
(19, '경찰', NULL, '0000-00-00', ''),
(20, '가족', NULL, '0000-00-00', ''),
(21, '절차', NULL, '0000-00-00', ''),
(22, '요즘', NULL, '0000-00-00', ''),
(23, '한국', NULL, '0000-00-00', ''),
(24, '일본', NULL, '0000-00-00', ''),
(25, '중국', NULL, '0000-00-00', ''),
(26, '샌드위치', NULL, '0000-00-00', ''),
(27, '우리나라', NULL, '0000-00-00', ''),
(28, '사실', NULL, '0000-00-00', ''),
(29, '고생길로', NULL, '0000-00-00', ''),
(30, '틈바구니에서', NULL, '0000-00-00', ''),
(31, '어제오늘 일', NULL, '0000-00-00', ''),
(32, '많이', '많', '0000-00-00', '');

-- --------------------------------------------------------

-- 
-- 테이블 구조 `trans_kspsurnames`
-- 

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
-- 테이블의 덤프 데이터 `trans_kspsurnames`
-- 

INSERT INTO `trans_kspsurnames` (`kname`, `ismasculin`, `isfeminine`, `isthing`, `isgroup`, `isplace`, `ename`) VALUES ('선우', 0, 0, 0, 0, 0, 'Sunwoo'),
('남궁', 0, 0, 0, 0, 0, 'NamGoong'),
('독고', 0, 0, 0, 0, 0, 'Dokgo'),
('황보', 0, 0, 0, 0, 0, 'HwangBo');

-- --------------------------------------------------------

-- 
-- 테이블 구조 `trans_ksurnames`
-- 

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
-- 테이블의 덤프 데이터 `trans_ksurnames`
-- 

INSERT INTO `trans_ksurnames` (`kname`, `ismasculin`, `isfeminine`, `isthing`, `isgroup`, `isplace`, `ename`) VALUES ('김', 0, 0, 0, 0, 0, 'Kim'),
('이', 0, 0, 0, 0, 0, 'Lee'),
('박', 0, 0, 0, 0, 0, 'Park'),
('최', 0, 0, 0, 0, 0, 'Choi'),
('조', 0, 0, 0, 0, 0, 'Cho'),
('정', 0, 0, 0, 0, 0, 'Jung'),
('배', 0, 0, 0, 0, 0, 'Bae'),
('임', 0, 0, 0, 0, 0, 'Lim');

-- --------------------------------------------------------

-- 
-- 테이블 구조 `trans_kverb_meanings`
-- 

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
-- 테이블의 덤프 데이터 `trans_kverb_meanings`
-- 

INSERT INTO `trans_kverb_meanings` (`vin`, `context`, `type`, `ktype`, `withhuman`, `withthing`, `withplace`, `withabstract`, `english`, `adjective`, `noun`, `entry_date`, `entry_id`) VALUES ('1', '1', '0', 0, 1, 1, 0, 0, 'go', '', '', '0000-00-00', ''),
('2', '1', '0', 0, 1, 1, 1, 0, 'come', '', '', '0000-00-00', ''),
('3', '1', '0', 0, 1, 1, 1, 0, 'is', '', '', '0000-00-00', ''),
('4', '1', '0', 0, 1, 0, 0, 0, 'write', '', '', '0000-00-00', ''),
('5', '1', '0', 0, 1, 0, 0, 0, 'be bore', '', '', '0000-00-00', ''),
('5', '1', '0', 0, 0, 1, 0, 0, 'be not salted enough', '', '', '0000-00-00', ''),
('6', '1', '0', 0, 1, 1, 0, 0, 'hurt oneself', '', '', '0000-00-00', ''),
('7', '1', '0', 0, 0, 1, 0, 0, 'occur', '', '', '0000-00-00', ''),
('7', '1', '0', 0, 1, 0, 0, 0, 'have an increasing space between', '', '', '0000-00-00', ''),
('8', '1', '0', 0, 1, 1, 0, 0, 'do', '', '', '0000-00-00', ''),
('13', '1', '0', 0, 1, 1, 1, 0, 'is', '', '', '0000-00-00', ''),
('14', '1', '0', 0, 0, 1, 1, 0, 'open', '', '', '0000-00-00', ''),
('15', '1', '0', 0, 1, 1, 1, 1, 'elicit', '', '', '0000-00-00', ''),
('16', '1', '0', 0, 1, 1, 0, 1, 'come to realize', '', '', '0000-00-00', ''),
('17', '1', '0', 0, 1, 1, 1, 0, 'be unsuccessful', '', '', '0000-00-00', ''),
('18', '1', '0', 0, 1, 1, 0, 0, 'eat', '', '', '0000-00-00', ''),
('19', '1', '0', 0, 0, 1, 0, 0, 'be not salty enough', '', '', '0000-00-00', ''),
('19', '1', '0', 0, 1, 0, 0, 0, 'be bore', '', '', '0000-00-00', ''),
('20', '1', '0', 0, 1, 0, 0, 0, 'live', '', '', '0000-00-00', ''),
('21', '1', '0', 0, 1, 1, 0, 0, 'fall down', '', '', '0000-00-00', ''),
('22', '1', '2', 0, 1, 1, 1, 0, 'be absent', '', '', '0000-00-00', ''),
('23', '1', '0', 0, 1, 0, 0, 0, 'explain', '', '', '0000-00-00', ''),
('24', '1', '0', 0, 1, 1, 0, 1, 'be able', 'been able', 'becoming', '0000-00-00', ''),
('25', '1', '0', 0, 0, 1, 1, 1, 'enter', '', 'entering', '0000-00-00', ''),
('26', '1', '1', 1, 1, 1, 1, 1, 'not be', 'non-', 'not the case', '0000-00-00', ''),
('28', '1', '0', 0, 1, 1, 0, 1, 'request', 'requesting', 'request', '0000-00-00', ''),
('29', '1', '0', 0, 0, 1, 0, 1, 'be able to start', 'ready', 'ready', '2007-05-25', 'danichem');

-- --------------------------------------------------------

-- 
-- 테이블 구조 `trans_kverbs`
-- 

CREATE TABLE `trans_kverbs` (
  `vin` int(8) NOT NULL auto_increment,
  `knocon` tinytext collate utf8_unicode_ci NOT NULL,
  `kcon` tinytext collate utf8_unicode_ci,
  `other` tinytext collate utf8_unicode_ci,
  `entry_date` date NOT NULL,
  `entry_id` varchar(8) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`vin`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=30 ;

-- 
-- 테이블의 덤프 데이터 `trans_kverbs`
-- 

INSERT INTO `trans_kverbs` (`vin`, `knocon`, `kcon`, `other`, `entry_date`, `entry_id`) VALUES (1, '가', NULL, NULL, '0000-00-00', ''),
(2, '오', '와', NULL, '0000-00-00', ''),
(3, '이', '이에', NULL, '0000-00-00', ''),
(4, '쓰', '써', NULL, '0000-00-00', ''),
(5, '심심하다', NULL, NULL, '0000-00-00', ''),
(6, '자해하', NULL, NULL, '0000-00-00', ''),
(7, '벌어지', '벌어져', NULL, '0000-00-00', ''),
(8, '하', '해', NULL, '0000-00-00', ''),
(9, '춥', '추워', '추우', '0000-00-00', ''),
(10, '듣', '들', NULL, '0000-00-00', ''),
(11, '돕', '도워', '도우', '0000-00-00', ''),
(12, '덥', '더워', '더우', '0000-00-00', ''),
(13, '있', '있어', NULL, '0000-00-00', ''),
(14, '열', '열어', NULL, '0000-00-00', ''),
(15, '자아내', NULL, NULL, '0000-00-00', ''),
(16, '실현해오', '실현해와', NULL, '0000-00-00', ''),
(17, '실패하', '실패해', NULL, '0000-00-00', ''),
(18, '먹', '먹어', NULL, '0000-00-00', ''),
(19, '싱겁', '싱거워', NULL, '0000-00-00', ''),
(20, '살', '살아', '사', '0000-00-00', ''),
(21, '넘어지', '넘어져', NULL, '0000-00-00', ''),
(22, '없', '없어', NULL, '0000-00-00', ''),
(23, '설명하', '설명해', NULL, '0000-00-00', ''),
(24, '되', '돼', NULL, '0000-00-00', ''),
(25, '접어들', '접어들어', NULL, '0000-00-00', ''),
(26, '아니', '아니에', '아니예', '0000-00-00', ''),
(27, '먹', '먹어', NULL, '2007-05-13', 'niche'),
(28, '부탁하', '부탁해', NULL, '2007-05-21', 'danichem'),
(29, '시작되', '시작돼', NULL, '2007-05-25', 'danichem');
