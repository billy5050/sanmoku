-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2024-12-19 13:36:52
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
  `game_result` varchar(5) NOT NULL,
  `time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- テーブルのデータのダンプ `score`
--

INSERT INTO `score` (`id`, `game_result`, `time`) VALUES
(81, 'drow', '2024-12-19 13:23:00'),
(82, 'drow', '2024-12-19 13:23:29'),
(83, 'drow', '2024-12-19 13:28:15'),
(84, 'win', '2024-12-19 13:29:28'),
(85, 'lose', '2024-12-19 13:29:36'),
(86, 'win', '2024-12-19 13:29:51'),
(87, 'win', '2024-12-19 13:30:02'),
(88, 'lose', '2024-12-19 13:30:15'),
(89, 'lose', '2024-12-19 13:30:52'),
(90, 'win', '2024-12-19 13:31:02');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=956;

--
-- テーブルの AUTO_INCREMENT `score`
--
ALTER TABLE `score`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- テーブルの AUTO_INCREMENT `test`
--
ALTER TABLE `test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1097;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
