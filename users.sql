-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: 19-Nov-2018 às 23:45
-- Versão do servidor: 5.7.21
-- PHP Version: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rest`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_fullname` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `user_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `user_fullname`, `email`, `password`, `user_status`) VALUES
(1, 'summa', 'admin@kvcodes.com', '202cb962ac59075b964b07152d234b70', 1),
(4, 'Summa edited', 'second@kvcodes.com', '202cb962ac59075b964b07152d234b70', 1),
(5, 'Third', 'third@kvcodes.com', '202cb962ac59075b964b07152d234b70', 1),
(9, 'teste', 'teste@teste.com', 'teste', 9),
(10, 'Summa Than', 'summa@kvcodes.com', '5f4dcc3b5aa765d61d8327deb882cf99', 2),
(11, 'Summa Than', 'summa@kvcodes.com', '5f4dcc3b5aa765d61d8327deb882cf99', 2),
(12, 'Summa Than', 'summa@kvcodes.com', '5f4dcc3b5aa765d61d8327deb882cf99', 2),
(13, 'Summa Than', 'summa@kvcodes.com', '5f4dcc3b5aa765d61d8327deb882cf99', 2),
(14, 'Summa Than', 'summa@kvcodes.com', '5f4dcc3b5aa765d61d8327deb882cf99', 2),
(15, 'jorge', 'jorge', 'd67326a22642a324aa1b0745f2f17abb', 2),
(16, 'jorge', 'jorge', 'd67326a22642a324aa1b0745f2f17abb', 2),
(17, 'jorge', 'jorge@teste.com', 'd67326a22642a324aa1b0745f2f17abb', 2);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
