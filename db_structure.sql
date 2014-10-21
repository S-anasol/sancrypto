-- phpMyAdmin SQL Dump
-- version 4.0.2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Окт 21 2014 г., 22:46
-- Версия сервера: 5.6.17-66.0-log
-- Версия PHP: 5.5.9-1ubuntu4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `crypto`
--
CREATE DATABASE IF NOT EXISTS `crypto` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `crypto`;

-- --------------------------------------------------------

--
-- Структура таблицы `cryptos`
--

CREATE TABLE IF NOT EXISTS `cryptos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `short` varchar(255) NOT NULL,
  `cpb` float NOT NULL,
  `exchange` varchar(500) NOT NULL,
  `explorer` varchar(500) NOT NULL,
  `bttalk` varchar(300) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `exid` varchar(255) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `algo` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=46 ;

INSERT INTO `cryptos` (`id`, `name`, `short`, `cpb`, `exchange`, `explorer`, `bttalk`, `logo`, `exid`, `status`, `algo`) VALUES
(1, 'FlappyCoin', 'FLAP', 500000, 'poloniex', 'http://flapplorer.flappycoin.info/chain/FlappyCoin', 'https://bitcointalk.org/index.php?topic=464743.0', 'flappy.png', 'FLAP', 0, 1),
(3, 'RabbitCoin', 'RBBT', 500000, 'poloniex', 'http://rabbitco.in:8080/chain/RabbitCoin', 'https://bitcointalk.org/index.php?topic=466621.0', 'rabbit.png', 'RBBT', 0, 1),
(4, 'ReddCoin', 'RDD', 100000, 'poloniex', 'http://cryptexplorer.com/chain/ReddCoin', 'https://bitcointalk.org/index.php?topic=423597.0', 'redd.png', 'REDD', 0, 1),
(5, 'RubyCoin', 'RUBY', 500, 'cryptorush', 'http://explorer.sancrypto.info/chain/rubycoin', 'https://bitcointalk.org/index.php?topic=459622.0', 'ruby.png', '-', 0, 1),
(6, 'LiteBar', 'LTB', 2, 'cryptorush', 'http://explorer.sancrypto.info/chain/litebar', 'https://bitcointalk.org/index.php?topic=461108', 'litebar.png', '-', 0, 1),
(7, 'AuroraCoin', 'AUR', 12, 'cryptsy', 'http://blockexplorer.auroracoin.eu/chain/AuroraCoin', 'https://bitcointalk.org/index.php?topic=446062', 'aurora.png', '160', 1, 1),
(8, 'ZeitCoin', 'ZEIT', 625000, 'cryptorush', 'http://explorer.sancrypto.info/chain/Zeitcoin', 'https://bitcointalk.org/index.php?topic=487814.0', 'zeit.png', '-', 0, 1),
(9, 'CorgiCoin', 'CORG', 500000, 'poloniex', 'http://cryptexplorer.com/chain/CorgiCoin', 'https://bitcointalk.org/index.php?topic=444700.0', 'corgi.png', 'CORG', 0, 1),
(10, 'DNotes', 'NOTE', 25, 'allcoin', 'http://cryptoblox.com/chain/DNotes', 'https://bitcointalk.org/index.php?topic=470155.0', 'dnotes.png', 'NOTE', 1, 1),
(11, 'Einsteinium', 'EMC2', 512, 'allcoin', 'http://cryptexplorer.com/chain/Einsteinium', 'https://bitcointalk.org/index.php?topic=494708.0', 'emc2.png', 'EMC2', 1, 1),
(12, 'MarsCoin', 'MRS', 50, 'mintpal', 'http://explore.marscoin.org/chain/Marscoin', 'https://bitcointalk.org/index.php?topic=434971.0', 'mars.png', 'MRS', 1, 1),
(13, 'Credits', 'CR', 796, 'allcoin', 'http://explorer.sancrypto.info/chain/Credits', 'https://bitcointalk.org/index.php?topic=496685.0', 'cr.png', 'CR', 1, 1),
(14, 'Dogecoin', 'DOGE', 125000, 'allcoin', 'http://dogechain.info/chain/Dogecoin', 'https://bitcointalk.org/index.php?topic=361813.0', 'doge.png', 'DOGE', 1, 1),
(16, 'MinCoin', 'MNC', 2, 'cryptsy', 'http://explorer.sancrypto.info/chain/Mincoin', 'https://bitcointalk.org/index.php?topic=165397.0', 'min.png', '7', 1, 1),
(17, 'Heisenberg', 'HEX', 4000, 'bleutrade', 'http://explorer2.sancrypto.info/chain/HEX', 'https://bitcointalk.org/index.php?topic=563923.0', 'hex.png', 'HEX', 1, 1),
(18, 'OctoCoin', '888', 188, 'bittrex', 'http://explorer.octocoin.org/chain/OctoCoin', 'https://bitcointalk.org/index.php?topic=504265.0', 'octo.png', '888', 1, 1),
(19, 'EarthCoin', 'EAC', 11888, 'cryptsy', 'http://earthchain.info/chain/EarthCoin', 'https://bitcointalk.org/index.php?topic=379236.0', 'eac.png', '139', 1, 1),
(20, 'New York Coin', 'NYC', 500000, 'xnigma', 'http://explorer2.sancrypto.info/chain/NYC', 'https://bitcointalk.org/index.php?topic=499842.0', 'empty.png', 'NYC', 0, 1),
(21, 'COINO', 'CON', 35, 'poloniex', 'http://cryptexplorer.com/chain/Coino', 'https://bitcointalk.org/index.php?topic=419873.0', 'con.png', 'CON', 1, 1),
(22, 'Myriad', 'MYR', 1000, 'poloniex', 'http://myr.theblockexplorer.com:2750/chain/Myriadcoin', 'https://bitcointalk.org/index.php?topic=483515.0', 'myr.png', 'MYR', 0, 1),
(23, 'Saturncoin', 'SAT', 31250, 'mintpal', 'http://23.252.113.113:3312/chain/Saturncoin', 'https://bitcointalk.org/index.php?topic=441760.0', 'sat.png', 'SAT', 1, 1),
(24, 'PotCoin', 'POT', 210, 'mintpal', 'http://potchain.aprikos.net/chain/Potcoin', 'https://bitcointalk.org/index.php?topic=426324.0', 'pot.png', 'POT', 1, 1),
(25, 'DopeCoin', 'DOPE', 475, 'allcoin', 'http://explorer.dopecoin.com/chain/DopeCoin', 'https://bitcointalk.org/index.php?topic=467641.0', 'dope.png', 'dope', 1, 1),
(27, 'FeatherCoin', 'FTC', 80, 'btce', 'https://explorer.feathercoin.com/chain/Feathercoin', 'https://bitcointalk.org/index.php?topic=178286.0', 'ftc.png', 'ftc', 1, 1),
(28, 'USDe', 'USDE', 2000, 'allcoin', 'http://explorer2.sancrypto.info/chain/USDe', 'https://bitcointalk.org/index.php?topic=410254', 'usde.png', 'USDE', 1, 1),
(29, 'Pawncoin', 'PAWN', 1, 'poloniex', 'http://cryptoblox.com/chain/PawnCoin', 'https://bitcointalk.org/index.php?topic=508824.0', 'pawn.png', 'PAWN', 1, 1),
(30, 'EmotiCoin', 'EMO', 1000000, 'cryptorush', 'http://blocks.emoticoin.org/chain/EmotiCoin', 'https://bitcointalk.org/index.php?topic=496129.0', 'empty.png', 'EMO', 0, 1),
(31, 'MtGoxCoin', 'GOX', 25, 'cryptorush', 'http://explorer3.sancrypto.info/chain/MtGoxCoin', 'https://bitcointalk.org/index.php?topic=506287', 'empty.png', 'GOX', 0, 1),
(32, 'Procoin', 'PCN', 1000, 'bittrex', 'http://pcn.exploreblocks.com:2750/chain/Procoin', 'https://bitcointalk.org/index.php?topic=511678.0', 'empty.png', 'PCN', 1, 1),
(33, 'Lycancoin', 'LYC', 2970, 'cryptsy', 'http://explorer3.sancrypto.info/chain/Lycancoin', 'https://bitcointalk.org/index.php?topic=527015', 'lyc.png', '177', 1, 1),
(34, 'SpainCoin', 'SPA', 93, 'mintpal', 'http://explorer.spaincoin.org/chain/Spaincoin', 'https://bitcointalk.org/index.php?topic=500511', 'spa.png', 'SPA', 1, 2),
(35, 'VertCoin', 'VTC', 50, 'bter', 'https://explorer.vertcoin.org/chain/Vertcoin', 'https://bitcointalk.org/index.php?topic=404364.0', 'vert.png', 'vtc', 1, 2),
(36, 'KlondikeCoin', 'KDC', 8, 'cryptsy', 'http://explorer.klondikecoin.com/chain/KlondikeCoin', 'https://bitcointalk.org/index.php?topic=407705.0', 'kdc.png', '178', 1, 1),
(37, 'UltraCoin', 'UTC', 50, 'cryptsy', 'http://bitgo.pw:3731/chain/Ultracoin', 'https://bitcointalk.org/index.php?topic=413978.0', 'utc.png', '163', 0, 2),
(38, 'GPU Coin', 'GPUC', 20000, 'bittrex', 'http://explorer3.sancrypto.info/chain/GPUCoin', 'https://bitcointalk.org/index.php?topic=469887.0', 'gpu.png', 'GPUC', 0, 2),
(39, 'BlitzCoin', 'BLTZ', 0, 'bittrex', 'http://cryptexplorer.com/chain/BlitzCoin', 'https://bitcointalk.org/index.php?topic=509173.0', 'blitz.png', 'BLTZ', 1, 1),
(40, 'PLNcoin', 'PLNc', 44, 'bittrex', 'http://explorer3.sancrypto.info/chain/PLNCoin', 'https://bitcointalk.org/index.php?topic=509433.0', 'pln.png', 'PLNc', 1, 1),
(41, 'ElectronicGulden', 'EFL', 25, 'bittrex', 'http://cryptoblox.com/chain/Electronic%20Gulden', 'https://bitcointalk.org/index.php?topic=522661.0', 'empty.png', 'EFL', 1, 1),
(42, 'Bones', 'BONES', 10, 'poloniex', 'http://cryptexplorer.com/chain/Bones', 'https://bitcointalk.org/index.php?topic=521270.0', 'bones.png', 'BONES', 1, 1),
(43, 'HongKetoCoin', 'HKC', 3000, 'bittrex', 'http://hkc.exploreblocks.com:2754/chain/HongKetoCoin', 'https://bitcointalk.org/index.php?topic=612919.0', 'empty.png', 'HKC', 1, 3),
(44, 'Unobtanium', 'UNO', 0.125, 'cryptsy', 'http://cryptexplorer.com/chain/Unobtanium', 'https://bitcointalk.org/index.php?topic=313126.0', 'empty.png', '133', 1, 3),
(45, 'Teacoin', 'TEA', 1, 'coinex', 'http://teachain.info/chain/Teacoin', 'https://bitcointalk.org/index.php?topic=434432.0', 'empty.png', '77', 1, 3);
-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `time` int(30) NOT NULL,
  `author` int(30) NOT NULL,
  `views` int(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Структура таблицы `prices`
--

CREATE TABLE IF NOT EXISTS `prices` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `crypto_id` int(30) NOT NULL,
  `exchange` varchar(30) NOT NULL,
  `price` double NOT NULL,
  `time` int(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `crypto` (`crypto_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5921050 ;

-- --------------------------------------------------------

--
-- Структура таблицы `profits`
--

CREATE TABLE IF NOT EXISTS `profits` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `time` int(30) NOT NULL,
  `crypto_id` int(30) NOT NULL,
  `percent` int(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `crypto` (`crypto_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5197132 ;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `key` varchar(32) DEFAULT NULL,
  `last_login` int(30) NOT NULL,
  `create_time` int(30) NOT NULL,
  `ip` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
