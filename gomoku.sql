-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2024-12-19 09:33:53
-- サーバのバージョン： 10.4.27-MariaDB
-- PHP のバージョン: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `gomoku`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `cpu`
--

CREATE TABLE `cpu` (
  `id` int(11) NOT NULL,
  `place` varchar(255) NOT NULL,
  `time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `score`
--

CREATE TABLE `score` (
  `id` int(11) NOT NULL,
  `vD` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- テーブルのデータのダンプ `score`
--

INSERT INTO `score` (`id`, `vD`) VALUES
(1, 'd'),
(2, 'v'),
(3, 'v'),
(4, 'v'),
(5, 'v'),
(6, 'v'),
(7, 'd'),
(8, 'v'),
(9, 'd'),
(10, 'd'),
(11, 'd'),
(12, 'v'),
(13, 'd'),
(14, 'd'),
(15, 'v'),
(16, 'd'),
(17, 'd'),
(18, 'v'),
(19, 'v'),
(20, 'v'),
(21, 'd'),
(22, 'd'),
(23, 'v'),
(24, 'd'),
(25, 'd'),
(26, 'd'),
(27, 'v'),
(28, 'v'),
(29, 'd'),
(30, 'd'),
(31, 'd'),
(32, 'v'),
(33, 'v'),
(34, 'v'),
(35, 'v'),
(36, 'd'),
(37, 'v'),
(38, 'd'),
(39, 'd'),
(40, 'd'),
(41, 'd'),
(42, 'v'),
(43, 'd'),
(44, 'd'),
(45, 'd'),
(46, 'v'),
(47, 'v'),
(48, 'v'),
(49, 'v'),
(50, 'v'),
(51, 'v'),
(52, 'v'),
(53, 'd'),
(54, 'v'),
(55, 'v'),
(56, 'd'),
(57, 'v'),
(58, 'v'),
(59, 'd');

-- --------------------------------------------------------

--
-- テーブルの構造 `test`
--

CREATE TABLE `test` (
  `id` int(11) NOT NULL,
  `place` varchar(255) NOT NULL,
  `process` varchar(255) DEFAULT NULL,
  `time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `cpu`
--
ALTER TABLE `cpu`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `score`
--
ALTER TABLE `score`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `cpu`
--
ALTER TABLE `cpu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=842;

--
-- テーブルの AUTO_INCREMENT `score`
--
ALTER TABLE `score`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- テーブルの AUTO_INCREMENT `test`
--
ALTER TABLE `test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=926;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
